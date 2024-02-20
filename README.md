# API Garage Vincent Parrot

Ceci est une API construite en PHP pour gérer les opérations du "Garage Vincent Parrot". Elle fournit différents points de terminaison pour interagir avec la base de données et effectuer des opérations CRUD sur différentes entités liées au garage.

## Configuration

### Prérequis

- PHP (>= 7.0)
- MySQL
- Serveur Apache (ou équivalent)
- Composer (pour l'autoload)

### Étapes d'installation

1. Clonez le dépôt sur votre machine locale.

2. Assurez-vous d'avoir PHP et MySQL installés et correctement configurés sur votre système.

3. Importez le schéma de base de données MySQL fourni dans le fichier `database.sql` pour configurer la structure de la base de données.

4. Installez les dépendances Composer en exécutant la commande suivante dans le répertoire racine du projet :

   ```
   composer install
   ```

5. Configurez votre serveur web (par exemple, Apache) pour pointer vers le répertoire public du projet en tant que document root.

6. Activez `mod_rewrite` si ce n'est pas déjà fait. Vous pouvez le faire en exécutant la commande suivante :

   ```
   sudo a2enmod rewrite
   ```

7. Si vous utilisez MAMP, assurez-vous que le serveur Apache fonctionne et configurez le virtual host pour pointer vers le répertoire du projet.

8. Mettez à jour les identifiants de la base de données dans le fichier `config.php` avec vos identifiants MySQL.

9. Une fois que tout est configuré, vous devriez pouvoir accéder aux points de terminaison de l'API via votre serveur web.

## Utilisation

### Structure des Points de Terminaison

Les points de terminaison de l'API suivent une structure RESTful. Voici quelques exemples de points de terminaison disponibles :

- **GET /backend/cars**: Récupérer toutes les voitures disponibles dans le garage.
- **GET /backend/models**: Récupérer tous les modèles de voitures.
- **GET /backend/brands**: Récupérer toutes les marques de voitures.
- **GET /backend/garage**: Récupérer des informations sur le garage.
- **GET /backend/images**: Récupérer toutes les images liées aux voitures.
- **GET /backend/testimonials**: Récupérer les témoignages des clients.
- **GET /backend/opening**: Récupérer les heures d'ouverture du garage.
- **GET /backend/services**: Récupérer les services offerts par le garage.
- **GET /backend/options**: Récupérer les options disponibles pour les voitures.
- **GET /backend/annee**: Récupérer les années de fabrication des voitures.
- **GET /backend/energy**: Récupérer les types d'énergie pour les voitures.
- **GET /backend/annonces**: Récupérer les annonces pour les voitures.

Ces points de terminaison vous permettent d'effectuer différentes opérations telles que la récupération de données sur les voitures, les modèles, les marques, les informations sur le garage, les témoignages, les heures d'ouverture, les services, les options, les années de fabrication, les types d'énergie et les annonces.

### Gestion des Erreurs

L'API est équipée de mécanismes de gestion des erreurs pour fournir des messages d'erreur significatifs en cas de problème. Si une erreur se produit lors du traitement d'une requête, l'API renvoie une réponse JSON avec des détails sur l'erreur.

### Personnalisation

N'hésitez pas à personnaliser et étendre l'API selon vos besoins. Vous pouvez ajouter de nouveaux points de terminaison, modifier ceux existants ou améliorer la fonctionnalité selon vos besoins.

## Contribution

Les contributions sont les bienvenues ! Si vous trouvez des problèmes ou avez des suggestions d'amélioration, n'hésitez pas à contribuer.
