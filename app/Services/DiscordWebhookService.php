<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Server;

class DiscordWebhookService
{
    protected $webhookUrl;
    protected $serverId;

    public function __construct($serverId = 1)
    {
        $this->serverId = $serverId;
        $this->loadWebhookUrl();
    }

    protected function loadWebhookUrl()
    {
        $server = Server::find($this->serverId);
        if ($server && $server->webhook_enabled && $server->discord_webhook_url) {
            $this->webhookUrl = $server->discord_webhook_url;
        } else {
            $this->webhookUrl = null;
        }
    }

    public function logAction($action, $data = [], $color = 0x00ff00)
    {
        if (!$this->webhookUrl) {
            Log::info('Discord webhook not configured, skipping log', [
                'action' => $action,
                'data' => $data
            ]);
            return false;
        }

        $embed = [
            'title' => $this->getActionTitle($action),
            'description' => $this->getActionDescription($action, $data),
            'color' => $color,
            'timestamp' => now()->toISOString(),
            'footer' => [
                'text' => 'Badger Staff Panel'
            ],
            'fields' => $this->getActionFields($action, $data)
        ];

        $payload = [
            'embeds' => [$embed]
        ];

        try {
            $response = Http::post($this->webhookUrl, $payload);
            
            if ($response->successful()) {
                Log::info('Discord webhook sent successfully', [
                    'action' => $action,
                    'response' => $response->status()
                ]);
                return true;
            } else {
                Log::error('Discord webhook failed', [
                    'action' => $action,
                    'response' => $response->status(),
                    'body' => $response->body()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Discord webhook exception', [
                'action' => $action,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    protected function getActionTitle($action)
    {
        $titles = [
            'ban_create' => '🚫 Ban Created',
            'ban_update' => '✏️ Ban Updated',
            'ban_delete' => '✅ Ban Removed',
            'warn_create' => '⚠️ Warning Issued',
            'warn_update' => '✏️ Warning Updated',
            'warn_delete' => '✅ Warning Removed',
            'kick_create' => '👢 Player Kicked',
            'kick_update' => '✏️ Kick Record Updated',
            'kick_delete' => '✅ Kick Record Removed',
            'note_create' => '📝 Note Added',
            'note_update' => '✏️ Note Updated',
            'note_delete' => '✅ Note Removed',
            'commend_create' => '🏆 Commendation Given',
            'commend_update' => '✏️ Commendation Updated',
            'commend_delete' => '✅ Commendation Removed',
            'trustscore_create' => '⭐ Trust Score Created',
            'trustscore_update' => '✏️ Trust Score Updated',
            'trustscore_reset' => '🔄 Trust Score Reset',
            'trustscore_delete' => '✅ Trust Score Removed',
            'staff_create' => '👤 Staff Member Added',
            'staff_update' => '✏️ Staff Member Updated',
            'staff_delete' => '🗑️ Staff Member Removed',
            'staff_login' => '🔐 Staff Login',
            'staff_logout' => '🚪 Staff Logout',
            'api_login' => '🔑 API Login',
            'token_create' => '🔑 Token Created',
            'token_update' => '✏️ Token Updated',
            'token_deactivate' => '🚫 Token Deactivated',
            'player_join' => '🎮 Player Joined',
            'player_leave' => '👋 Player Left',
            'dashboard_create' => '📊 Dashboard Created',
            'dashboard_delete' => '🗑️ Dashboard Deleted',
            'dashboard_switch' => '🔄 Dashboard Switched',
            'dashboard_save' => '💾 Dashboard Saved',
            'widget_add' => '➕ Widget Added',
            'widget_remove' => '➖ Widget Removed',
            'settings_update' => '⚙️ Settings Updated'
        ];

        return $titles[$action] ?? '📋 Action Logged';
    }

    protected function getActionDescription($action, $data)
    {
        $descriptions = [
            'ban_create' => "Player **{$data['player_name']}** has been banned",
            'ban_update' => "Ban for **{$data['player_name']}** has been updated",
            'ban_delete' => "Ban for **{$data['player_name']}** has been removed",
            'warn_create' => "Warning issued to **{$data['player_name']}**",
            'warn_update' => "Warning for **{$data['player_name']}** has been updated",
            'warn_delete' => "Warning for **{$data['player_name']}** has been removed",
            'kick_create' => "Player **{$data['player_name']}** has been kicked",
            'kick_update' => "Kick record for **{$data['player_name']}** has been updated",
            'kick_delete' => "Kick record for **{$data['player_name']}** has been removed",
            'note_create' => "Note added for **{$data['player_name']}**",
            'note_update' => "Note for **{$data['player_name']}** has been updated",
            'note_delete' => "Note for **{$data['player_name']}** has been removed",
            'commend_create' => "Commendation given to **{$data['player_name']}**",
            'commend_update' => "Commendation for **{$data['player_name']}** has been updated",
            'commend_delete' => "Commendation for **{$data['player_name']}** has been removed",
            'trustscore_create' => "Trust score created for **{$data['player_name']}**",
            'trustscore_update' => "Trust score for **{$data['player_name']}** has been updated",
            'trustscore_reset' => "Trust score for **{$data['player_name']}** has been reset",
            'trustscore_delete' => "Trust score for **{$data['player_name']}** has been removed",
            'staff_create' => "New staff member **{$data['username']}** has been added",
            'staff_update' => "Staff member **{$data['username']}** has been updated",
            'staff_delete' => "Staff member **{$data['username']}** has been removed",
            'staff_login' => "Staff member **{$data['username']}** has logged in",
            'staff_logout' => "Staff member **{$data['username']}** has logged out",
            'api_login' => "API access granted to **{$data['username']}**",
            'token_create' => "New token created for **{$data['username']}**",
            'token_update' => "Token for **{$data['username']}** has been updated",
            'token_deactivate' => "Token for **{$data['username']}** has been deactivated",
            'player_join' => "Player **{$data['player_name']}** joined the server",
            'player_leave' => "Player **{$data['player_name']}** left the server",
            'dashboard_create' => "New dashboard **{$data['dashboard_name']}** has been created",
            'dashboard_delete' => "Dashboard **{$data['dashboard_name']}** has been deleted",
            'dashboard_switch' => "Switched to dashboard **{$data['dashboard_name']}**",
            'dashboard_save' => "Dashboard **{$data['dashboard_name']}** has been saved",
            'widget_add' => "Widget **{$data['widget_type']}** has been added",
            'widget_remove' => "Widget **{$data['widget_type']}** has been removed",
            'settings_update' => "Server settings have been updated"
        ];

        return $descriptions[$action] ?? 'An action has been performed in the system';
    }

    protected function getActionFields($action, $data)
    {
        $fields = [];

        // Common fields
        if (isset($data['staff_username'])) {
            $fields[] = [
                'name' => 'Staff Member',
                'value' => $data['staff_username'],
                'inline' => true
            ];
        }

        if (isset($data['player_name'])) {
            $fields[] = [
                'name' => 'Player',
                'value' => $data['player_name'],
                'inline' => true
            ];
        }

        if (isset($data['reason'])) {
            $fields[] = [
                'name' => 'Reason',
                'value' => $data['reason'],
                'inline' => false
            ];
        }

        if (isset($data['server_name'])) {
            $fields[] = [
                'name' => 'Server',
                'value' => $data['server_name'],
                'inline' => true
            ];
        }

        if (isset($data['timestamp'])) {
            $fields[] = [
                'name' => 'Time',
                'value' => $data['timestamp'],
                'inline' => true
            ];
        }

        // Action-specific fields
        switch ($action) {
            case 'ban_create':
            case 'ban_delete':
                if (isset($data['expires'])) {
                    $fields[] = [
                        'name' => 'Expires',
                        'value' => $data['expires'],
                        'inline' => true
                    ];
                }
                break;

            case 'trustscore_create':
            case 'trustscore_update':
            case 'trustscore_reset':
                if (isset($data['trust_score'])) {
                    $fields[] = [
                        'name' => 'New Trust Score',
                        'value' => $data['trust_score'],
                        'inline' => true
                    ];
                }
                if (isset($data['old_score'])) {
                    $fields[] = [
                        'name' => 'Previous Trust Score',
                        'value' => $data['old_score'],
                        'inline' => true
                    ];
                }
                break;

            case 'staff_create':
            case 'staff_update':
                if (isset($data['role'])) {
                    $fields[] = [
                        'name' => 'Role',
                        'value' => ucfirst($data['role']),
                        'inline' => true
                    ];
                }
                if (isset($data['email'])) {
                    $fields[] = [
                        'name' => 'Email',
                        'value' => $data['email'],
                        'inline' => true
                    ];
                }
                break;

            case 'token_create':
                if (isset($data['note'])) {
                    $fields[] = [
                        'name' => 'Token Note',
                        'value' => $data['note'],
                        'inline' => false
                    ];
                }
                break;
        }

        return $fields;
    }

    // Convenience methods for common actions
    public function logBanCreate($playerName, $staffUsername, $reason, $expires = null, $serverName = null)
    {
        return $this->logAction('ban_create', [
            'player_name' => $playerName,
            'staff_username' => $staffUsername,
            'reason' => $reason,
            'expires' => $expires,
            'server_name' => $serverName,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ], 0xff0000); // Red for bans
    }

    public function logBanDelete($playerName, $staffUsername, $serverName = null)
    {
        return $this->logAction('ban_delete', [
            'player_name' => $playerName,
            'staff_username' => $staffUsername,
            'server_name' => $serverName,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ], 0x00ff00); // Green for removals
    }

    public function logWarnCreate($playerName, $staffUsername, $reason, $serverName = null)
    {
        return $this->logAction('warn_create', [
            'player_name' => $playerName,
            'staff_username' => $staffUsername,
            'reason' => $reason,
            'server_name' => $serverName,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ], 0xffa500); // Orange for warnings
    }

    public function logKickCreate($playerName, $staffUsername, $reason, $serverName = null)
    {
        return $this->logAction('kick_create', [
            'player_name' => $playerName,
            'staff_username' => $staffUsername,
            'reason' => $reason,
            'server_name' => $serverName,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ], 0xff8c00); // Dark orange for kicks
    }

    public function logNoteCreate($playerName, $staffUsername, $note, $serverName = null)
    {
        return $this->logAction('note_create', [
            'player_name' => $playerName,
            'staff_username' => $staffUsername,
            'reason' => $note,
            'server_name' => $serverName,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ], 0x4169e1); // Royal blue for notes
    }

    public function logCommendCreate($playerName, $staffUsername, $reason, $serverName = null)
    {
        return $this->logAction('commend_create', [
            'player_name' => $playerName,
            'staff_username' => $staffUsername,
            'reason' => $reason,
            'server_name' => $serverName,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ], 0xffd700); // Gold for commendations
    }

    public function logTrustScoreUpdate($playerName, $staffUsername, $trustScore, $serverName = null)
    {
        return $this->logAction('trustscore_create', [
            'player_name' => $playerName,
            'staff_username' => $staffUsername,
            'trust_score' => $trustScore,
            'server_name' => $serverName,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ], 0x32cd32); // Lime green for trust scores
    }

    public function logStaffCreate($username, $role, $email, $staffUsername, $serverName = null)
    {
        return $this->logAction('staff_create', [
            'username' => $username,
            'role' => $role,
            'email' => $email,
            'staff_username' => $staffUsername,
            'server_name' => $serverName,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ], 0x9370db); // Medium purple for staff actions
    }

    public function logTokenCreate($username, $note, $staffUsername, $serverName = null)
    {
        return $this->logAction('token_create', [
            'username' => $username,
            'note' => $note,
            'staff_username' => $staffUsername,
            'server_name' => $serverName,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ], 0x20b2aa); // Light sea green for tokens
    }

    public function logDashboardCreate($dashboardName, $staffUsername, $serverName = null)
    {
        return $this->logAction('dashboard_create', [
            'dashboard_name' => $dashboardName,
            'staff_username' => $staffUsername,
            'server_name' => $serverName,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ], 0x00bfff); // Deep sky blue for dashboard actions
    }

    public function logWidgetAdd($widgetType, $staffUsername, $serverName = null)
    {
        return $this->logAction('widget_add', [
            'widget_type' => $widgetType,
            'staff_username' => $staffUsername,
            'server_name' => $serverName,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ], 0x00ced1); // Dark turquoise for widget actions
    }
}
