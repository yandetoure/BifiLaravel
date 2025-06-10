<?php declare(strict_types=1); 

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            [
                'name' => 'CMA EDL (Électricité du Laos)',
                'address' => 'Vientiane, Laos',
                'phone' => '+856 21 123 456',
                'email' => 'contact@cma-edl.la',
                'logo' => null,
            ],
            [
                'name' => 'CMA Télécom Laos',
                'address' => 'Luang Prabang, Laos',
                'phone' => '+856 21 789 123',
                'email' => 'service@cma-telecom.la',
                'logo' => null,
            ],
            [
                'name' => 'CMA Distribution d\'Eau',
                'address' => 'Pakse, Laos',
                'phone' => '+856 31 456 789',
                'email' => 'eau@cma-distribution.la',
                'logo' => null,
            ],
            [
                'name' => 'CMA Services Urbains',
                'address' => 'Savannakhet, Laos',
                'phone' => '+856 41 654 321',
                'email' => 'urbain@cma-services.la',
                'logo' => null,
            ],
            [
                'name' => 'CMA Gaz & Énergie',
                'address' => 'Champasak, Laos',
                'phone' => '+856 31 987 654',
                'email' => 'gaz@cma-energie.la',
                'logo' => null,
            ],
            [
                'name' => 'CMA Transport Public',
                'address' => 'Vientiane, Laos',
                'phone' => '+856 21 555 777',
                'email' => 'transport@cma-public.la',
                'logo' => null,
            ],
        ];

        foreach ($companies as $company) {
            Company::updateOrCreate(
                ['name' => $company['name']],
                $company
            );
        }
    }
} 