<?php
require_once "models/front/API.manager.php";
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
    private function displayData($datas)
    {
        echo "<pre>";
        print_r($datas);
        echo "</pre>";
    }

    public function getCars()
    {
        // je récupère toutes les informations via APIManager avec la fonction getDBCars
        $cars = $this->apiManager->getDBCars();
        $this->displayData($cars);
    }

    public function getModels($idModels)
    { // je récupère toutes les informations via APIManager avec la fonction getDBModels
        $models = $this->apiManager->getDBModels($idModels);
        $this->displayData($models);
    }

    public function getBrands()
    { // je récupère toutes les informations via APIManager avec la fonction getDBBrands
        $brands = $this->apiManager->getDBBrands();
        $this->displayData($brands);
    }

    public function getGarage()
    { // je récupère toutes les informations via APIManager avec la fonction getGarage
        $garage = $this->apiManager->getDBGarage();
        $this->displayData($garage);
    }

    public function getImages()
    { // je récupère toutes les informations via APIManager avec la fonction getImages
        $images = $this->apiManager->getDBImages();
        $this->displayData($images);
    }

    public function getTestimonials()
    { // je récupère toutes les informations via APIManager avec la fonction getTestimonials
        $testimonials = $this->apiManager->getDBTestimonials();
        $this->displayData($testimonials);
    }

    public function getOpening()
    { // je récupère toutes les informations via APIManager avec la fonction getDBOpening
        $opening = $this->apiManager->getDBOpening();
        $this->displayData($opening);
    }

    public function getGarageServices()
    { // je récupère toutes les informations via APIManager avec la fonction getDBGarageServices
        $services = $this->apiManager->getDBGarageServices();
        $this->displayData($services);
    }

    public function getOptions()
    { // je récupère toutes les informations via APIManager avec la fonction getDBOptions
        $idoptions = $this->apiManager->getDBOptions();
        $this->displayData($idoptions);
    }

    public function getManufactureYears()
    { // je récupère toutes les informations via APIManager avec la fonction getDBManufactureYears
        $manufactureYears = $this->apiManager->getDBManufactureYears();
        $this->displayData($manufactureYears);
    }

    public function getEnergyType()
    { // je récupère toutes les informations via APIManager avec la fonction getDBEnergyType
        $energyType = $this->apiManager->getDBEnergyType();
        $this->displayData($energyType);
    }

    public function getCarAnnonce()
    { // je récupère toutes les informations via APIManager avec la fonction getDBCarAnnonce
        $carAnnonce = $this->apiManager->getDBidCarAnnonce();
        $this->displayData($carAnnonce);
    }

    public function getMessage()
    { // je récupère toutes les informations via APIManager avec la fonction getDBMessage
        $message = $this->apiManager->getDBMessage();
        $this->displayData($message);
    }

    public function getResetPassword()
    { // je récupère toutes les informations via APIManager avec la fonction getDBResetPassword
        $resetPassword = $this->apiManager->getDBResetPassword();
        $this->displayData($resetPassword);
    }

    public function getUsers()
    {
        $users = $this->apiManager->getDBUsers();
        $this->displayData($users);
    }
}
