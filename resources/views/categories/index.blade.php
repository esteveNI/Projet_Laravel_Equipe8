
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
                            @php
                                // Une catégorie qui contient des produits ne peut pas être supprimée.
                                $bloquee = $categorie->produits_count > 0;
                                $titre = $bloquee ? 'Suppression impossible' : 'Supprimer cette catégorie ?';
                                $message = $bloquee
                                    ? "La catégorie « {$categorie->nom} » contient encore {$categorie->produits_count} produit(s). Déplacez ou supprimez d'abord ces produits."
                                    : "La catégorie « {$categorie->nom} » sera définitivement supprimée. Cette action est irréversible.";
                            @endphp
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
                                        <button type="button" class="bouton bouton-danger bouton-tout-petit"
                                                data-suppression
                                                data-action="{{ route('categories.destroy', $categorie) }}"
                                                data-titre="{{ $titre }}"
                                                data-message="{{ $message }}"
                                                data-bloque="{{ $bloquee ? '1' : '0' }}">
                                            Supprimer
                                        </button>
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
