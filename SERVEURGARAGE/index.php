<?php

require_once "controllers/API.controller.php";
require_once "models/Model.php";


// On définit une constante pour stocker l'URL de base de notre site
define("URL", str_replace("index.php", "", (isset($_SERVER['HTTPS']) ? "https" : "http") .
    "://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]"));

// On passe de http://localhost/..
// -> https://wwww.site.com/...


$apiController = new APIController();


try {
    if (empty($_GET['page'])) {
        throw new Exception("La page demandée n'éxiste pas");
    } else {
        header("Access-Control-Allow-Origin: https://ggevparrot.vercel.app");
        header("Access-Control-Allow-Origin: http://localhost:3000");

        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        $url = explode("/", filter_var($_GET['page'], FILTER_SANITIZE_URL));
        if (!isset($url[0]) || !isset($url[1]))
            throw new Exception("La page demandée n'éxiste pas");
        $allowedActions = [
            "cars", "models", "brands", "garage", "images",
            "testimonials", "opening", "services", "options",
            "years", "energy", "annonces", "contact_message",
            "users", "message_annonce", "password", "login",
        ];
        if (!in_array($url[1], $allowedActions)) {
            throw new Exception("Oups! cette action n'éxiste pas");
        }
        switch ($url[0]) {
            case "api":
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
                    case "cars":
                        // Gérer les méthodes pour les voitures
                        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                            $apiController->getCars();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            //     $apiController->insertCar();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                            //     $apiController->updateCar();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                            //     $apiController->deleteCar();
                        } else {
                            throw new Exception("Méthode non autorisée");
                        }
                        break;
                    case "garage":
                        // Gérer les méthodes pour les modèles
                        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                            $apiController->getGarage();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            //     $apiController->insertGarage();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                            //     $apiController->updateGarage();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                            //     $apiController->deleteGarage();
                        } else {
                            throw new Exception("Méthode non autorisée");
                        }
                        break;
                    case "models":
                        // Gérer les méthodes pour les modèles
                        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                            $apiController->getModels();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            //     $apiController->insertModel();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                            //     $apiController->updateModel();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                            //     $apiController->deleteModel();
                        } else {
                            throw new Exception("Méthode non autorisée");
                        }
                        break;
                    case "brands":
                        // Gérer les méthodes pour les marques
                        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                            $apiController->getBrands();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            //     $apiController->insertBrand();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                            //     $apiController->updateBrand();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                            //     $apiController->deleteBrand();
                        } else {
                            throw new Exception("Méthode non autorisée");
                        }
                        break;
                    case "images":
                        // Gérer les méthodes pour les images
                        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                            $apiController->getImages();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            //     $apiController->insertImage();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                            //     $apiController->updateImage();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                            //     $apiController->deleteImage();
                        } else {
                            throw new Exception("Méthode non autorisée");
                        }
                        break;
                    case "testimonials":
                        // Gérer les méthodes pour les témoignages
                        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                            $apiController->getTestimonials();
                        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $apiController->insertTestimonial();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                            //     $apiController->updateTestimonial();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                            //     $apiController->deleteTestimonial();
                        } else {
                            throw new Exception("Méthode non autorisée");
                        }
                        break;
                    case "opening":
                        // Gérer les méthodes pour les horaires d'ouverture
                        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                            $apiController->getOpening();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            //     $apiController->insertOpening();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                            //     $apiController->updateOpening();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                            //     $apiController->deleteOpening();
                        } else {
                            throw new Exception("Méthode non autorisée");
                        }
                        break;
                    case "services":
                        // Géreles méthodes pour les services du garage
                        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                            $apiController->getGarageServices();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            //     $apiController->insertGarageService();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                            //     $apiController->updateGarageService();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                            //     $apiController->deleteGarageService();
                        } else {
                            exit("Méthode non autorisée");
                        }
                        break;
                    case "options":
                        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                            $apiController->getOptions();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            //     $apiController->insertOption();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                            //     $apiController->updateOption();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                            //     $apiController->deleteOption();
                        } else {
                            throw new Exception("Méthode non autorisée");
                        }
                        break;
                    case "years":
                        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                            $apiController->getManufactureYears();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            //     $apiController->insertManufactureYears();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                            //     $apiController->updateManufactureYears();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                            //     $apiController->deleteManufactureYears();
                        } else {
                            throw new Exception("Méthode non autorisée");
                        }
                        break;
                    case "energy":
                        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                            $apiController->getEnergyType();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            //     $apiController->insertEnergyType();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                            //     $apiController->updateEnergyType();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                            //     $apiController->deleteEnergyType();
                        } else {
                            throw new Exception("Méthode non autorisée");
                        }
                        break;
                    case "contact_message":
                        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                            $apiController->getContactMessage();
                        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $apiController->insertContactMessage();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                            //     $apiController->updateContactMessage();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                            //     $apiController->deleteContactMessage();
                        } else {
                            throw new Exception("Méthode non autorisée");
                        }
                        break;
                    case "users":
                        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                            $apiController->getUsers();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            //     $apiController->insertUser();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                            //     $apiController->updateUser();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                            //     $apiController->deleteUser();
                        } else {
                            throw new Exception("Méthode non autorisée");
                        }
                        break;
                    case "message_annonce":
                        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                            // Récupérer tous les messages pour toutes les annonces
                            $apiController->getAllMessageAnnonce();
                        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $Id_CarAnnonce = $_POST['Id_CarAnnonce'];
                            $apiController->insertMessageAnnonce($Id_CarAnnonce);
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                            //     $apiController->updateMessageAnnonce();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                            //     $apiController->deleteMessageAnnonce();
                        } else {
                            throw new Exception("Méthode non autorisée");
                        }
                        break;
                    case "password":
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $apiController->getResetPassword();
                            // } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                            //     $apiController->deleteResetPassword();
                        } else {
                            throw new Exception("Méthode non autorisée");
                        }
                        break;
                        // case "login":
                        //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        //         $apiController->login();
                        //     } else {
                        //         throw new Exception("Méthode non autorisée");
                        //     }
                        //     break;
                    default:
                        throw new Exception("Oups! Cette page n'existe pas");
                }
                break;
            default:
                throw new Exception("La page demandée n'existe pas");
        }
    }
} catch (Exception $e) {
    $error = [
        'error' => $e->getMessage(),
    ];
    Model::sendJSON($error);
}
