{{--

    Vue : tableau de bord (bloc 6 de Vital).

    Trois indicateurs (nombre de produits, valeur totale du stock, nombre
    d'alertes) puis la liste des produits en alerte avec un lien vers
    leur modification. Chaque chiffre est calculé dans
    app/Http/Controllers/TableauDeBordController.php.

--}}

@extends('layout')

@section('titre', 'Tableau de bord — Ma boutique')

@section('contenu')

    <div class="en-tete-page">
        <div>
            <h1>Tableau de bord</h1>
            <p class="description-page">
                Vue d'ensemble du stock de la boutique : {{ $nombreDeProduits }} produit(s)
                répartis dans {{ $nombreDeCategories }} catégorie(s).
            </p>
        </div>
        @if (Route::has('produits.create'))
            <a href="{{ route('produits.create') }}" class="bouton">+ Ajouter un produit</a>
        @endif
    </div>

    {{-- Les trois indicateurs du tableau de bord --}}
    <div class="cartes-indicateurs">
        <div class="carte">
            <span class="carte-label">Produits enregistrés</span>
            <span class="carte-valeur">{{ $nombreDeProduits }}</span>
        </div>
        <div class="carte">
            <span class="carte-label">Valeur totale du stock</span>
            <span class="carte-valeur">{{ number_format($valeurTotaleStock, 0, ',', ' ') }} FCFA</span>
        </div>
        <div class="carte alerte">
            <span class="carte-label">Alertes de stock faible</span>
            <span class="carte-valeur">{{ $nombreDAlertes }}</span>
        </div>
    </div>

    {{-- Produits en alerte : quantité atteint ou passe sous le seuil --}}
    @if ($nombreDeProduits === 0)
        <div class="section-carte">
            <div class="etat-vide">
                <h3>Aucun produit pour le moment</h3>
                <p>Commencez par enregistrer votre premier produit pour suivre votre stock.</p>
                @if (Route::has('produits.create'))
                    <a href="{{ route('produits.create') }}" class="bouton">+ Ajouter un produit</a>
                @endif
            </div>
        </div>
    @else
        <div class="section-carte">
            <h2>Produits en alerte ({{ $nombreDAlertes }})</h2>

            @if ($nombreDAlertes === 0)
                <div class="etat-vide">
                    <h3>Aucune alerte</h3>
                    <p>Tous vos produits sont au-dessus de leur seuil d'alerte.</p>
                </div>
            @else
                <div class="tableau-deroulant">
                    <table>
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Catégorie</th>
                                <th>Quantité / seuil</th>
                                <th>Prix</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($produitsEnAlerte as $produit)
                                <tr>
                                    <td>
                                        {{ $produit->nom }}
                                        <span class="badge badge-alerte">Stock faible</span>
                                    </td>
                                    <td>{{ $produit->categorie?->nom ?? '—' }}</td>
                                    <td class="texte-alerte">{{ $produit->quantite }} / {{ $produit->seuil }}</td>
                                    <td>{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</td>
                                    <td>
                                        @if (Route::has('produits.edit'))
                                            <a href="{{ route('produits.edit', $produit) }}"
                                               class="bouton bouton-editer bouton-tout-petit">
                                                Modifier
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endif

@endsection
