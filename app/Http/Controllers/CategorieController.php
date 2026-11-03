<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategorieController extends Controller
{

    public function index(): View
    {
        $categories = Categorie::query()
            ->withCount('produits')
            ->orderBy('nom')
            ->get();

        return view('categories.index', [
            'categories' => $categories,
        ]);
    }


    public function create(): View
    {
        return view('categories.formulaire', [
            'categorie' => new Categorie,
        ]);
    }


    public function store(Request $requete): RedirectResponse
    {
        $donneesValides = $requete->validate(
            $this->regles(),
            $this->messages()
        );

        $categorie = Categorie::create($donneesValides);

        return redirect()
            ->route('categories.index')
            ->with('succes', "La catégorie « {$categorie->nom} » a été ajoutée avec succès.");
    }


    public function edit(Categorie $categorie): View
    {

        return view('categories.formulaire', [
            'categorie' => $categorie,
        ]);
    }


    public function update(Request $requete, Categorie $categorie): RedirectResponse
    {
        $donneesValides = $requete->validate(
            $this->regles($categorie),
            $this->messages()
        );

        $categorie->update($donneesValides);

        return redirect()
            ->route('categories.index')
            ->with('succes', "La catégorie « {$donneesValides['nom']} » a été modifiée avec succès.");
    }


    public function destroy(Categorie $categorie): RedirectResponse
    {
        $nombreDeProduits = $categorie->produits()->count();

        if ($nombreDeProduits > 0) {

            return redirect()
                ->route('categories.index')
                ->with('erreur', "Suppression impossible : la catégorie « {$categorie->nom} » contient encore {$nombreDeProduits} produit(s).");
        }

        $categorie->delete();

        return redirect()
            ->route('categories.index')
            ->with('succes', "La catégorie « {$categorie->nom} » a été supprimée avec succès.");
    }


    private function regles(?Categorie $categorie = null): array
    {
        $regleUnique = Rule::unique('categories', 'nom');

        if ($categorie !== null) {
            $regleUnique->ignore($categorie->getKey());
        }

        return [
            'nom' => ['required', 'string', 'max:100', $regleUnique],
        ];
    }


    private function messages(): array
    {
        return [
            'nom.required' => 'Le nom de la catégorie est obligatoire.',
            'nom.string' => 'Le nom de la catégorie doit être un texte.',
            'nom.max' => 'Le nom de la catégorie ne doit pas dépasser :max caractères.',
            'nom.unique' => 'Cette catégorie existe déjà : vous avez enregistré ce nom.',
        ];
    }
}
