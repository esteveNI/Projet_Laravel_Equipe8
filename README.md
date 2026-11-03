# Suivi de stock d'une petite boutique — Équipe 8

Projet d'initiation aux frameworks · IFRI GL3 · Enseignante : Mme GAHOU

**Sujet attribué : C — Suivi de stock d'une petite boutique (Laravel)**

Dépôt GitHub : <https://github.com/esteveNI/Projet_Laravel_Equipe8>

## Membres de l'équipe

- **Mahussiede Esteve Winnerson QUENUM** (Esteve)
- **Vital OLOKOIKO** (Vital)

## Présentation

Cette application web permet à une petite boutique de gérer ses produits et de suivre l'état de son stock. Elle permet notamment d'ajouter, consulter, modifier et supprimer des produits, de les organiser par catégorie, de rechercher et filtrer les produits et de repérer les stocks faibles depuis un tableau de bord.

Le projet est développé avec **Laravel 13**, en respectant l'architecture MVC : routes, contrôleurs, modèles et vues Blade.

Un produit est considéré en stock faible lorsque sa quantité est **inférieure ou égale à son seuil d'alerte**. La suppression d'une catégorie qui contient encore des produits est refusée et un message explicatif est affiché.

## Fonctionnalités

- **Tableau de bord** : nombre de produits, valeur totale du stock (quantité × prix), nombre d'alertes et liste des produits concernés.
- **Gestion des produits** : création, consultation, modification et suppression ; formulaire avec validations et messages explicites.
- **Recherche et filtres combinables** : recherche par nom, filtre par catégorie et filtre des produits en stock faible.
- **Gestion des catégories** : création, consultation, modification et suppression, avec blocage de la suppression lorsqu'une catégorie contient des produits.
- **Interface responsive** : navigation adaptée aux petits écrans, menu repliable et tableaux consultables sur téléphone comme sur ordinateur.
- **Retours à l'utilisateur** : messages de succès et d'erreur, affichage des listes vides et indication lorsqu'une recherche ne renvoie aucun résultat.
- **Interactions d'interface** : confirmation avant suppression et actualisation automatique des filtres.


## Répartition des tâches

### Mahussiede Esteve Winnerson QUENUM
- Conception et mise en place de la base de données.
- Création des migrations et des modèles.
- Définition des relations entre les modèles `Produit` et `Categorie`.
- Création et configuration des seeders pour les catégories et les produits.
- Développement initial du CRUD des produits.
- Participation à la conception et à la réalisation des vues liées aux produits.
- Réalisation de corrections, d'améliorations et de finitions sur différentes parties de l'application.
- Mise à jour de la documentation technique du projet.

### Vital OLOKOIKO
- Développement du CRUD des catégories.
- Développement du tableau de bord et de ses indicateurs.
- Contribution importante au développement et à l'amélioration du contrôleur de gestion des produits.
- Mise en place de la structure générale des vues et du layout partagé.
- Intégration de la navigation et des différents éléments communs de l'interface.
- Participation à l'intégration et à la finalisation de l'interface utilisateur.

### Tâches réalisées en collaboration
- Intégration des différentes fonctionnalités de l'application.
- Tests et vérification du bon fonctionnement général.
- Vérification de la cohérence entre les différentes parties du projet.
- Finalisation de l'application en vue de sa présentation.
**Remarque sur l'historique Git :** les commits et les fusions du dépôt permettent de retrouver les différentes étapes du travail. Le tableau ci-dessus décrit les contributions au projet dans son ensemble, y compris le travail collaboratif, les reprises de code et les corrections réalisées après les premières versions.

## Choix techniques

- **Framework :** Laravel 13, avec l'architecture MVC.
- **Routes :** `routes/web.php`.
- **Contrôleurs :** `app/Http/Controllers`.
- **Modèles :** `app/Models`.
- **Vues :** Blade, dans `resources/views`.
- **Interface :** CSS dans `public/css/app.css`, avec des ajustements JavaScript pour certaines interactions.
- **Base de données :** MySQL. SQLite n'est pas utilisé.
- **Données de démonstration :** seeders Laravel (`database/seeders`). Le jeu de données comprend 5 catégories et 13 produits, dont 4 produits en stock faible.

## Installation et lancement

### Prérequis

- PHP 8.3 ou version compatible avec le projet ;
- Composer ;
- MySQL (par exemple via XAMPP ou Laragon).

### 1. Cloner le dépôt

```bash
git clone https://github.com/esteveNI/Projet_Laravel_Equipe8.git
cd Projet_Laravel_Equipe8
composer install
```

### 2. Créer la base de données

Créer une base de données MySQL nommée `gestion_stock`, par exemple avec phpMyAdmin ou avec la commande suivante :

```sql
CREATE DATABASE gestion_stock CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3. Configurer l'environnement

Copier `.env.example` vers `.env`, puis vérifier les paramètres MySQL suivants et les adapter à votre installation :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_stock
DB_USERNAME=root
DB_PASSWORD=
```

Ne jamais publier le fichier `.env` : il peut contenir des informations sensibles propres à votre machine.

### 4. Générer la clé et préparer la base

```bash
php artisan key:generate
php artisan migrate --seed
```

### 5. Démarrer le serveur

```bash
php artisan serve
```

Ouvrir ensuite <http://localhost:8000> dans le navigateur.

### Données de démonstration attendues

Avec les seeders fournis, le tableau de bord doit notamment afficher :

- **13 produits** ;
- **324 100 FCFA** de valeur totale de stock ;
- **4 produits en stock faible** : Sucre en poudre, Spaghetti, Jus d'ananas et Dentifrice.

## Parcours de démonstration

Tableau de bord → ajout d'un produit → recherche du produit → modification de sa quantité pour déclencher une alerte de stock faible → suppression du produit.

La démonstration présente également la gestion des catégories, les filtres et l'affichage sur petit écran.
