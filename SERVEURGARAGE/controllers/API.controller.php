<?php
require_once "models/API.manager.php";
require_once "models/Model.php";

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
        echo json_encode($data);
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
                $response = ["error" => $e->getMessage()];
                http_response_code(500);
                $this->sendJSONResponse($response, 200);
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
            echo json_encode([$error]);
        } else {
            try {
                $result = $this->apiManager->insertContactMessage($formData);
                if ($result === true) {
                    $success = "Votre message a bien été envoyé";
                    echo json_encode([$success]);
                } else {
                    $error = "Une erreur est survenue lors de l'envoi de votre message";
                    echo json_encode([$error]);
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
                echo json_encode([$error]);
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
                $response = ["error" => $e->getMessage()];
                http_response_code(500); // Erreur Serveur
                $this->sendJSONResponse($response, 200);
            }
        }
    }
}
