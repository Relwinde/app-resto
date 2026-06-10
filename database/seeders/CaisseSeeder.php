<?php

namespace Database\Seeders;

use App\Models\Caisse;
use Illuminate\Database\Seeder;

class CaisseSeeder extends Seeder
{
    public function run(): void
    {
        Caisse::firstOrCreate(
            ['nom' => 'Caisse Principale'],
            ['solde_actuel' => 0, 'statut' => 'active']
        );
    }
}
