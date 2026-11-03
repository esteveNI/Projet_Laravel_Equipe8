
@extends('layout')

@section('titre', ($categorie->exists ? 'Modifier une catégorie' : 'Ajouter une catégorie') . ' — Ma boutique')

@section('contenu')

    <a href="{{ route('categories.index') }}" class="lien-retour">&larr; Retour aux catégories</a>

    <div class="section-carte bande-conteneur">
        <h2>{{ $categorie->exists ? 'Modifier la catégorie' : 'Ajouter une catégorie' }}</h2>

        {{-- Bloc du formulaire : marge intérieure pour aérer le contenu --}}
        <div class="contenu-formulaire">
            <form action="{{ $categorie->exists
                    ? route('categories.update', $categorie)
                    : route('categories.store') }}"
                  method="POST">

                @if ($categorie->exists)
                    @method('PUT')
                @endif

                @csrf

                <div class="champ">
                    <label for="nom" class="asterisque">Nom de la catégorie</label>
                    <input type="text" name="nom" id="nom" value="{{ old('nom', $categorie->nom) }}"
                           placeholder="Exemples : Épicerie, Boissons, Hygiène"
                           class="@error('nom') saisie-invalide @enderror" required>

                    {{-- Erreur de validation affichée sous le champ concerné --}}
                    @error('nom')
                        <p class="erreur-champ">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bouton-groupe">
                    <button type="submit" class="bouton">
                        {{ $categorie->exists ? 'Enregistrer les modifications' : 'Ajouter la catégorie' }}
                    </button>
                    <a href="{{ route('categories.index') }}"
                       class="bouton bouton-editer">Annuler</a>
                </div>
            </form>
        </div>
    </div>

@endsection
