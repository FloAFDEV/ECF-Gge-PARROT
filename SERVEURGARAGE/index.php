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
                    case "brands":
                        $apiController->getBrands();
                        break;
                    case "cars":
                        if (empty($url[2])) throw new Exception("Identifiant de la voiture demandée manquant");
                        $apiController->getCars($url[2]);
                        break;
                    case "models":
                        if (empty($url[3])) throw new Exception("Identifiant du modèle demandé manquant");
                        $apiController->getModels($url[3]);
                        break;
                    case "garage":
                        if (empty($url[4])) throw new Exception("Identifiant du garage demandé manquant");
                        $apiController->getGarage($url[4]);
                        break;
                    case "images":
                        if (empty($url[5])) throw new Exception("Identifiant de l'image demandée manquant");
                        $apiController->getImages($url[5]);
                        break;
                    case "testimonials":
                        if (empty($url[6])) throw new Exception("Identifiant du témoignage demandé manquant");
                        $apiController->getTestimonials($url[6]);
                        break;
                    case "opening":
                        if (empty($url[7])) throw new Exception("Identifiant de l'horaire demandé manquant");
                        $apiController->getOpening($url[7]);
                        break;
                    case "services":
                        if (empty($url[8])) throw new Exception("Identifiant du service demandé manquant");
                        $apiController->getGarageServices($url[8]);
                        break;
                    case "options":
                        if (empty($url[9])) throw new Exception("Identifiant de l'option demandée manquant");
                        $apiController->getOptions($url[9]);
                        break;
                    case "manufactureYears":
                        if (empty($url[10])) throw new Exception("Identifiant de l'année de fabrication demandée manquant");
                        $apiController->getManufactureYears($url[10]);
                        break;
                    case "energyType":
                        if (empty($url[11])) throw new Exception("Identifiant de l'énergie demandée manquant");
                        $apiController->getEnergyType($url[11]);
                        break;
                    case "annonce":
                        if (empty($url[12])) throw new Exception("Identifiant de l'annonce demandée manquant");
                        $apiController->getCarAnnonce($url[12]);
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
