<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Produit;
use App\Models\Categorie;

class ProduitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $boissons = Categorie::where('nom', 'Boissons')->first();
        $alimentation = Categorie::where('nom', 'Alimentation')->first();
        $hygiene = Categorie::where('nom', 'Hygiène')->first();

        Produit::create([
            'nom' => 'Coca-Cola 1,5L',
            'quantite' => 20,
            'prix' => 1000,
            'seuil'=>8,
            'categorie_id' => $boissons->id,
        ]);

        Produit::create([
            'nom' => 'Riz 5kg',
            'quantite' => 15,
            'prix' => 4500,
            'seuil'=>5,
            'categorie_id' => $alimentation->id,
        ]);

        Produit::create([
            'nom' => 'Savon Palmida',
            'quantite' => 3,
            'prix' => 500,
            'seuil'=>10,
            'categorie_id' => $hygiene->id,
        ]);
    }
}
