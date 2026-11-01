{{--

    Vue : liste des produits (blocs 3 et 5 d'Esteve).

    - Trois filtres combinables (méthode GET) : recherche par nom,
      filtre par catégorie et case « stock faible seulement » ;
      les valeurs restent affichées après la recherche.
    - Tableau avec le nom, la catégorie, la quantité, le seuil, le prix
      et les actions modifier / supprimer.
    - Un produit en alerte (quantité qui atteint ou passe sous le seuil)
      porte une étiquette « Stock faible » visible.
    - Deux états vides : « aucun produit » (liste vide) et
      « aucun résultat » (recherche ou filtres sans correspondance).

--}}
@extends('layout')

@section('titre', 'Produits — Ma boutique')

@section('contenu')

    <div class="en-tete-page">
        <div>
            <h1>Produits</h1>
            <p class="description-page">{{ $produits->count() }} produit(s) affiché(s).</p>
        </div>
        @if (Route::has('produits.create'))
            <a href="{{ route('produits.create') }}" class="bouton">+ Ajouter un produit</a>
        @endif
    </div>

    {{-- Formulaire de recherche et de filtres : GET pour rester dans l'URL --}}
    <form action="{{ route('produits.index') }}" method="GET" class="barre-filtres">
        <div class="filtre">
            <label for="recherche">Nom du produit</label>
            <input type="text" name="recherche" id="recherche"
                   value="{{ e($filtres['recherche']) }}" placeholder="Rechercher par nom...">
        </div>

        <div class="filtre">
            <label for="categorie_id">Catégorie</label>
            <select name="categorie_id" id="categorie_id">
                <option value="">Toutes les catégories</option>
                @foreach ($categories as $categorie)
                    <option value="{{ $categorie->id }}"
                        @selected((string) old('categorie_id', (string) $filtres['categorie_id']) === (string) $categorie->id)>
                        {{ $categorie->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filtre filtre-case">
            <label for="faible">Stock faible seulement</label>
            <input type="checkbox" name="faible" id="faible" value="1"
                @checked($filtres['faible'])>
        </div>

        <div class="filtre filtre-boutons">
            <button type="submit" class="bouton">Rechercher</button>
            {{-- Ne réinitialiser le formulaire que si un filtre est actif --}}
            @if ($filtreActif)
                <a href="{{ route('produits.index') }}" class="bouton bouton-editer">Réinitialiser</a>
            @endif
        </div>
    </form>

    <div class="section-carte">
        @if ($produits->isEmpty())
            @if ($filtreActif)
                {{-- Aucun produit ne correspond à la recherche ou aux filtres actifs --}}
                <div class="etat-vide">
                    <h3>Aucun résultat</h3>
                    <p>Aucun produit ne correspond à votre recherche. Essayez d'élargir vos filtres.</p>
                </div>
            @else
                {{-- Liste vide : la boutique ne contient encore aucun produit --}}
                <div class="etat-vide">
                    <h3>Aucun produit pour le moment</h3>
                    <p>Ajoutez votre premier produit pour commencer à suivre votre stock.</p>
                    @if (Route::has('produits.create'))
                        <a href="{{ route('produits.create') }}" class="bouton">+ Ajouter un produit</a>
                    @endif
                </div>
            @endif
        @else
            <div class="tableau-deroulant">
                <table>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Catégorie</th>
                            <th>Quantité</th>
                            <th>Seuil</th>
                            <th>Prix</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produits as $produit)
                            @php
                                // Un produit est en alerte quand la quantité atteint ou passe sous le seuil.
                                $enAlerte = $produit->quantite <= $produit->seuil;
                            @endphp
                            <tr>
                                <td>
                                    {{ $produit->nom }}
                                    @if ($enAlerte)
                                        <span class="badge badge-alerte">Stock faible</span>
                                    @endif
                                </td>
                                <td>{{ $produit->categorie?->nom ?? '—' }}</td>
                                <td class="{{ $enAlerte ? 'texte-alerte' : '' }}">{{ $produit->quantite }}</td>
                                <td>{{ $produit->seuil }}</td>
                                <td>{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</td>
                                <td>
                                    <div class="actions-ligne">
                                        @if (Route::has('produits.edit'))
                                            <a href="{{ route('produits.edit', $produit) }}"
                                               class="bouton bouton-editer bouton-tout-petit">Modifier</a>

                                            {{-- Suppression avec confirmation du navigateur --}}
                                            <form action="{{ route('produits.destroy', $produit) }}" method="POST"
                                                  onsubmit="return confirm('Voulez-vous vraiment supprimer le produit « {{ $produit->nom }} » ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bouton bouton-danger bouton-tout-petit">
                                                    Supprimer
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

@endsection
