<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    use HasFactory;

    protected $table = 'servers';
    protected $primaryKey = 'server_id';

    protected $fillable = [
        'server_name',
        'server_slug',
        'discord_webhook_url',
        'webhook_enabled'
    ];

    protected $casts = [
        'webhook_enabled' => 'boolean'
    ];

    public function staff()
    {
        return $this->hasMany(Staff::class, 'server_id');
    }

    public function players()
    {
        return $this->hasMany(Player::class, 'server_id');
    }

    public function bans()
    {
        return $this->hasMany(Ban::class, 'server_id');
    }

    public function kicks()
    {
        return $this->hasMany(Kick::class, 'server_id');
    }

    public function warns()
    {
        return $this->hasMany(Warn::class, 'server_id');
    }

    public function notes()
    {
        return $this->hasMany(Note::class, 'server_id');
    }

    public function commends()
    {
        return $this->hasMany(Commend::class, 'server_id');
    }

    public function trustScores()
    {
        return $this->hasMany(TrustScore::class, 'server_id');
    }

    public function isWebhookEnabled()
    {
        return $this->webhook_enabled && !empty($this->discord_webhook_url);
    }

    public function getWebhookUrl()
    {
        return $this->isWebhookEnabled() ? $this->discord_webhook_url : null;
    }

    /**
     * Get server name by ID
     *
     * @param int $serverId
     * @return string|null
     */
    public static function getServerNameById($serverId)
    {
        $server = self::find($serverId);
        return $server ? $server->server_name : null;
    }
}
