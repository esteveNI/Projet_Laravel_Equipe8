<!DOCTYPE html>
{{--
    Gabarit commun de l'application (bloc 2 de Vital).
    Toutes les pages l'utilisent avec la directive d'héritage Blade (extends layout).
    Rôle du gabarit :
    - en-tête avec le titre du site ;
    - menu avec trois entrées : Tableau de bord, Produits, Catégories ;
    - zone qui affiche les messages de succès et d'erreur (effacement
      automatique au bout de 2 secondes) ;
    - emplacement du contenu de chaque page (section « contenu ») ;
    - pied de page avec les crédits du projet ;
    - mise en page qui s'adapte au téléphone : le menu se replie derrière
      le bouton hamburger et les tableaux défilent horizontalement.
    Remarque : le lien « Produits » est affiché seulement si la route
    existe (partie d'Esteve, bloc 3). Jusqu'à sa fusion, l'entrée est
    simplement masquée au lieu de provoquer une erreur.
--}}
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titre', 'Ma boutique')</title>
    {{-- Feuille de style maison (bloc 2) : aucune compilation npm nécessaire --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

    {{-- En-tête : titre du site et bouton hamburger (visible sur téléphone) --}}
    <header class="l-en-tete">
        <div class="rangee-en-tete">
            <a href="{{ route('tableau-de-bord') }}" class="titre-du-site">
                Ma boutique
            </a>
            <button type="button" class="bouton-menu" aria-label="Ouvrir ou fermer le menu"
                    aria-expanded="false" onclick="basculerMenu(this)">
                <span></span><span></span><span></span>
            </button>
        </div>

        {{-- Menu principal : trois entrées demandées par le sujet --}}
        @php
            $surLeTableauDeBord = request()->routeIs('tableau-de-bord');
            $surLesProduits = Route::has('produits.index') && request()->routeIs('produits.*');
            $surLesCategories = request()->routeIs('categories.*');
        @endphp
        <nav class="menu" id="menu-principal">
            <a href="{{ route('tableau-de-bord') }}" class="{{ $surLeTableauDeBord ? 'actif' : '' }}">
                Tableau de bord
            </a>
            @if (Route::has('produits.index'))
                <a href="{{ route('produits.index') }}" class="{{ $surLesProduits ? 'actif' : '' }}">
                    Produits
                </a>
            @endif
            <a href="{{ route('categories.index') }}" class="{{ $surLesCategories ? 'actif' : '' }}">
                Catégories
            </a>
        </nav>
    </header>

    {{-- Contenu principal : messages flash puis contenu propre à la page --}}
    <main class="conteneur">
        @if (session('succes'))
            <div class="alerte alerte-succes" role="status">
                {{ session('succes') }}
                <button type="button" class="bouton-fermer" aria-label="Fermer le message"
                        onclick="this.closest('.alerte').remove()">x</button>
            </div>
        @endif

        @if (session('erreur'))
            <div class="alerte alerte-erreur" role="alert">
                {{ session('erreur') }}
                <button type="button" class="bouton-fermer" aria-label="Fermer le message"
                        onclick="this.closest('.alerte').remove()">x</button>
            </div>
        @endif

        {{-- Emplacement du contenu de chaque page --}}
        @yield('contenu')
    </main>

    <script>
        // Replie ou déplie le menu sur téléphone (bloc 2 : menu qui se replie).
        function basculerMenu(bouton) {
            const menu = document.getElementById('menu-principal');
            const ouvert = menu.style.display === 'flex';
            menu.style.display = ouvert ? 'none' : 'flex';
            bouton.setAttribute('aria-expanded', ouvert ? 'false' : 'true');
        }

        // Efface automatiquement les messages de succès et d'erreur
        // après 2 secondes, avec un fondu de 0,3 s (bloc 7 : finitions).
        document.querySelectorAll('.alerte').forEach(function (element) {
            setTimeout(function () {
                element.classList.add('alerte-estompe');
                setTimeout(function () { element.remove(); }, 300);
            }, 2000);
        });
    </script>
</body>
</html>
