<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Add sample staff members
        $staffMembers = [
            [
                'staff_username' => 'admin_user',
                'staff_email' => 'admin@example.com',
                'staff_discord' => 'AdminUser#0001',
                'role' => 'admin',
                'status' => 'active',
                'join_date' => '2023-06-01',
                'notes' => 'Main administrator',
                'password' => Hash::make('password123'),
                'server_id' => 1
            ],
            [
                'staff_username' => 'mod_user',
                'staff_email' => 'mod@example.com',
                'staff_discord' => 'ModUser#0002',
                'role' => 'moderator',
                'status' => 'active',
                'join_date' => '2023-08-15',
                'notes' => 'Senior moderator',
                'password' => Hash::make('password123'),
                'server_id' => 1
            ],
            [
                'staff_username' => 'helper_user',
                'staff_email' => 'helper@example.com',
                'staff_discord' => 'HelperUser#0003',
                'role' => 'helper',
                'status' => 'active',
                'join_date' => '2023-11-20',
                'notes' => 'New helper',
                'password' => Hash::make('password123'),
                'server_id' => 1
            ],
            [
                'staff_username' => 'former_mod',
                'staff_email' => 'former@example.com',
                'staff_discord' => 'FormerMod#0004',
                'role' => 'moderator',
                'status' => 'inactive',
                'join_date' => '2023-05-10',
                'notes' => 'Former moderator - inactive',
                'password' => Hash::make('password123'),
                'server_id' => 1
            ]
        ];

        foreach ($staffMembers as $staffData) {
            Staff::create($staffData);
        }
    }
} 