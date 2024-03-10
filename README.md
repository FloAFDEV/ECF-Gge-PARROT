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

# 💫 About Me:

Jeune développeur tout juste reconverti, passionné de code et autres techno.

## 🌐 Socials:

[![Discord](https://img.shields.io/badge/Discord-%237289DA.svg?logo=discord&logoColor=white)](https://discord.gg/https://discord.gg/hz7C5qFA) [![Facebook](https://img.shields.io/badge/Facebook-%231877F2.svg?logo=Facebook&logoColor=white)](https://www.facebook.com/florent.perez.18/) [![LinkedIn](https://img.shields.io/badge/LinkedIn-%230077B5.svg?logo=linkedin&logoColor=white)](https://www.linkedin.com/in/florent-perez-559524242/)

# 💻 Tech Stack:

![Symfony](https://img.shields.io/badge/symfony-%23000000.svg?style=for-the-badge&logo=symfony&logoColor=white) ![HTML5](https://img.shields.io/badge/html5-%23E34F26.svg?style=for-the-badge&logo=html5&logoColor=white) ![JavaScript](https://img.shields.io/badge/javascript-%23323330.svg?style=for-the-badge&logo=javascript&logoColor=%23F7DF1E) ![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white) ![Trello](https://img.shields.io/badge/Trello-%23026AA7.svg?style=for-the-badge&logo=Trello&logoColor=white) ![Docker](https://img.shields.io/badge/docker-%230db7ed.svg?style=for-the-badge&logo=docker&logoColor=white) ![IOS](https://img.shields.io/badge/IOS-%2320232a.svg?style=for-the-badge&logo=apple&logoColor=white) ![ANDROID](https://img.shields.io/badge/android-%2320232a.svg?style=for-the-badge&logo=android&logoColor=%a4c639) ![Apache](https://img.shields.io/badge/apache-%23D42029.svg?style=for-the-badge&logo=apache&logoColor=white) ![Adobe Lightroom](https://img.shields.io/badge/Adobe%20Lightroom-31A8FF.svg?style=for-the-badge&logo=Adobe%20Lightroom&logoColor=white) ![ESLint](https://img.shields.io/badge/ESLint-4B3263?style=for-the-badge&logo=eslint&logoColor=white)

# 📊 GitHub Stats:

![](https://github-readme-stats.vercel.app/api?username=FloAFDEV&theme=dark&hide_border=false&include_all_commits=false&count_private=false)<br/>
![](https://github-readme-streak-stats.herokuapp.com/?user=FloAFDEV&theme=dark&hide_border=false)<br/>
![](https://github-readme-stats.vercel.app/api/top-langs/?username=FloAFDEV&theme=dark&hide_border=false&include_all_commits=false&count_private=false&layout=compact)

## 🏆 GitHub Trophies

![](https://github-profile-trophy.vercel.app/?username=FloAFDEV&theme=radical&no-frame=false&no-bg=true&margin-w=4)

---

[![](https://visitcount.itsvg.in/api?id=FloAFDEV&icon=0&color=0)](https://visitcount.itsvg.in)

<!-- Proudly created with GPRM ( https://gprm.itsvg.in ) -->

![logo](https://user-images.githubusercontent.com/103335500/226464740-aea77c7f-c2a2-4645-af78-f303dac76a8e.png)
