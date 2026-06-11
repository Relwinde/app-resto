<?php

namespace Database\Seeders;

use App\Models\Caisse;
use Illuminate\Database\Seeder;

class CaisseSeeder extends Seeder
{
    public function run(): void
    {
        Caisse::firstOrCreate(
            ['type' => 'especes'],
            ['nom' => 'Caisse Espèces', 'solde_actuel' => 0, 'statut' => 'active']
        );

        Caisse::firstOrCreate(
            ['type' => 'mobile_money'],
            ['nom' => 'Caisse Mobile Money', 'solde_actuel' => 0, 'statut' => 'active']
        );
    }
}
