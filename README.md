# Projet Symfony avec EasyAdmin et Multi-Bases de Données

Ce projet est une application Symfony qui utilise EasyAdmin pour recréer la partie gestion/administration d'une application web.

Le but de ce projet est d'évaluer le temps gagné en passant d'un framework "homemade" à Symfony/EasyAdmin.
Pour essayer de creuser, nous avons une configuration multi-bases de données, des liens entre les entités, des éléments customs...

## Prérequis

Avant de commencer, assurez-vous d'avoir les éléments suivants installés sur votre machine :

- PHP 8.1 ou supérieur
- Composer (gestionnaire de dépendances PHP)
- Symfony CLI (optionnel, mais recommandé)
- MySQL/MariaDB ou tout autre SGBD pris en charge par Doctrine

## Installation

1. **Cloner le dépôt :**

   ```bash
   git clone https://github.com/JulesFonsecaDiiage/d2-poc.git
   cd d2-poc
   ```
   
2. **Installer les dépendances :**

   ```bash
    composer install
    ```
   
3. **Configurer les bases de données :**

    ```dotenv
    # Base de données principale (default)
    DATABASE_URL="mysql://user:password@127.0.0.1:3306/default_db?serverVersion=mariadb-10.6.8&charset=utf8mb4"

    # Base de données de facturation
    DATABASE_URL_FACTURATION="mysql://user:password@127.0.0.1:3306/facturation_db?serverVersion=mariadb-10.6.8&charset=utf8mb4"
    ```
   
4. **Exécuter les migrations :**
    
    ```bash
    php bin/console app:migrations:migrate
    ```
   
5. **Charger les fixtures :**
    
    ```bash
    php bin/console app:fixtures
    ```
   
6. **Démarrer le serveur de développement :**
    
    ```bash
    symfony server:start
    ```
   
7. **Accéder à l'application :**

   En cliquant [ici](http://127.0.0.1:8000/admin)

## Informations Utiles

- **Commandes personnalisées :**
  - `php bin/console app:migrations:diff` : Permet de générer les migrations
  - `php bin/console app:migrations:migrate` : Permet d'exécuter les migrations
  - `php bin/console app:fixtures` : Charge les fixtures de toutes les bases de données
   

   
