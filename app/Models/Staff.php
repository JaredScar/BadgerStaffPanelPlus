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
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'staff_username',
        'password',
        'staff_email',
        'staff_discord',
        'server_id',
        'role',
        'status',
        'join_date',
        'notes',
        'last_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'join_date' => 'date',
        'last_active' => 'datetime',
    ];

    public static function getIdByUsername($username) {
        $staff = self::where('staff_username', $username)->first();

        // Check if the staff exists
        if ($staff) {
            return $staff->staff_id;
        }

        return null; // Staff not found
    }

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
    public function warns() {
        return $this->hasMany('App\Models\Warn', 'staff_id');
    }

    /**
     * Get total actions count for this staff member
     */
    public function getTotalActionsCount() {
        return $this->kicks()->count() + 
               $this->bans()->count() + 
               $this->commends()->count() + 
               $this->notes()->count() + 
               $this->warns()->count();
    }

    /**
     * Get staff statistics
     */
    public static function getStaffStatistics() {
        $stats = [
            'admins' => self::where('role', 'admin')->where('status', 'active')->count(),
            'moderators' => self::where('role', 'moderator')->where('status', 'active')->count(),
            'helpers' => self::where('role', 'helper')->where('status', 'active')->count(),
            'active_staff' => self::where('status', 'active')->count(),
            'total_staff' => self::count()
        ];
        
        return $stats;
    }

    /**
     * Get all staff with their action counts
     */
    public static function getAllStaffWithStats() {
        return self::withCount(['kicks', 'bans', 'commends', 'notes', 'warns'])
                   ->orderBy('created_at', 'desc')
                   ->get()
                   ->map(function($staff) {
                       $staff->total_actions = $staff->kicks_count + $staff->bans_count + 
                                              $staff->commends_count + $staff->notes_count + $staff->warns_count;
                       return $staff;
                   });
    }
}
