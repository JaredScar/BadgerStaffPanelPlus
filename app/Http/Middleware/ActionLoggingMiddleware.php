<?php

namespace App\Http\Middleware;

use App\Services\DiscordWebhookService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ActionLoggingMiddleware
{
    protected $webhookService;

    public function __construct()
    {
        $this->webhookService = new DiscordWebhookService();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only log successful actions (2xx status codes)
        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            $this->logAction($request, $response);
        }

        return $response;
    }

    /**
     * Log the action based on the request
     */
    protected function logAction(Request $request, $response)
    {
        try {
            $route = $request->route();
            if (!$route) return;

            $action = $route->getActionMethod();
            $routeName = $route->getName();
            $uri = $request->getRequestUri();
            $method = $request->getMethod();

            // Skip logging for certain routes
            if ($this->shouldSkipLogging($uri, $method)) {
                return;
            }

            // Determine action type based on route and method
            $actionType = $this->determineActionType($uri, $method, $action);
            
            if (!$actionType) return;

            // Get staff information
            $staff = Auth::user();
            if (!$staff) return;

            // Get response data for logging
            $responseData = $this->extractResponseData($response);
            
            // Log to Discord webhook
            $this->webhookService->logAction($actionType, [
                'staff_username' => $staff->staff_username ?? 'Unknown',
                'server_name' => $staff->server->server_name ?? null,
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'route' => $routeName,
                'method' => $method,
                'uri' => $uri,
                'response_data' => $responseData
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to log action in middleware', [
                'error' => $e->getMessage(),
                'uri' => $request->getRequestUri(),
                'method' => $request->getMethod()
            ]);
        }
    }

    /**
     * Determine if logging should be skipped for this request
     */
    protected function shouldSkipLogging($uri, $method)
    {
        $skipPatterns = [
            '/api/logs',
            '/api/health',
            '/api/metrics',
            '/_debugbar',
            '/telescope',
            '/horizon'
        ];

        foreach ($skipPatterns as $pattern) {
            if (str_contains($uri, $pattern)) {
                return true;
            }
        }

        // Skip GET requests for most routes (except important ones)
        if ($method === 'GET' && !$this->isImportantGetRequest($uri)) {
            return true;
        }

        return false;
    }

    /**
     * Check if this is an important GET request that should be logged
     */
    protected function isImportantGetRequest($uri)
    {
        $importantPatterns = [
            '/api/players/',
            '/api/staff/',
            '/api/dashboard/',
            '/api/records/'
        ];

        foreach ($importantPatterns as $pattern) {
            if (str_contains($uri, $pattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine the action type based on the request
     */
    protected function determineActionType($uri, $method, $action)
    {
        // Dashboard actions
        if (str_contains($uri, '/dashboard')) {
            if (str_contains($action, 'create')) return 'dashboard_create';
            if (str_contains($action, 'delete')) return 'dashboard_delete';
            if (str_contains($action, 'switch')) return 'dashboard_switch';
            if (str_contains($action, 'save')) return 'dashboard_save';
            if (str_contains($action, 'add_widget')) return 'widget_add';
        }

        // Record actions
        if (str_contains($uri, '/bans')) {
            if ($method === 'POST') return 'ban_create';
            if ($method === 'PUT' || $method === 'PATCH') return 'ban_update';
            if ($method === 'DELETE') return 'ban_delete';
        }

        if (str_contains($uri, '/warns')) {
            if ($method === 'POST') return 'warn_create';
            if ($method === 'PUT' || $method === 'PATCH') return 'warn_update';
            if ($method === 'DELETE') return 'warn_delete';
        }

        if (str_contains($uri, '/kicks')) {
            if ($method === 'POST') return 'kick_create';
            if ($method === 'PUT' || $method === 'PATCH') return 'kick_update';
            if ($method === 'DELETE') return 'kick_delete';
        }

        if (str_contains($uri, '/notes')) {
            if ($method === 'POST') return 'note_create';
            if ($method === 'PUT' || $method === 'PATCH') return 'note_update';
            if ($method === 'DELETE') return 'note_delete';
        }

        if (str_contains($uri, '/commends')) {
            if ($method === 'POST') return 'commend_create';
            if ($method === 'PUT' || $method === 'PATCH') return 'commend_update';
            if ($method === 'DELETE') return 'commend_delete';
        }

        if (str_contains($uri, '/trustscores')) {
            if ($method === 'POST') {
                // Check if this is a reset operation
                if (str_contains($uri, '/reset')) return 'trustscore_reset';
                return 'trustscore_create';
            }
            if ($method === 'PUT' || $method === 'PATCH') return 'trustscore_update';
            if ($method === 'DELETE') return 'trustscore_delete';
        }

        // Staff actions
        if (str_contains($uri, '/staff')) {
            if ($method === 'POST') return 'staff_create';
            if ($method === 'PUT' || $method === 'PATCH') return 'staff_update';
            if ($method === 'DELETE') return 'staff_delete';
        }

        // Token actions
        if (str_contains($uri, '/tokens')) {
            if ($method === 'POST') return 'token_create';
            if ($method === 'PUT' || $method === 'PATCH') return 'token_update';
            if ($method === 'DELETE') return 'token_deactivate';
        }

        // Settings actions
        if (str_contains($uri, '/settings')) {
            if ($method === 'PUT' || $method === 'PATCH') return 'settings_update';
        }

        // Generic actions
        if ($method === 'POST') return 'record_create';
        if ($method === 'PUT' || $method === 'PATCH') return 'record_update';
        if ($method === 'DELETE') return 'record_delete';

        return null;
    }

    /**
     * Extract relevant data from the response
     */
    protected function extractResponseData($response)
    {
        try {
            $content = $response->getContent();
            $data = json_decode($content, true);
            
            if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                // Extract relevant information
                $relevantData = [];
                
                if (isset($data['message'])) {
                    $relevantData['message'] = $data['message'];
                }
                
                if (isset($data['success'])) {
                    $relevantData['success'] = $data['success'];
                }
                
                if (isset($data['id'])) {
                    $relevantData['id'] = $data['id'];
                }
                
                return $relevantData;
            }
        } catch (\Exception $e) {
            // Ignore errors in data extraction
        }
        
        return null;
    }
}
