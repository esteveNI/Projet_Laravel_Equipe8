<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Contrôleur de la gestion des produits (blocs 3 et 5 d'Esteve).
 *
 * Bloc 3 : liste, ajout, modification et suppression d'un produit,
 * avec validation des champs et messages de succès.
 * Bloc 5 : recherche par nom, filtre par catégorie et filtre
 * « stock faible seulement », combinables entre eux.
 */
class ProduitsController extends Controller
{
    /**
     * Affiche la liste des produits, avec recherche par nom, filtre par
     * catégorie et filtre « stock faible seulement », combinables.
     */
    public function index(Request $requete): View
    {
        // Valeurs des filtres, réaffichées après la recherche dans la vue.
        $recherche = trim((string) $requete->query('recherche', ''));
        $categorieId = $requete->query('categorie_id');
        $faibleSeulement = $requete->boolean('faible');

        $produits = Produit::query()
            ->with('categorie')
            ->orderBy('nom')
            ->when($recherche !== '', function ($construction) use ($recherche) {
                // Recherche par nom : insensible à la casse grâce à LIKE (MySQL).
                $construction->where('nom', 'like', '%'.$recherche.'%');
            })
            ->when(! empty($categorieId), function ($construction) use ($categorieId) {
                // Filtre par catégorie : un id numérique renvoyé par la liste déroulante.
                $construction->where('categorie_id', (int) $categorieId);
            })
            ->when($faibleSeulement, function ($construction) {
                // Filtre « stock faible seulement » : même règle que sur le tableau de bord.
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
            // Un filtre est-il actif ? Sert à afficher « Aucun résultat » ou « liste vide ».
            'filtreActif' => $recherche !== '' || ! empty($categorieId) || $faibleSeulement,
        ]);
    }

    /**
     * Affiche le formulaire d'ajout d'un produit.
     */
    public function create(): View
    {
        return view('produits.formulaire', [
            'produit' => new Produit,
            'categories' => Categorie::query()->orderBy('nom')->get(),
        ]);
    }

    /**
     * Enregistre un nouveau produit après validation.
     */
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

    /**
     * Affiche le formulaire de modification, pré-rempli avec le produit actuel.
     */
    public function edit(Produit $produit): View
    {
        // Paramètre nommé 'produit' : correspond à ->parameters([...]) mis dans routes/web.php.
        return view('produits.formulaire', [
            'produit' => $produit,
            'categories' => Categorie::query()->orderBy('nom')->get(),
        ]);
    }

    /**
     * Met à jour un produit après validation.
     */
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

    /**
     * Supprime un produit (confirmation demandée côté vue).
     */
    public function destroy(Produit $produit): RedirectResponse
    {
        $produit->delete();

        return redirect()
            ->route('produits.index')
            ->with('succes', "Le produit « {$produit->nom} » a été supprimé avec succès.");
    }

    /**
     * Règles de validation d'un produit, en ajout comme en modification.
     *
     * - nom : obligatoire, texte de 100 caractères maximum ;
     * - categorie_id : le produit appartient à une catégorie existante ;
     * - quantite : entier non négatif (une quantité décimale est refusée) ;
     * - prix : entier non négatif (exprimé en FCFA) ;
     * - seuil : entier non négatif, seuil d'alerte propre au produit.
     */
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

    /**
     * Messages d'erreur en français, affichés sous chaque champ concerné.
     */
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
