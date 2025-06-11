<?php declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            // [
            //     'name' => 'Administrateur Bifi',
            //     'email' => 'admin@bifi.com',
            //     'password' => Hash::make('password'),
            //     'role' => 'supervisor',
            // ],
            // [
            //     'name' => 'Agent CMA Vientiane',
            //     'email' => 'agent1@bifi.com',
            //     'password' => Hash::make('password'),
            //     'role' => 'agent',
            // ],
            [
                'name' => 'Astou 2',
                'email' => 'agent2@bifi.com',
                'password' => Hash::make('password'),
                'role' => 'agent',
            ],
            // [
            //     'name' => 'Superviseur CMA',
            //     'email' => 'superviseur@bifi.com',
            //     'password' => Hash::make('password'),
            //     'role' => 'supervisor',
            // ],
            // [
            //     'name' => 'Jean Dupont',
            //     'email' => 'client1@example.com',
            //     'password' => Hash::make('password'),
            //     'role' => 'client',
            // ],
            // [
            //     'name' => 'Marie Martin',
            //     'email' => 'client2@example.com',
            //     'password' => Hash::make('password'),
            //     'role' => 'client',
            // ],
            // [
            //     'name' => 'Pierre Durand',
            //     'email' => 'client3@example.com',
            //     'password' => Hash::make('password'),
            //     'role' => 'client',
            // ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
} 