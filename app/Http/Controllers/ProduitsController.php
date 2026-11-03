<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class ProduitsController extends Controller
{

    public function index(Request $requete): View
    {

        $recherche = trim((string) $requete->query('recherche', ''));
        $categorieId = $requete->query('categorie_id');
        $faibleSeulement = $requete->boolean('faible');

        $produits = Produit::query()
            ->with('categorie')
            ->orderBy('nom')
            ->when($recherche !== '', function ($construction) use ($recherche) {

                $construction->where('nom', 'like', '%'.$recherche.'%');
            })
            ->when(! empty($categorieId), function ($construction) use ($categorieId) {

                $construction->where('categorie_id', (int) $categorieId);
            })
            ->when($faibleSeulement, function ($construction) {

                $construction->whereColumn('quantite', '<=', 'seuil');
            })
            ->get();

        return view('produits.index', [
            'produits' => $produits,
            'categories' => Categorie::query()->orderBy('nom')->get(),
            'filtres' => [
                'recherche' => $recherche,
                'categorie_id' => $categorieId,
                'faible' => $faibleSeulement,
            ],

            'filtreActif' => $recherche !== '' || ! empty($categorieId) || $faibleSeulement,
        ]);
    }


    public function create(): View
    {
        return view('produits.formulaire', [
            'produit' => new Produit,
            'categories' => Categorie::query()->orderBy('nom')->get(),
        ]);
    }

    public function store(Request $requete): RedirectResponse
    {
        $produit = Produit::create($requete->validate(
            $this->regles(),
            $this->messages()
        ));

        return redirect()
            ->route('produits.index')
            ->with('succes', "Le produit « {$produit->nom} » a été ajouté avec succès.");
    }


    public function edit(Produit $produit): View
    {

        return view('produits.formulaire', [
            'produit' => $produit,
            'categories' => Categorie::query()->orderBy('nom')->get(),
        ]);
    }


    public function update(Request $requete, Produit $produit): RedirectResponse
    {
        $produit->update($requete->validate(
            $this->regles(),
            $this->messages()
        ));

        return redirect()
            ->route('produits.index')
            ->with('succes', "Le produit « {$produit->nom} » a été modifié avec succès.");
    }


    public function destroy(Produit $produit): RedirectResponse
    {
        $produit->delete();

        return redirect()
            ->route('produits.index')
            ->with('succes', "Le produit « {$produit->nom} » a été supprimé avec succès.");
    }


    private function regles(): array
    {
        return [
            'nom' => ['required', 'string', 'max:100'],
            'categorie_id' => ['required', 'integer', Rule::exists('categories', 'id')],
            'quantite' => ['required', 'integer', 'min:0'],
            'prix' => ['required', 'integer', 'min:0'],
            'seuil' => ['required', 'integer', 'min:0'],
        ];
    }

    
    private function messages(): array
    {
        return [
            'nom.required' => 'Le nom du produit est obligatoire.',
            'nom.string' => 'Le nom du produit doit être un texte.',
            'nom.max' => 'Le nom du produit ne doit pas dépasser :max caractères.',
            'categorie_id.required' => 'La catégorie est obligatoire : choisissez-en une dans la liste.',
            'categorie_id.exists' => 'La catégorie choisie n\'existe pas.',
            'quantite.required' => 'La quantité est obligatoire.',
            'quantite.integer' => 'La quantité doit être un nombre entier (pas de décimales).',
            'quantite.min' => 'La quantité ne peut pas être négative.',
            'prix.required' => 'Le prix est obligatoire.',
            'prix.integer' => 'Le prix doit être un nombre entier de FCFA.',
            'prix.min' => 'Le prix ne peut pas être négatif.',
            'seuil.required' => 'Le seuil d\'alerte est obligatoire.',
            'seuil.integer' => 'Le seuil d\'alerte doit être un nombre entier.',
            'seuil.min' => 'Le seuil d\'alerte ne peut pas être négatif.',
        ];
    }
}
