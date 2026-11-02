<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Contrôleur de la gestion des catégories (bloc 4 de Vital).
 *
 * Il gère la liste, l'ajout, la modification et la suppression des
 * catégories, avec deux règles du sujet :
 * - le nom d'une catégorie est obligatoire et unique ;
 * - la suppression est refusée tant que la catégorie contient
 *   des produits, avec un message d'erreur clair.
 */
class CategorieController extends Controller
{
    /**
     * Affiche la liste des catégories avec le nombre de produits de chacune.
     */
    public function index(): View
    {
        // withCount ajoute la colonne produits_count : nombre de produits par catégorie.
        $categories = Categorie::query()
            ->withCount('produits')
            ->orderBy('nom')
            ->get();

        return view('categories.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Affiche le formulaire d'ajout d'une catégorie.
     */
    public function create(): View
    {
        return view('categories.formulaire', [
            'categorie' => new Categorie,
        ]);
    }

    /**
     * Enregistre une nouvelle catégorie après validation du nom.
     */
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

    /**
     * Affiche le formulaire de modification, pré-rempli avec le nom actuel.
     */
    public function edit(Categorie $categorie): View
    {
        // Paramètre nommé 'categorie' : correspond à ->parameters([...]) mis dans routes/web.php.
        return view('categories.formulaire', [
            'categorie' => $categorie,
        ]);
    }

    /**
     * Met à jour le nom de la catégorie après validation du nouveau nom.
     */
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

    /**
     * Supprime une catégorie, sauf si elle contient des produits.
     *
     * Règle de gestion du sujet : la suppression d'une catégorie est
     * interdite si elle contient des produits. Dans ce cas, on revient
     * sur la liste avec un message d'erreur clair (et non une erreur brute).
     */
    public function destroy(Categorie $categorie): RedirectResponse
    {
        $nombreDeProduits = $categorie->produits()->count();

        if ($nombreDeProduits > 0) {
            // La garde avant l'erreur SQL de la foreign key : le message reste compréhensible.
            return redirect()
                ->route('categories.index')
                ->with('erreur', "Suppression impossible : la catégorie « {$categorie->nom} » contient encore {$nombreDeProduits} produit(s).");
        }

        $categorie->delete();

        return redirect()
            ->route('categories.index')
            ->with('succes', "La catégorie « {$categorie->nom} » a été supprimée avec succès.");
    }

    /**
     * Règles de validation du champ « nom ».
     *
     * En modification, la règle d'unicité ignore la catégorie en cours :
     * un renommage qui garde le même nom ne doit pas lever d'erreur.
     */
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

    /**
     * Messages d'erreur en français, affichés sous le champ concerné.
     */
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
