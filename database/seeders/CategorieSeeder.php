<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categorie;

class CategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Categorie::create(['nom' => 'Boissons']);
        Categorie::create(['nom' => 'Alimentation']);
        Categorie::create(['nom' => 'Hygiène']);
        Categorie::create(['nom' => 'Entretien']);
        Categorie::create(['nom' => 'Divers']);
    }
}
