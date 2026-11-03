<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Contracts\View\View;


class TableauDeBordController extends Controller
{

    public function index(): View
    {
        $nombreDeProduits = Produit::query()->count();
        $nombreDeCategories = Categorie::query()->count();


        $produitsEnAlerte = Produit::query()
            ->with('categorie')
            ->whereColumn('quantite', '<=', 'seuil')
            ->orderBy('nom')
            ->get();

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
