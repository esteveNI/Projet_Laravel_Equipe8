{{--

    Vue : liste des catégories (bloc 4 de Vital).

    Tableau avec le nom de chaque catégorie et le nombre de produits
    qu'elle contient, plus les actions ajouter, modifier et supprimer.
    La suppression d'une catégorie remplie est refusée côté serveur
    (CategorieController : méthode destroy) avec un message d'erreur clair.

--}}

@extends('layout')

@section('titre', 'Catégories — Ma boutique')

@section('contenu')

    <div class="en-tete-page">
        <div>
            <h1>Catégories</h1>
            <p class="description-page">Ranger les produits de la boutique par catégorie.</p>
        </div>
        <a href="{{ route('categories.create') }}" class="bouton">+ Nouvelle catégorie</a>
    </div>

    <div class="section-carte">
        @if ($categories->isEmpty())
            {{-- État vide : aucune catégorie enregistrée --}}
            <div class="etat-vide">
                <h3>Aucune catégorie pour le moment</h3>
                <p>Créez votre première catégorie pour pouvoir ranger vos produits.</p>
                <a href="{{ route('categories.create') }}" class="bouton">+ Nouvelle catégorie</a>
            </div>
        @else
            <div class="tableau-deroulant">
                <table>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Nombre de produits</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $categorie)
                            <tr>
                                <td>{{ $categorie->nom }}</td>
                                <td>
                                    <span class="badge badge-neutre">
                                        {{ $categorie->produits_count }} produit(s)
                                    </span>
                                </td>
                                <td>
                                    <div class="actions-ligne">
                                        <a href="{{ route('categories.edit', $categorie) }}"
                                           class="bouton bouton-editer bouton-tout-petit">Modifier</a>
                                        {{-- Suppression avec confirmation : étape obligatoire avant l'envoi --}}
                                        <form action="{{ route('categories.destroy', $categorie) }}" method="POST"
                                              onsubmit="return confirm('Voulez-vous vraiment supprimer la catégorie « {{ $categorie->nom }} » ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bouton bouton-danger bouton-tout-petit">
                                                Supprimer
                                            </button>
                                        </form>
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
