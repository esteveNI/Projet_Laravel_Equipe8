<?php

namespace Database\Seeders;

use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Database\Seeder;

/**
 * Données de démonstration réalistes pour la boutique (bloc 1 d'Esteve).
 *
 * Le sujet demande : 4 ou 5 catégories, une douzaine de produits
 * et 3 ou 4 produits dont la quantité est sous (ou égale à) leur seuil.
 *
 * Résumé des données ci-dessous :
 * - 5 catégories : Boissons, Consommables, Épicerie, Fruits et légumes, Hygiène et beauté ;
 * - 13 produits, dont 4 en alerte de stock faible ;
 * - valeur totale du stock (somme de quantité × prix) : 324 100 FCFA,
 *   à vérifier avec la calculatrice sur le tableau de bord ;
 * - alertes : Sucre en poudre, Spaghetti, Jus d'ananas, Dentifrice.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Remplit les tables catégories et produits.
     */
    public function run(): void
    {
        // ---------- Catégories (5) ----------
        $boissons = Categorie::create(['nom' => 'Boissons']);
        $consommables = Categorie::create(['nom' => 'Consommables']);
        $epicerie = Categorie::create(['nom' => 'Épicerie']);
        $fruitsEtLegumes = Categorie::create(['nom' => 'Fruits et légumes']);
        $hygiene = Categorie::create(['nom' => 'Hygiène et beauté']);

        // ---------- Produits (13, dont 4 sous leur seuil d'alerte) ----------
        // Chaque ligne : [nom, quantite, seuil, prix, catégorie].
        $produits = [
            // Épicerie
            ['nom' => 'Riz parfumé 5 kg', 'quantite' => 24, 'seuil' => 10, 'prix' => 4500, 'categorie' => $epicerie],
            ['nom' => 'Sucre en poudre 1 kg', 'quantite' => 3, 'seuil' => 5, 'prix' => 550, 'categorie' => $epicerie],
            ['nom' => 'Huile végétale 1 L', 'quantite' => 15, 'seuil' => 6, 'prix' => 1350, 'categorie' => $epicerie],
            ['nom' => 'Spaghetti 200 g', 'quantite' => 2, 'seuil' => 8, 'prix' => 350, 'categorie' => $epicerie],
            ['nom' => 'Concentré de tomate', 'quantite' => 12, 'seuil' => 5, 'prix' => 900, 'categorie' => $epicerie],

            // Boissons
            ['nom' => 'Eau minérale pack 6 × 50 cl', 'quantite' => 40, 'seuil' => 12, 'prix' => 1500, 'categorie' => $boissons],
            ['nom' => "Jus d'ananas 1 L", 'quantite' => 4, 'seuil' => 6, 'prix' => 1750, 'categorie' => $boissons],

            // Hygiène et beauté
            ['nom' => 'Savon liquide 500 ml', 'quantite' => 18, 'seuil' => 8, 'prix' => 850, 'categorie' => $hygiene],
            ['nom' => 'Dentifrice 100 g', 'quantite' => 1, 'seuil' => 5, 'prix' => 750, 'categorie' => $hygiene],
            ['nom' => 'Shampooing 250 ml', 'quantite' => 9, 'seuil' => 4, 'prix' => 1350, 'categorie' => $hygiene],

            // Fruits et légumes
            ['nom' => 'Oranges (sac)', 'quantite' => 25, 'seuil' => 10, 'prix' => 2500, 'categorie' => $fruitsEtLegumes],
            ['nom' => 'Tomates (panier)', 'quantite' => 10, 'seuil' => 4, 'prix' => 1000, 'categorie' => $fruitsEtLegumes],

            // Consommables
            ['nom' => 'Papier hygiénique (2 rouleaux)', 'quantite' => 30, 'seuil' => 10, 'prix' => 500, 'categorie' => $consommables],
        ];

        foreach ($produits as $description) {
            Produit::create([
                'nom' => $description['nom'],
                'quantite' => $description['quantite'],
                'seuil' => $description['seuil'],
                'prix' => $description['prix'],
                'categorie_id' => $description['categorie']->id,
            ]);
        }
    }
}
