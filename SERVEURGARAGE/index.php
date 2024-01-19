<?php
define("URL", str_replace("index.php", "", (isset($_SERVER['HTTPS']) ? "https" : "http") .
    "://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]"));
//On passe de http://localhost/..
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
        switch ($url[0]) {
            case "front":
                switch ($url[1]) {
                    case "cars":
                        $apiController->getCars();
                        break;
                    case "models":
                        if (empty($url[2])) throw new Exception("Identifiant des modèles demandée manquant");
                        $apiController->getModels($url[2]);
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
                        $apiController->getCarAnnonce();
                        break;
                    case "users":
                        $apiController->getUsers();
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
    $msg = $e->getMessage();
    echo $msg;
}
