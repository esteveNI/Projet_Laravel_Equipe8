
@extends('layout')

@section('titre', ($produit->exists ? 'Modifier un produit' : 'Ajouter un produit') . ' — Ma boutique')

@section('contenu')

    <a href="{{ route('produits.index') }}" class="lien-retour">&larr; Retour aux produits</a>

    <div class="section-carte bande-conteneur">
        <h2>{{ $produit->exists ? 'Modifier le produit' : 'Ajouter un produit' }}</h2>

        <div class="contenu-formulaire">
            <form action="{{ $produit->exists
                    ? route('produits.update', $produit)
                    : route('produits.store') }}"
                  method="POST">

                @if ($produit->exists)
                    @method('PUT')
                @endif

                @csrf

                {{-- Champ nom : obligatoire --}}
                <div class="champ">
                    <label for="nom" class="asterisque">Nom du produit</label>
                    <input type="text" name="nom" id="nom" value="{{ old('nom', $produit->nom) }}"
                           placeholder="Exemple : Riz parfumé 5 kg"
                           class="@error('nom') saisie-invalide @enderror">

                    @error('nom')
                        <p class="erreur-champ">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Champ catégorie : liste déroulante des catégories existantes --}}
                <div class="champ">
                    <label for="categorie_id" class="asterisque">Catégorie</label>
                    <select name="categorie_id" id="categorie_id"
                            class="@error('categorie_id') saisie-invalide @enderror">
                        <option value="">— Choisir une catégorie —</option>
                        @foreach ($categories as $categorie)
                            <option value="{{ $categorie->id }}"
                                @selected((string) old('categorie_id', $produit->categorie_id) === (string) $categorie->id)>
                                {{ $categorie->nom }}
                            </option>
                        @endforeach
                    </select>

                    @error('categorie_id')
                        <p class="erreur-champ">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Champ quantité : entier non négatif --}}
                <div class="champ">
                    <label for="quantite" class="asterisque">Quantité en stock</label>
                    <input type="number" name="quantite" id="quantite" min="0" step="1"
                           value="{{ old('quantite', $produit->quantite) }}"
                           class="@error('quantite') saisie-invalide @enderror">

                    @error('quantite')
                        <p class="erreur-champ">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Champ seuil d'alerte : entier non négatif, propre au produit --}}
                <div class="champ">
                    <label for="seuil" class="asterisque">Seuil d'alerte</label>
                    <input type="number" name="seuil" id="seuil" min="0" step="1"
                           value="{{ old('seuil', $produit->seuil) }}"
                           class="@error('seuil') saisie-invalide @enderror">
                    <p class="aide-champ">Le produit est signalé « stock faible » quand sa quantité
                        atteint ou passe sous ce seuil.</p>

                    @error('seuil')
                        <p class="erreur-champ">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Champ prix : entier non négatif, en FCFA --}}
                <div class="champ">
                    <label for="prix" class="asterisque">Prix unitaire (FCFA)</label>
                    <input type="number" name="prix" id="prix" min="0" step="1"
                           value="{{ old('prix', $produit->prix) }}"
                           class="@error('prix') saisie-invalide @enderror">

                    @error('prix')
                        <p class="erreur-champ">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bouton-groupe">
                    <button type="submit" class="bouton">
                        {{ $produit->exists ? 'Enregistrer les modifications' : 'Ajouter le produit' }}
                    </button>
                    <a href="{{ route('produits.index') }}"
                       class="bouton bouton-editer">Annuler</a>
                </div>
            </form>
        </div>
    </div>

@endsection
