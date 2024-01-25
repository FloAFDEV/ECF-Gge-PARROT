<?php
define("URL", str_replace("index.php", "", (isset($_SERVER['HTTPS']) ? "https" : "http") .
    "://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]"));
// On passe de http://localhost/..
// -> https://wwww.site.com/...

require_once "controllers/front/API.controller.php";
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
            "manufactureYears", "energyType", "annonces",
            "users", "message", "password"
        ];

        if (!in_array($url[1], $allowedActions)) {
            throw new Exception("Oups cette action n'éxiste pas");
        }

        switch ($url[0]) {
            case "front":
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
                    case "manufactureYears":
                        $apiController->getManufactureYears();
                        break;
                    case "energyType":
                        $apiController->getEnergyType();
                        break;
                    case "annonce":
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
                throw new Exception("La page n'éxiste pas");
        }
    }
} catch (Exception $e) {
    $error = [
        'error' => $e->getMessage(),
    ];
    Model::sendJSON($error);
}
