<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [];
        $users[] = User::create([
            'name' => 'Admin',
            'email' => 'admin@behin.com',
            'password' => Hash::make('Aa@123456'),
            'type' => 'admin',
        ]);
        $users[] = User::create([
            'name' => 'Agent',
            'email' => 'agent@behin.com',
            'password' => Hash::make('Aa@123456'),
            'type' => 'agent',
        ]);
        $users[] = User::create([
            'name' => 'User',
            'email' => 'user@behin.com',
            'password' => Hash::make('Aa@123456'),
        ]);
        foreach ($users as $user) {
            Profile::create([
                'user_id' => $user->id,
            ]);
        }
    }
}
