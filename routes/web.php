<?php

use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ProduitsController;
use App\Http\Controllers\TableauDeBordController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes de l'application « Suivi de stock — Équipe 8 » (MVC)
|--------------------------------------------------------------------------
|
| Chemin complet d'une requête pour l'ajout d'un produit (à expliquer en
| démo) : URL /produits/create → route nommée produits.create (ici) →
| ProduitsController::create → vue produits/formulaire.blade.php →
| soumission du formulaire → ProduitsController::store → modèle Produit
| → enregistrement en base (MySQL) → redirection avec message de succès.
|
| Le tableau de bord est la page d'accueil de l'application.
| La suppression d'une catégorie remplie est refusée côté contrôleurs.
*/

// Bloc 6 de Vital : tableau de bord avec les indicateurs et les alertes.
Route::get('/', [TableauDeBordController::class, 'index'])->name('tableau-de-bord');

// Bloc 3 et 5 d'Esteve : CRUD des produits + recherche et filtres.
// Le paramètre de route est nommé 'produit' pour des URLs lisibles,
// par exemple /produits/5/edit.
Route::resource('produits', ProduitsController::class)
    ->parameters(['produits' => 'produit'])
    ->except(['show']);

// Bloc 4 de Vital : CRUD des catégories avec la règle de suppression.
Route::resource('categories', CategorieController::class)
    ->parameters(['categories' => 'categorie'])
    ->except(['show']);
