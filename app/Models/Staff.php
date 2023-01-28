<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Query\Builder;

/**
 * @mixin Builder
 */
class Staff extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'staff';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'staff_id';
    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'U';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'staff_username',
        'staff_password',
        'staff_email',
        'staff_discord',
        'server_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'staff_password'
    ];

    public function kicks() {
        return $this->hasMany('App\Models\Kick', 'staff_id');
    }
    public function bans() {
        return $this->hasMany('App\Models\Ban', 'staff_id');
    }
    public function commends() {
        return $this->hasMany('App\Models\Commend', 'staff_id');
    }
    public function notes() {
        return $this->hasMany('App\Models\Note', 'staff_id');
    }

}
