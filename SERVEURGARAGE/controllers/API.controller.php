<?php
require_once "models/API.manager.php";
require_once "models/Model.php";

// Définition des constantes pour les codes de statut HTTP
define("HTTP_OK", 200);
define("HTTP_BAD_REQUEST", 400);
define("HTTP_INTERNAL_SERVER_ERROR", 500);


// méthodes
class APIController
{
    private $apiManager;
    public function __construct()
    {

        $this->apiManager = new APIManager();
    }

    // Mise en place d'une méthode commune pour l'affichage des données sans redondance (DRY)
    // private function displayData($datas)
    // {
    //     echo "<pre>";
    //     print_r($datas);
    //     echo "</pre>";
    // }

    private function sendJSONResponse($data, $statusCode)
    {
        http_response_code($statusCode);
        header("Content-Type: application/json");
        Model::sendJSON($data);
    }

    public function getAllAnnonces($idAnnonces = null)
    {
        // Récupération des informations via APIManager avec la fonction getAnnonces
        $linesAnnonces = ($idAnnonces !== null) ? $this->apiManager->getAllAnnonces($idAnnonces) : $this->apiManager->getAllAnnonces();
        $this->sendJSONResponse($linesAnnonces, 200);
    }
    public function getAnnonceByID($id)
    {
        // Récupération des informations via APIManager avec la fonction getAnnonces
        $annonce = $this->apiManager->getAnnonceByID($id);
        $this->sendJSONResponse($annonce, 200);        // $this->displayData($annonce);
    }


    public function getModels()
    { // Récupération des informations via APIManager avec la fonction getModels
        $models = $this->apiManager->getDBModels();
        $this->sendJSONResponse($models, 200);        // $this->displayData($models);
    }

    public function getBrands()
    { // Récupération des informations via APIManager avec la fonction getBrands
        $brands = $this->apiManager->getDBBrands();
        $this->sendJSONResponse($brands, 200);        // $this->displayData($brands);
    }

    public function getGarage()
    { // Récupération des informations via APIManager avec la fonction getGarage
        $garage = $this->apiManager->getDBGarage();
        $this->sendJSONResponse($garage, 200);        // $this->displayData($garage);
    }

    public function getImages()
    { // Récupération des informations via APIManager avec la fonction getImages
        $images = $this->apiManager->getDBImages();
        $this->sendJSONResponse($images, 200);        // $this->displayData($images);
    }

    public function getTestimonials()
    { // Récupération des informations via APIManager avec la fonction getTestimonials
        $testimonials = $this->apiManager->getDBTestimonials();
        $this->sendJSONResponse($testimonials, 200);        // $this->displayData($testimonials);
    }

    public function insertTestimonial()
    {
        header("Access-Control-Allow-Headers: Accept, Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token, Authorization");
        $formData = json_decode(file_get_contents('php://input'), true);
        if ($formData === null) {
            http_response_code(400);
            Model::sendJSON(["error" => "Aucunes données transmises dans le formulaire"]);
        } else {
            try {
                // Utiliser l'instance existante de APIManager
                $result = $this->apiManager->insertTestimonial($formData);
                if ($result === true) {
                    http_response_code(200); // OK
                    Model::sendJSON(["success" => "Votre témoignage a bien été enregistré"]);
                } else {
                    $response = ["error" => "Une erreur est survenue lors de l'enregistrement de votre témoignage"];
                    http_response_code(500);
                    Model::sendJSON($response);
                }
            } catch (Exception $e) {
                http_response_code(HTTP_INTERNAL_SERVER_ERROR);
                $this->sendJSONResponse($response, HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    public function getOpening()
    { // Récupération des informations via APIManager avec la fonction getOpening
        $opening = $this->apiManager->getDBOpening();
        $this->sendJSONResponse($opening, 200);        // $this->displayData($opening);
    }

    public function getGarageServices()
    { // Récupération des informations via APIManager avec la fonction getDBGarageServices
        $services = $this->apiManager->getDBGarageServices();
        $this->sendJSONResponse($services, 200);        // $this->displayData($services);
    }

    public function getOptions()
    { // Récupération des informations via APIManager avec la fonction getOptions
        $options = $this->apiManager->getDBOptions();
        $this->sendJSONResponse($options, 200);        // $this->displayData($idoptions);
    }

    public function getManufactureYears()
    { // Récupération des informations via APIManager avec la fonction getManufactureYears
        $manufactureYears = $this->apiManager->getDBManufactureYears();
        $this->sendJSONResponse($manufactureYears, 200);        // $this->displayData($manufactureYears);
    }

    public function getEnergyType()
    { // Récupération des informations via APIManager avec la fonction getEnergyType
        $energy = $this->apiManager->getDBEnergyType();
        $this->sendJSONResponse($energy, 200);        // $this->displayData($energyType);
    }

    public function getCars()
    { // Récupération des informations via APIManager avec la fonction getCarAnnonce
        $annonce = $this->apiManager->getDBCars();
        $this->sendJSONResponse($annonce, 200);        // $this->displayData($carAnnonce);
    }

    public function getAllMessageAnnonce()
    { // Appelle la méthode correspondante dans APIManager pour récupérer les messages
        $messages = $this->apiManager->getDBAllMessageAnnonce();
        // Envoie les messages au format JSON
        $this->sendJSONResponse($messages, 200);
    }

    public function getResetPassword()
    { // Récupération des informations via APIManager avec la fonction getResetPassword
        $password = $this->apiManager->getDBResetPassword();
        $this->sendJSONResponse($password, 200);        // $this->displayData($resetPassword);
    }

    public function getContactMessage()
    { // Récupération des informations via APIManager avec la fonction getContactMessage
        $password = $this->apiManager->getDBContactMessage();
        $this->sendJSONResponse($password, 200);        // $this->displayData($resetPassword);
    }

    public function getUsers()
    {    // Récupération des informations via APIManager avec la fonction getUsers
        $users = $this->apiManager->getDBUsers();
        $this->sendJSONResponse($users, 200);        // $this->displayData($users);
    }

    // Méthode pour insérer un message global
    public function insertContactMessage()
    {
        header("Access-Control-Allow-Headers: Accept, Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token, Authorization");
        $formData = json_decode(file_get_contents('php://input'));
        if ($formData === null) {
            $error = "Aucunes données transmises dans le formulaire";
            Model::sendJSON([$error]);
        } else {
            try {
                $result = $this->apiManager->insertContactMessage($formData);
                if ($result === true) {
                    $success = "Votre message a bien été envoyé";
                    Model::sendJSON([$success]);
                } else {
                    $error = "Une erreur est survenue lors de l'envoi de votre message";
                    Model::sendJSON([$error]);
                }
            } catch (Exception $e) {
                http_response_code(HTTP_INTERNAL_SERVER_ERROR);
                $response = ["error" => $e->getMessage()];
                $this->sendJSONResponse($response, HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    public function insertMessageAnnonce()
    {
        header("Access-Control-Allow-Headers: Accept, Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token, Authorization");
        $formData = json_decode(file_get_contents('php://input'));
        if ($formData === null || !isset($formData->Id_CarAnnonce)) {
            http_response_code(400); // Mauvaise requête
            Model::sendJSON(["error" => "Aucune donnée valide transmise dans le formulaire ou l'ID de l'annonce de voiture est manquant"]);
        } else {
            try {
                $Id_CarAnnonce = $formData->Id_CarAnnonce;
                // Utilise l'instance existante de APIManager
                $result = $this->apiManager->insertMessageAnnonce($Id_CarAnnonce, $formData);
                if ($result === true) {
                    http_response_code(200); // OK
                    Model::sendJSON(["success" => "Votre message a bien été envoyé"]);
                } else {
                    $response = ["error" => "Une erreur est survenue lors de l'envoi de votre message"];
                    http_response_code(500); // Erreur Serveur
                    Model::sendJSON($response);
                }
            } catch (Exception $e) {
                http_response_code(HTTP_INTERNAL_SERVER_ERROR);
                $response = ["error" => $e->getMessage()];
                $this->sendJSONResponse($response, HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    public function createAnnonce()
    {
        header("Access-Control-Allow-Headers: Accept, Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token, Authorization");
        $formData = json_decode(file_get_contents('php://input'));
        if ($formData === null) {
            http_response_code(400); // Mauvaise requête
            Model::sendJSON(["error" => "Aucune donnée valide transmise dans le formulaire"]);
        } else {
            try {
                // Utilisation de addAnnonce pour ajouter une nouvelle annonce
                $result = $this->apiManager->createAnnonce($formData);
                if ($result === true) {
                    http_response_code(200); // OK
                    Model::sendJSON(["success" => "L'annonce a bien été ajoutée"]);
                } else {
                    $response = ["error" => "Une erreur est survenue lors de l'ajout de l'annonce"];
                    http_response_code(500); // Erreur Serveur
                    Model::sendJSON($response);
                }
            } catch (Exception $e) {
                http_response_code(HTTP_INTERNAL_SERVER_ERROR);
                $response = ["error" => $e->getMessage()];
                $this->sendJSONResponse($response, HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    public function updateAnnonce($id)
    {
        header("Access-Control-Allow-Headers: Accept, Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token, Authorization");
        $formData = json_decode(file_get_contents('php://input'));
        if ($formData === null) {
            http_response_code(400); // Mauvaise requête
            Model::sendJSON(["error" => "Aucune donnée valide transmise dans le formulaire"]);
        } else {
            try {
                // Utilisation de updateAnnonce pour mettre à jour une annonce par ID
                $result = $this->apiManager->updateAnnonce($id, $formData);
                if ($result === true) {
                    http_response_code(200); // OK
                    Model::sendJSON(["success" => "L'annonce a bien été mise à jour"]);
                } else {
                    $response = ["error" => "Une erreur est survenue lors de la mise à jour de l'annonce"];
                    http_response_code(500); // Erreur Serveur
                    Model::sendJSON($response);
                }
            } catch (Exception $e) {
                http_response_code(HTTP_INTERNAL_SERVER_ERROR);
                $response = ["error" => $e->getMessage()];
                $this->sendJSONResponse($response, HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    public function deleteAnnonce($id)
    {
        // Ajoute les en-têtes CORS nécessaires pour permettre les requêtes cross-origin
        header("Access-Control-Allow-Headers: Accept, Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token, Authorization");
        try {
            // Utilise apiManager pour supprimer une annonce par ID
            $result = $this->apiManager->deleteAnnonce($id);
            // Si la suppression réussit
            if ($result === true) {
                http_response_code(200); // OK
                Model::sendJSON(["success" => "L'annonce a bien été supprimée"]);
            } else {
                // Si la suppression échoue
                $response = ["error" => "Une erreur est survenue lors de la suppression de l'annonce"];
                http_response_code(500); // Erreur Serveur
                Model::sendJSON($response);
            }
        } catch (Exception $e) {
            // Gère les exceptions
            http_response_code(500); // Erreur Serveur
            $response = ["error" => $e->getMessage()];
            Model::sendJSON($response);
        }
    }

    public function updateValidationStatus($annonceId, $newValidity)
    {
        // Journalise l'appel à la fonction pour suivre l'exécution
        error_log("Appel à updateValidationStatus dans APIController");
        // Journalise l'ID de l'annonce et la nouvelle validité pour le suivi
        error_log("Annonce ID: " . $annonceId);
        error_log("New Validity: " . $newValidity);
        // Appelle la méthode de mise à jour dans le gestionnaire (manager)
        $success = $this->apiManager->updateValidationStatus($annonceId, $newValidity);
        // Journalise le succès ou l'échec de la mise à jour
        error_log("Update Success: " . ($success ? "true" : "false"));
        if ($success) {
            // Si la mise à jour a réussi, récupère les données mises à jour après la mise à jour dans la base de données
            $updatedData = $this->apiManager->getAnnonceById($annonceId);
            if ($updatedData) {
                // Si les données mises à jour sont disponibles, retourne une réponse JSON avec les données mises à jour
                http_response_code(200); // OK
                Model::sendJSON($updatedData);
            } else {
                // Gère le cas où les données mises à jour ne sont pas disponibles
                http_response_code(404); // Not Found
                Model::sendJSON(["error" => "Annonce mise à jour introuvable"]);
            }
        } else {
            // SI la mise à jour a échoué
            http_response_code(500); // Internal Server Error
            Model::sendJSON(["error" => "Erreur lors de la mise à jour du statut de validité"]);
        }
    }
}
