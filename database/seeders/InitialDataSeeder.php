<?php declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les entreprises partenaires
        Company::create([
            'name' => 'Compagnie Malienne d\'Assurance (CMA)',
            'address' => 'Bamako, Mali',
            'phone' => '+223 20 22 22 22',
            'email' => 'contact@cma.ml',
        ]);

        Company::create([
            'name' => 'Électricité du Mali (EDM)',
            'address' => 'Bamako, Mali',
            'phone' => '+223 20 23 23 23',
            'email' => 'info@edm.ml',
        ]);

        Company::create([
            'name' => 'Somaleau',
            'address' => 'Bamako, Mali',
            'phone' => '+223 20 24 24 24',
            'email' => 'contact@somaleau.ml',
        ]);

        // Créer un utilisateur superviseur
        User::create([
            'name' => 'Admin BIFI',
            'email' => 'admin@bifi.com',
            'password' => Hash::make('password'),
            'role' => 'supervisor',
        ]);

        // Créer un agent
        User::create([
            'name' => 'Agent BIFI',
            'email' => 'agent@bifi.com',
            'password' => Hash::make('password'),
            'role' => 'agent',
        ]);

        // Créer un client de test
        User::create([
            'name' => 'Client Test',
            'email' => 'client@test.com',
            'password' => Hash::make('password'),
            'role' => 'client',
        ]);
    }
}
