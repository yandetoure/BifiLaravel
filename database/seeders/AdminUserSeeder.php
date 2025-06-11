<?php declare(strict_types=1); 

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer l'utilisateur admin par défaut
        User::updateOrCreate(
            ['email' => 'admin@bifi.com'],
            [
                'name' => 'Ndeye Yandé Touré',
                'email' => 'admin@bifi.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Créer un superviseur de test
        User::updateOrCreate(
            ['email' => 'superviseur@bifi.com'],
            [
                'name' => 'Mame Diarra Touré',
                'email' => 'superviseur@bifi.com',
                'password' => Hash::make('supervisor123'),
                'role' => 'supervisor',
                'email_verified_at' => now(),
            ]
        );

        // Créer un agent de test
        User::updateOrCreate(
            ['email' => 'agent@bifi.com'],
            [
                'name' => 'Astou 1',
                'email' => 'agent@bifi.com',
                'password' => Hash::make('agent123'),
                'role' => 'agent',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Utilisateurs de test créés avec succès !');
        $this->command->info('Admin: admin@bifi.com / admin123');
        $this->command->info('Superviseur: superviseur@bifi.com / supervisor123');
        $this->command->info('Agent: agent@bifi.com / agent123');
    }
}
