<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// On définit une constante pour stocker l'URL de base de notre site
define("URL", str_replace("index.php", "", (isset($_SERVER['HTTPS']) ? "https" : "http") .
    "://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]"));

// On passe de http://localhost/..
// -> https://wwww.site.com/...


require_once "controllers/API.controller.php";
require_once "./models/admin.manager.php";
require_once "./models/Model.php";
require_once "./utils/auth.php";

$adminManager = new AdminManager();

session_start();

$apiController = new APIController();

// Définition des en-têtes CORS
header("Access-Control-Allow-Origin: https://ggevparrot.vercel.app");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Vérifie si la demande est une demande OPTIONS préalable
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Si c'est le cas, renvoie les en-têtes CORS sans traitement supplémentaire
    http_response_code(200);
    exit();
}

// Vérification de l'authentification pour la route admin
if ($_SERVER['REQUEST_URI'] === '/admin') {
    // Vérification de l'authentification avec le middleware
    $userData = requireAuth();
    if (!$userData) {
        // Si l'utilisateur n'est pas authentifié, envoie une réponse d'erreur
        http_response_code(401);
        Model::sendJSON(["error" => "Authentification requise"]);
        exit();
    }
}

// On vérifie si la page demandée est définie en GET, sinon on utilise par défaut la page d'accueil

if (empty($_GET['page'])) {
    throw new Exception("La page demandée n'existe pas");
}


// Analyse de l'URL demandée
$url = explode("/", filter_var($_GET['page'], FILTER_SANITIZE_URL));
if (!isset($url[0]) || !isset($url[1])) {
    throw new Exception("La page demandée n'existe pas");
}
$allowedActions = [
    "cars", "models", "brands", "garage", "images",
    "testimonials", "testimonial", "opening", "services", "options",
    "years", "energy", "annonces", "annonce", "contact_message",
    "users", "message_annonce", "password", "admin", "validite",
];
if (!in_array($url[1], $allowedActions)) {
    throw new Exception("Oups! cette action n'éxiste pas");
}
switch ($url[0]) {
    case "api":
        switch ($url[1]) {
            case "annonces":
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Récupére les données JSON envoyées dans la requête POST depuis le front
                    $data = json_decode(file_get_contents("php://input"), true);
                    // Vérifieque toute les données soient présentes
                    if (
                        isset($data['annonce_title']) &&
                        isset($data['brand_name']) &&
                        isset($data['registration']) &&
                        isset($data['price']) &&
                        isset($data['power']) &&
                        isset($data['power_unit']) &&
                        isset($data['color']) &&
                        isset($data['model_name']) &&
                        isset($data['category_model']) &&
                        isset($data['manufacture_year']) &&
                        isset($data['fuel_type']) &&
                        isset($data['options_name'])
                    ) {
                        $apiController->createAnnonce($data);
                        // Message de succès
                        http_response_code(201); // Code 201 pour cré   ation ok
                        echo json_encode(array("message" => "Annonce créée avec succès"));
                    } else {
                        http_response_code(400);
                        echo json_encode(array("error" => "Toutes les données obligatoires ne sont pas fournies"));
                    }
                } elseif (isset($url[2]) && is_numeric($url[2])) {
                    if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($url[3]) && $url[3] === 'validite') {
                        // Récupére le statut de validité depuis les données POST
                        $data = json_decode(file_get_contents("php://input"), true);
                        $newValidity = $data['valid'];
                        // Appele la méthode updateValidationStatus avec l'ID de l'annonce et le nouveau statut de validité
                        $apiController->updateValidationStatus($url[2], $newValidity);
                    } else {
                        $apiController->getAnnonceByID($url[2]);
                    }
                } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    $apiController->getAllAnnonces();
                } else {
                    throw new Exception("Identifiant du véhicule invalide");
                }
                break;
            case "annonce":
                switch ($url[2]) {
                    case ($_SERVER['REQUEST_METHOD'] === 'DELETE'):
                        error_log('DELETE request received for annonces');
                        if (isset($url[2]) && is_numeric($url[2])) {
                            $annonceId = $url[2];
                            // Suppression du témoignage
                            $deleteSuccess = $apiController->deleteAnnonce($annonceId);
                            if ($deleteSuccess) {
                                http_response_code(200);
                                return Model::sendJSON(["message" => "Annonce supprimé avec succès"]);
                            } else {
                                http_response_code(500);
                                return Model::sendJSON(["error" => "Échec de la suppression de l'annonce"]);
                            }
                        } else {
                            http_response_code(400);
                            return Model::sendJSON(["error" => "Données incomplètes pour la suppression de l'annonce"]);
                        }
                        break;
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
            case "testimonial":
                // var_dump($_SERVER['REQUEST_METHOD']);
                switch ($url[2]) {
                    case ($_SERVER['REQUEST_METHOD'] === 'GET'):
                        if (isset($url[2]) && is_numeric($url[2])) {
                            // var_dump($url[2]);
                            $apiController->getDBTestimonialId($url[2]);
                        }
                        break;
                    case ($_SERVER['REQUEST_METHOD'] === 'PUT'):
                        if (isset($url[2]) && is_numeric($url[2])) {
                            $data = json_decode(file_get_contents("php://input"), true);
                            error_log('PUT data: ' . print_r($data, true));
                            if (isset($data['valid'])) {
                                $testimonialId = $url[2];
                                $newValidity = $data['valid'];
                                $updateSuccess = $apiController->updateTestimonialValidation($testimonialId, $newValidity);
                                // var_dump($updateSuccess);
                                if ($updateSuccess) {
                                    http_response_code(200);
                                    return   Model::sendJSON(["message" => "Statut de validation du témoignage mis à jour avec succès"]);
                                } else {
                                    http_response_code(500);
                                    return  Model::sendJSON(["error" => "Échec de la mise à jour du statut de validation du témoignage"]);
                                }
                            } else {
                                http_response_code(400);
                                return Model::sendJSON(["error" => "Données incomplètes pour la mise à jour du statut de validation du témoignage"]);
                            }
                        }
                        break;
                    case ($_SERVER['REQUEST_METHOD'] === 'DELETE'):
                        error_log('DELETE request received for testimonials');
                        if (isset($url[2]) && is_numeric($url[2])) {
                            $testimonialId = $url[2];
                            // Suppression du témoignage
                            $deleteSuccess = $apiController->deleteTestimonial($testimonialId);
                            if ($deleteSuccess) {
                                http_response_code(200);
                                return Model::sendJSON(["message" => "Témoignage supprimé avec succès"]);
                            } else {
                                http_response_code(500);
                                return Model::sendJSON(["error" => "Échec de la suppression du témoignage"]);
                            }
                        } else {
                            http_response_code(400);
                            return Model::sendJSON(["error" => "Données incomplètes pour la suppression du témoignage"]);
                        }
                        break;
                }
                break;
            case "testimonials":
                switch ($_SERVER['REQUEST_METHOD']) {
                    case 'GET':
                        $apiController->getTestimonials();
                        break;
                    case 'POST':
                        $apiController->insertTestimonial();
                        break;
                    default:
                        error_log('Unhandled request method for testimonials: ' . $_SERVER['REQUEST_METHOD']);
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
                    throw new Exception("Méthode non autorisée");
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
                    // Récupérer l'ID de l'annonce de voiture à partir des données POST
                    $formData = json_decode(file_get_contents('php://input'));
                    $Id_CarAnnonce = $formData->Id_CarAnnonce;
                    // Vérifier si l'ID de l'annonce de voiture est défini
                    if (isset($Id_CarAnnonce)) {
                        // Appeler la méthode insertMessageAnnonce avec l'ID de l'annonce de voiture et les données du formulaire
                        $apiController->insertMessageAnnonce($Id_CarAnnonce, $formData);
                    } else {
                        // Retourner une erreur si l'ID de l'annonce de voiture n'est pas défini
                        http_response_code(400); // Mauvaise requête
                        Model::sendJSON(["error" => "L'ID de l'annonce de voiture n'est pas défini"]);
                    }
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
            case "admin":
                // Vérification de la méthode de la requête
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $formData = json_decode(file_get_contents('php://input'), true);
                    // var_dump($formData);
                    $email = $formData["email"];
                    // var_dump($email);
                    $password = $formData["password"];
                    // Vérification des identifiants
                    $credentialsValid = $adminManager->checkCredentials($email, $password);
                    if ($credentialsValid) {
                        // Récupération de l'ID de l'utilisateur à partir de son e-mail
                        $userId = $adminManager->getUserIdByEmail($email);
                        // Récupération du rôle de l'utilisateur à partir de son e-mail
                        $userRole = $adminManager->getUserRoleByEmail($email);
                        // Génération d'un nouveau jeton JWT
                        $token = generateAuthToken($email, $userRole);
                        // Envoi du jeton dans le corps de la réponse JSON
                        http_response_code(200); // OK
                        Model::sendJSON(["token" => $token]);
                    } else {
                        http_response_code(401); // Non autorisé
                        Model::sendJSON(["error" => "Identifiants incorrects ! Veuillez réessayer."]);
                        exit(); // Sortie immédiate
                    }
                } else {
                    // Vérification de l'authentification avec le middleware (utils/auth.php)
                    $userData = requireAuth();
                    if (!$userData) {
                        // Si l'utilisateur n'est pas authentifié, renvoie une réponse d'erreur
                        http_response_code(401);
                        Model::sendJSON(["error" => "Authentification requise"]);
                        exit();
                    }
                    // Si l'utilisateur est authentifié, message de bienvenue avec nom complet et rôle
                    $fullName = $userData->fullName;
                    $userRole = $userData->userRole;
                    $welcomeMessage = "Bienvenue, $fullName ! Vous êtes connecté en tant que $userRole.";
                    Model::sendJSON(["message" => $welcomeMessage]);
                }
                break;
            default:
                // Vérification de l'authentification avec le middleware (utils/auth.php)
                require_once "./utils/auth.php";
                $userData = requireAuth();
                if (!$userData) {
                    // Si l'utilisateur n'est pas authentifié, renvoie une réponse d'erreur
                    http_response_code(401);
                    Model::sendJSON(["error" => "Authentification requise"]);
                    exit();
                }
                // Si l'utilisateur est authentifié, message de bienvenue avec nom complet et rôle
                $fullName = $userData->fullName;
                $userRole = $userData->userRole;
                $welcomeMessage = "Bienvenue, $fullName ! Vous êtes connecté en tant que $userRole.";
                Model::sendJSON(["message" => $welcomeMessage]);
                break;
        }
}
