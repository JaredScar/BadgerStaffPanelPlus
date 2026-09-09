<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebAuthenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo($request): string|null
    {
        // Log authentication check
        Log::info('WebAuthenticate middleware - checking authentication', [
            'uri' => $request->getRequestUri(),
            'method' => $request->getMethod(),
            'expects_json' => $request->expectsJson(),
            'has_session' => $request->hasSession(),
            'session_id' => $request->session()->getId() ?? 'no_session',
            'auth_check' => auth()->check(),
            'user_id' => auth()->id()
        ]);
        
        if ($request->expectsJson()) {
            return null;
        }
        
        return route('START');
    }
    
    /**
     * Handle an unauthenticated user.
     */
    protected function unauthenticated($request, array $guards)
    {
        Log::warning('WebAuthenticate middleware - user not authenticated', [
            'uri' => $request->getRequestUri(),
            'method' => $request->getMethod(),
            'expects_json' => $request->expectsJson(),
            'guards' => $guards
        ]);
        
        if ($request->expectsJson()) {
            abort(401, 'Unauthenticated.');
        }
        
        abort(redirect()->guest($this->redirectTo($request)));
    }
}
