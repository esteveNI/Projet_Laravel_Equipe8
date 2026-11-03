<?php

use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ProduitsController;
use App\Http\Controllers\TableauDeBordController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TableauDeBordController::class, 'index'])->name('tableau-de-bord');

Route::resource('produits', ProduitsController::class)
    ->parameters(['produits' => 'produit'])
    ->except(['show']);


Route::resource('categories', CategorieController::class)
    ->parameters(['categories' => 'categorie'])
    ->except(['show']);
