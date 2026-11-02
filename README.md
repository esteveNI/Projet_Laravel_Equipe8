# Suivi de stock d'une petite boutique — Équipe 8

Projet d'initiation aux frameworks · IFRI GL3 · Enseignante : Mme GAHOU

**Sujet attribué : C — Suivi de stock d'une petite boutique (Laravel)**

Dépôt : <https://github.com/esteveNI/Projet_Laravel_Equipe8> (branches `esteve` et `vital`, fusion dans `main` par Pull Request)

## Membres (noms officiels)

- **Mahussiede Esteve Winnerson QUENUM** (affiché : Esteve)
- **Vital OLOKOIKO** (affiché : Vital)

## Description du projet

L'application permet à une petite boutique de suivre son stock : ajouter, modifier, supprimer et consulter des produits, les ranger par catégorie, repérer ceux dont le stock est faible et suivre quelques indicateurs sur un tableau de bord. Réalisée avec Laravel 13 selon l'architecture MVC (routes, contrôleurs, modèles, vues Blade).

Un produit est « en alerte de stock faible » quand sa quantité **atteint ou passe sous** son seuil d'alerte. La suppression d'une catégorie **contenant des produits est refusée** avec un message d'erreur clair.

## Fonctionnalités réalisées

- **Tableau de bord** : nombre de produits, valeur totale du stock (somme de quantité × prix), nombre d'alertes et liste des produits en alerte avec un lien vers leur modification.
- **Produits** : ajout, modification, suppression (avec confirmation) et liste détaillée (nom, catégorie, quantité, seuil, prix), avec validations aux messages clairs affichés sous les champs concernés.
- **Recherche et filtres combinables** : recherche par nom, filtre par catégorie et filtre « stock faible seulement » ; les trois fonctionnent ensemble et restent affichés après la recherche. Les étiquettes « Stock faible » signalent les produits en alerte.
- **Catégories** : liste avec le nombre de produits de chacune, ajout, modification (nom obligatoire et unique) et suppression refusée si la catégorie contient des produits.
- **Mise en page responsive** : menu qui se replie sur téléphone (bouton hamburger), tableaux qui défilent horizontalement, chaque page utilisable sur téléphone et sur ordinateur.
- **Messages** : succès après chaque action, « liste vide » et « aucun résultat » quand rien ne correspond — aucune page d'erreur brute dans le parcours normal.

## Répartition des tâches

| Bloc | Contenu | Membre | Relecteur |
|------|---------|--------|-----------|
| 1 | Base de données et données de démo (migrations, modèles, relation, seeders) | Esteve | Vital |
| 2 | Gabarit et navigation (menu, messages, responsive) | Vital | Esteve |
| 3 | Gestion des produits (CRUD, validations) | Esteve | Vital |
| 4 | Gestion des catégories (CRUD, règle de suppression) | Vital | Esteve |
| 5 | Recherche, filtres et alerte de stock faible | Esteve | Vital |
| 6 | Tableau de bord (indicateurs) | Vital | Esteve |
| 7 | Messages et finitions (états vides, cas d'erreur) | Vital, avec Esteve | — |
| 8 | README | les deux | — |
| 9 | Test sur clone neuf et démonstration | les deux | — |

## Choix techniques

- **Laravel 13** (architecture MVC) — router en `routes/web.php`, contrôleurs en `app/Http/Controllers`, modèles en `app/Models`, vues en `resources/views`.
- **Vues Blade** avec un gabarit commun `resources/views/layout.blade.php` : en-tête, menu, pied de page, zone des messages de succès et d'erreur.
- **CSS maison** (`public/css/app.css`) sans framework ni compilation npm : simple, léger et responsive.
- **Base de données MySQL** (mêmes réglages chez les deux membres), migrations obligatoires dans `database/migrations`, **SQLite non utilisé**.
- **Seeders** (`database/seeders/DatabaseSeeder.php`) : 5 catégories et 13 produits réalistes, dont 4 sous leur seuil d'alerte.
- **Validations** dans les contrôleurs avec messages en français affichés sous les champs concernés.

## Lancement du projet (testé sur un clone neuf)

Prérequis : PHP 8.3+, Composer, MySQL (par exemple avec XAMPP ou Laragon).

1. **Cloner le dépôt et installer les dépendances**

   ```
   git clone https://github.com/esteveNI/Projet_Laravel_Equipe8.git
   cd Projet_Laravel_Equipe8
   composer install
   ```

2. **Créer une base de données vide** dans MySQL, par exemple avec phpMyAdmin ou en ligne de commande :

   ```
   mysql -u root -e "CREATE DATABASE IF NOT EXISTS stock_boutique CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   ```

3. **Configurer le fichier `.env`** : copier `.env.example` en `.env` puis renseigner ces lignes (exemple avec MySQL local de XAMPP, utilisateur `root` sans mot de passe) :

   ```
   APP_NAME="Suivi de stock - Equipe 8"
   APP_LOCALE=fr
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=stock_boutique
   DB_USERNAME=root
   DB_PASSWORD=
   ```

   Ne jamais pousser le fichier `.env` (il est ignoré par git).

4. **Générer la clé de l'application, créer les tables et mettre les données de démonstration**

   ```
   php artisan key:generate
   php artisan migrate --seed
   ```

5. **Lancer le serveur de développement**

   ```
   php artisan serve
   ```

   puis ouvrir <http://localhost:8000>.

Vérification rapide après le lancement : le tableau de bord doit afficher **13 produits**, une **valeur totale de 324 100 FCFA** et **4 alertes** (Sucre en poudre, Spaghetti, Jus d'ananas, Dentifrice).

## Parcours de démonstration (5 à 7 minutes)

Tableau de bord → ajouter un produit → le rechercher → le modifier jusqu'à déclencher l'alerte de stock faible → le supprimer.
