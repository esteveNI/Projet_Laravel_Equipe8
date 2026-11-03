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
    <form action="{{ route('produits.index') }}" method="GET" class="barre-filtres" id="formulaire-filtres">
        <div class="filtre">
            <label for="recherche">Nom du produit</label>
            <input type="text" name="recherche" id="recherche" autocomplete="off"
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
            <label for="faible">
                <input type="checkbox" name="faible" id="faible" value="1" @checked($filtres['faible'])>
                Stock faible seulement
            </label>
        </div>

        <div class="filtre filtre-boutons">
            {{-- Sans JavaScript, le bouton reste disponible --}}
            <noscript><button type="submit" class="bouton">Rechercher</button></noscript>
            {{-- Ne proposer la réinitialisation que si un filtre est actif --}}
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

                                            {{-- Suppression : le bouton ouvre la boîte de confirmation du gabarit --}}
                                            <button type="button" class="bouton bouton-danger bouton-tout-petit"
                                                    data-suppression
                                                    data-action="{{ route('produits.destroy', $produit) }}"
                                                    data-titre="Supprimer ce produit ?"
                                                    data-message="Le produit « {{ $produit->nom }} » sera définitivement supprimé. Cette action est irréversible."
                                                    data-bloque="0">
                                                Supprimer
                                            </button>
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

@push('scripts')
<script>
    // Recherche et filtres automatiques
    (function () {
        const formulaire = document.getElementById('formulaire-filtres');
        const champRecherche = document.getElementById('recherche');
        let minuterie = null;

        // Liste déroulante et case à cocher
        formulaire.querySelectorAll('select, input[type="checkbox"]').forEach(function (champ) {
            champ.addEventListener('change', function () { formulaire.submit(); });
        });

        // Champ texte
        champRecherche.addEventListener('input', function () {
            clearTimeout(minuterie);
            minuterie = setTimeout(function () {
                sessionStorage.setItem('focusRecherche', '1');
                formulaire.submit();
            }, 400);
        });

        // Après le rechargement de la page,curseur remis
        if (sessionStorage.getItem('focusRecherche') === '1') {
            sessionStorage.removeItem('focusRecherche');
            champRecherche.focus();
            champRecherche.setSelectionRange(champRecherche.value.length, champRecherche.value.length);
        }
    })();
</script>
@endpush
