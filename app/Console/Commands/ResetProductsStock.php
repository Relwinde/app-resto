<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetProductsStock extends Command
{
    protected $signature = 'stock:reset {--dry-run : Afficher les corrections sans les appliquer} {--force : Exécuter sans confirmation interactive}';

    protected $description = "Réinitialise à 0 le stock calculé de tous les produits, via une ligne de correction (n'efface aucun historique d'approvisionnement/vente).";

    public function handle(): int
    {
        $produits = Product::all()->filter(fn (Product $p) => (float) $p->stock_actuel !== 0.0);

        if ($produits->isEmpty()) {
            $this->info('Tous les produits sont déjà à 0. Rien à faire.');
            return self::SUCCESS;
        }

        $this->table(
            ['Produit', 'Stock actuel', 'Correction à appliquer'],
            $produits->map(fn (Product $p) => [$p->name, $p->stock_actuel, -$p->stock_actuel])
        );

        if ($this->option('dry-run')) {
            $this->comment('Mode --dry-run : aucune modification effectuée.');
            return self::SUCCESS;
        }

        if (! $this->option('force') && ! $this->confirm('Confirmez-vous l\'application de ces corrections ?')) {
            $this->comment('Annulé.');
            return self::SUCCESS;
        }

        DB::transaction(function () use ($produits) {
            foreach ($produits as $produit) {
                $stock = (float) $produit->stock_actuel;
                StockMovement::create([
                    'product_id' => $produit->id,
                    'quantite'   => -$stock,
                    'note'       => 'Correction stock — réinitialisation à 0 (' . now()->format('d/m/Y') . ')',
                ]);
            }
        });

        $this->info(count($produits) . ' produit(s) corrigé(s).');
        return self::SUCCESS;
    }
}
