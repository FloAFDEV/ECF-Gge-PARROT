<?php

// Charger les variables d'environnement
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

// Définir l'URL de base
define("URL", str_replace("index.php", "", (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]"));
// On passe de http://localhost/..
// -> https://wwww.site.com/...

// Inclure le contrôleur de l'API
require_once "controllers/API.controller.php";
$apiController = new APIController();

try {
    if (empty($_GET['page'])) {
        throw new Exception("La page demandée n'éxiste pas");
    } else {
        $url = explode("/", filter_var($_GET['page'], FILTER_SANITIZE_URL));
        if (!isset($url[0]) || !isset($url[1]))
            throw new Exception("La page demandée n'éxiste pas");

        $allowedActions = [
            "cars", "models", "brands", "garage", "images",
            "testimonials", "opening", "services", "options",
            "years", "energy", "annonces",
            "users", "message", "password"
        ];

        if (!in_array($url[1], $allowedActions)) {
            throw new Exception("Oups cette action n'éxiste pas");
        }

        // Configuration de la base de données
        $dbConfig = [
            'DB_HOST' => $_ENV['DATABASE_DNS'],
            'DB_DATABASE' => $_ENV['DB_NAME'],
            'DB_USERNAME' => $_ENV['DATABASE_USER'],
            'DB_PASSWORD' => $_ENV['DATABASE_PASSWORD']
        ];

        // Router les requêtes vers les actions appropriées
        switch ($url[0]) {
            case "backend":
                // Appeler la méthode appropriée du contrôleur de l'API en lui passant les informations de configuration de la base de données
                switch ($url[1]) {
                    case "annonces":
                        if (isset($url[2]) && is_numeric($url[2])) {
                            $apiController->getAnnonceByID($url[2]);
                        } elseif (!isset($url[2])) {
                            // Gérer le cas où aucun identifiant n'est fourni
                            // Cela peut inclure la logique pour récupérer toutes les voitures
                            $apiController->getAllAnnonces();
                        } else {
                            // Gérer le cas où l'identifiant n'est pas un nombre valide
                            throw new Exception("Identifiant du véhicule invalide");
                        }
                        break;
                    case "models":
                        $apiController->getModels();
                        break;
                    case "brands":
                        $apiController->getBrands();;
                        break;
                    case "garage":
                        $apiController->getGarage();
                        break;
                    case "images":
                        $apiController->getImages();
                        break;
                    case "testimonials":
                        $apiController->getTestimonials();
                        break;
                    case "opening":
                        $apiController->getOpening();
                        break;
                    case "services":
                        $apiController->getGarageServices();
                        break;
                    case "options":
                        $apiController->getOptions();
                        break;
                    case "years":
                        $apiController->getManufactureYears();
                        break;
                    case "energy":
                        $apiController->getEnergyType();
                        break;
                    case "cars":
                        $apiController->getCars();
                        break;
                    case "users":
                        $apiController->getUsers();
                        break;
                    case "message":
                        $apiController->getMessage();
                        break;
                    case "password":
                        $apiController->getResetPassword();
                        break;
                    default:
                        throw new Exception("Oups cette page n'éxiste pas");
                }
                break;
            case "back":
                echo "page back demandée";
                break;
            default:
                throw new Exception("La page demandée n'éxiste pas");
        }
    }
} catch (Exception $e) {
    // Gérer les erreurs et renvoyer une réponse JSON
    $error = [
        'error' => $e->getMessage(),
    ];
    Model::sendJSON($error);
}
