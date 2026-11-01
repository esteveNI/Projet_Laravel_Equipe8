<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Contracts\View\View;

/**
 * Contrôleur du tableau de bord (bloc 6 de Vital).
 *
 * Il s'agit de la page d'accueil de l'application. Elle affiche :
 * - le nombre de produits de la boutique ;
 * - la valeur totale du stock (somme de quantité × prix de chaque produit) ;
 * - le nombre d'alertes, c'est-à-dire les produits dont la quantité
 *   atteint ou passe sous leur seuil d'alerte ;
 * - la liste de ces produits en alerte, avec un lien vers leur modification.
 */
class TableauDeBordController extends Controller
{
    /**
     * Affiche le tableau de bord avec les trois indicateurs et la liste des alertes.
     */
    public function index(): View
    {
        $nombreDeProduits = Produit::query()->count();
        $nombreDeCategories = Categorie::query()->count();

        // Un produit est en alerte quand sa quantité atteint ou passe sous son seuil.
        $produitsEnAlerte = Produit::query()
            ->with('categorie')
            ->whereColumn('quantite', '<=', 'seuil')
            ->orderBy('nom')
            ->get();

        // Valeur totale du stock : somme de quantité × prix, calculée par MySQL.
        // COALESCE renvoie 0 quand la table produits est vide (pas de NULL affiché).
        $valeurTotaleStock = (int) Produit::query()
            ->selectRaw('COALESCE(SUM(quantite * prix), 0) AS valeur_totale')
            ->value('valeur_totale');

        return view('tableau-de-bord', [
            'nombreDeProduits' => $nombreDeProduits,
            'nombreDeCategories' => $nombreDeCategories,
            'produitsEnAlerte' => $produitsEnAlerte,
            'nombreDAlertes' => $produitsEnAlerte->count(),
            'valeurTotaleStock' => $valeurTotaleStock,
        ]);
    }
}
