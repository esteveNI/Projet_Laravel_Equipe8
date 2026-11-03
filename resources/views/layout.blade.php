
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titre', 'Ma boutique')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

    @php
        $surLeTableauDeBord = request()->routeIs('tableau-de-bord');
        $surLesProduits = Route::has('produits.index') && request()->routeIs('produits.*');
        $surLesCategories = request()->routeIs('categories.*');
    @endphp

    {{-- En-tête : le conteneur est le même que celui du contenu, donc tout est aligné --}}
    <header class="entete">
        <div class="conteneur">
            <a href="{{ route('tableau-de-bord') }}" class="entete-titre">Ma boutique</a>

            {{-- Bouton hamburger : visible seulement sur téléphone --}}
            <button type="button" class="entete-burger" aria-label="Ouvrir ou fermer le menu"
                    aria-expanded="false" onclick="basculerMenu(this)">
                <span></span><span></span><span></span>
            </button>

            <nav class="entete-menu" id="menu-principal">
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
        </div>
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

        @yield('contenu')
    </main>

    {{--
        Boîte de confirmation partagée. Un bouton qui possède l'attribut
        data-suppression l'ouvre en lui passant le titre, le message et l'adresse
        de suppression. Si data-bloque vaut 1, la suppression est impossible :
        on affiche seulement le message et un bouton « Fermer ».
    --}}
    <dialog id="dialogue-confirmation" class="dialogue">
        <form method="POST" id="dialogue-formulaire">
            @csrf
            @method('DELETE')
            <h2 id="dialogue-titre"></h2>
            <p id="dialogue-message"></p>
            <div class="dialogue-actions">
                <button type="button" class="bouton bouton-editer" id="dialogue-annuler">Annuler</button>
                <button type="submit" class="bouton bouton-danger" id="dialogue-confirmer">Supprimer</button>
            </div>
        </form>
    </dialog>

    <script>
        // Replie ou déplie le menu sur téléphone.
        function basculerMenu(bouton) {
            const menu = document.getElementById('menu-principal');
            const ouvert = menu.classList.toggle('ouvert');
            bouton.setAttribute('aria-expanded', ouvert ? 'true' : 'false');
        }

        // Efface automatiquement les messages de succès 
        document.querySelectorAll('.alerte-succes, .alerte-erreur').forEach(function (element) {
            const duree = element.classList.contains('alerte-erreur') ? 5000 : 2000;
            setTimeout(function () {
                element.classList.add('alerte-estompe');
                setTimeout(function () { element.remove(); }, 300);
            }, duree);
        });

        // Boîte de confirmation de suppression.
        (function () {
            const dialogue = document.getElementById('dialogue-confirmation');
            const formulaire = document.getElementById('dialogue-formulaire');
            const titre = document.getElementById('dialogue-titre');
            const message = document.getElementById('dialogue-message');
            const confirmer = document.getElementById('dialogue-confirmer');
            const annuler = document.getElementById('dialogue-annuler');

            document.querySelectorAll('[data-suppression]').forEach(function (bouton) {
                bouton.addEventListener('click', function () {
                    const bloque = bouton.dataset.bloque === '1';
                    titre.textContent = bouton.dataset.titre;
                    message.textContent = bouton.dataset.message;
                    formulaire.action = bouton.dataset.action;
                    confirmer.style.display = bloque ? 'none' : '';
                    annuler.textContent = bloque ? 'Fermer' : 'Annuler';
                    dialogue.showModal();
                });
            });

            annuler.addEventListener('click', function () { dialogue.close(); });
        })();
    </script>

    {{-- Scripts propres à une page (voir @push('scripts') dans les vues) --}}
    @stack('scripts')
</body>
</html>
