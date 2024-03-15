<?php
require_once "models/API.manager.php";
require_once "models/Model.php";

// méthodes
class APIController
{
    private $apiManager;

    public function __construct()
    {
        //J'instancie l'apiManager
        $this->apiManager = new APIManager();
    }

    // Mise en place d'une méthode commune pour l'affichage des données sans redondance (DRY)
    // private function displayData($datas)
    // {
    //     echo "<pre>";
    //     print_r($datas);
    //     echo "</pre>";
    // }

    public function getAllAnnonces($idAnnonces = null)
    {
        // Récupération des informations via APIManager avec la fonction getAnnonces
        $linesAnnonces = ($idAnnonces !== null) ? $this->apiManager->getAllAnnonces($idAnnonces) : $this->apiManager->getAllAnnonces();
        Model::sendJSON(($linesAnnonces));
        // $this->displayData($cars);
    }
    public function getAnnonceByID($id)
    {
        // Récupération des informations via APIManager avec la fonction getAnnonces
        $annonce = $this->apiManager->getAnnonceByID($id);
        Model::sendJSON(($annonce));
        // $this->displayData($annonce);
    }

    // private function formatDataLinesModels($lines)
    // {
    //     $formatted_data = [];

    //     foreach ($lines as $line) {
    //         // Génére les clés pour l'annonce en fonction des identifiants uniques
    //         $annonce_key_id = "Voiture n° " . $line["Id_Cars"];
    //         $annonce_key_title = "Titre annonce " . $line["annonce_title"];

    //         // Vérifie si la clé d'annonce existe déjà dans les données formatées
    //         if (
    //             !array_key_exists($annonce_key_id, $formatted_data) ||
    //             !array_key_exists($annonce_key_title, $formatted_data[$annonce_key_id]["info_annonce"])
    //         ) {
    //             // Sinon, crée une nouvelle entrée avec les informations du garage, de l'annonce, de la voiture et des options
    //             $formatted_data[$annonce_key_id] = [
    //                 "info_annonce" => [
    //                     $annonce_key_title => [
    //                         "nom du garage" => $line["annonce_garageName"],
    //                         "titre de l'annonce" => $line["annonce_title"],
    //                         "Id_Car" => $line["Id_Cars"],
    //                         "date_creation_annonce" => $line["annonce_createdAt"],
    //                         "validation_annonce" => $line["annonce_valid"] ?? null,
    //                     ],
    //                 ],
    //                 "info_voiture" => [
    //                     "Id_CarAnnonce" => $line["Id_CarAnnonce"],
    //                     "modele" => $line["brand_name"] . ' ' . $line["model_name"],
    //                     "annee_fabrication" => $line["manufacture_year"],
    //                     "type_carburant" => $line["fuel_type"],
    //                     "kilometrage" => $line["mileage"] . ' km',
    //                     "categorie" => $line["category_model"],
    //                     "description" => $line["description"],
    //                     "image_principale" => $line["main_image_url"],
    //                     "puissance" => $line["power"] . ' ' . $line["power_unit"],
    //                     "couleur" => $line["color"],
    //                     "prix" => number_format($line["price"], 0, '', '') . ' €', // Formaterle prix sans décimales
    //                     "options" => [], // Initialise le tableau des options
    //                 ],
    //             ];
    //         }
    //         // Vérifie si des options existent avant de les utiliser
    //         if (isset($line["options_name"]) && $line["options_name"] !== null) {
    //             // Ajoute chaque option à la liste des options pour cette voiture
    //             $options = explode(',', $line["options_name"]);
    //             // Ajoute les options à la liste existante sans vérifier les doublons
    //             $formatted_data[$annonce_key_id]["info_voiture"]["options"] = array_merge(
    //                 $formatted_data[$annonce_key_id]["info_voiture"]["options"],
    //                 $options
    //             );
    //             // Supprime les doublons
    //             $formatted_data[$annonce_key_id]["info_voiture"]["options"] = array_unique($formatted_data[$annonce_key_id]["info_voiture"]["options"]);
    //         }
    //     }
    //     return $formatted_data;
    // }



    public function getModels()
    { // Récupération des informations via APIManager avec la fonction getModels
        $models = $this->apiManager->getDBModels();
        Model::sendJSON($models);
        // $this->displayData($models);
    }

    public function getBrands()
    { // Récupération des informations via APIManager avec la fonction getBrands
        $brands = $this->apiManager->getDBBrands();
        Model::sendJSON($brands);
        // $this->displayData($brands);
    }

    public function getGarage()
    { // Récupération des informations via APIManager avec la fonction getGarage
        $garage = $this->apiManager->getDBGarage();
        Model::sendJSON($garage);
        // $this->displayData($garage);
    }

    public function getImages()
    { // Récupération des informations via APIManager avec la fonction getImages
        $images = $this->apiManager->getDBImages();
        Model::sendJSON($images);
        // $this->displayData($images);
    }

    public function getTestimonials()
    { // Récupération des informations via APIManager avec la fonction getTestimonials
        $testimonials = $this->apiManager->getDBTestimonials();
        Model::sendJSON($testimonials);
        // $this->displayData($testimonials);
    }

    public function getOpening()
    { // Récupération des informations via APIManager avec la fonction getOpening
        $opening = $this->apiManager->getDBOpening();
        Model::sendJSON($opening);
        // $this->displayData($opening);
    }

    public function getGarageServices()
    { // Récupération des informations via APIManager avec la fonction getDBGarageServices
        $services = $this->apiManager->getDBGarageServices();
        Model::sendJSON($services);
        // $this->displayData($services);
    }

    public function getOptions()
    { // Récupération des informations via APIManager avec la fonction getOptions
        $options = $this->apiManager->getDBOptions();
        Model::sendJSON($options);
        // $this->displayData($idoptions);
    }

    public function getManufactureYears()
    { // Récupération des informations via APIManager avec la fonction getManufactureYears
        $manufactureYears = $this->apiManager->getDBManufactureYears();
        Model::sendJSON($manufactureYears);
        // $this->displayData($manufactureYears);
    }

    public function getEnergyType()
    { // Récupération des informations via APIManager avec la fonction getEnergyType
        $energy = $this->apiManager->getDBEnergyType();
        Model::sendJSON($energy);
        // $this->displayData($energyType);
    }

    public function getCars()
    { // Récupération des informations via APIManager avec la fonction getCarAnnonce
        $annonce = $this->apiManager->getDBCars();
        Model::sendJSON($annonce);
        // $this->displayData($carAnnonce);
    }

    public function getMessage()
    { // Récupération des informations via APIManager avec la fonction getMessage
        $message = $this->apiManager->getDBMessage();
        Model::sendJSON($message);
        // $this->displayData($message);
    }

    public function getResetPassword()
    { // Récupération des informations via APIManager avec la fonction getResetPassword
        $password = $this->apiManager->getDBResetPassword();
        Model::sendJSON($password);
        // $this->displayData($resetPassword);
    }

    public function getUsers()
    {
        $users = $this->apiManager->getDBUsers();
        Model::sendJSON($users);
        // $this->displayData($users);
    }

    public function ContactMessage()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Accept, Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token, Authorization");

        $obj = json_decode(file_get_contents('php://input'));


        $messageBack = [
            "from" => $obj->email,
            "to" => 'contact@vparrot.fr',
        ];

        echo json_encode($messageBack);
    }
}
