<?php
require_once "models/front/API.manager.php";

// méthodes
class APIController
{
    private $apiManager;

    public function __construct()
    {
        //J'instancie l'apiManager
        $this->apiManager = new APIManager();
    }



    public function getCars()
    {
        // je récupère toutes les informations via APIManager avec la fonction getDBCars
        $cars = $this->apiManager->getDBCars();
        echo "<pre>";
        print_r($cars);
        echo "</pre>";
    }

    public function getModels($idModels)
    { // je récupère toutes les informations via APIManager avec la fonction getDBModels
        $models = $this->apiManager->getDBModels($idModels);
        echo "<pre>";
        print_r($models);
        echo "</pre>";
    }

    public function getBrands()
    { // je récupère toutes les informations via APIManager avec la fonction getDBBrands
        $brands = $this->apiManager->getDBBrands();
        echo "<pre>";
        print_r($brands);
        echo "</pre>";
    }

    public function getGarage()
    {
        $garage = $this->apiManager->getDBGarage();
        echo "<pre>";
        print_r($garage);
        echo "</pre>";
    }

    // Mise en place d'une méthode commune pour l'affichage des données sans redondance (DRY)
    private function displayData($datas)
    {
        echo "<pre>";
        print_r($datas);
        echo "</pre>";
    }
    // Mise en place d'une méthode commune pour l'affichage des données sans redondance (DRY)
    public function getImages()
    {
        $images = $this->apiManager->getDBImages();
        $this->displayData($images);
    }

    public function getTestimonials()
    {
        $testimonials = $this->apiManager->getDBTestimonials();
        $this->displayData($testimonials);
    }

    public function getOpening()
    {
        $opening = $this->apiManager->getDBOpening();
        $this->displayData($opening);
    }

    public function getGarageServices()
    {
        $services = $this->apiManager->getDBGarageServices();
        $this->displayData($services);
    }

    public function getOptions($idOptions)
    {
        echo "Données JSON sur les options" . $idOptions . "demandées";
    }

    public function getManufactureYears($idManufactureYears)
    {
        echo "Données JSON sur les années de fabrication" . $idManufactureYears . "demandées";
    }

    public function getEnergyType($idEnergytype)
    {
        echo "Données JSON sur les energies utilisées" . $idEnergytype . "demandées";
    }

    public function getCarAnnonce($idCarAnnonce)
    {
        echo "Données JSON sur les annonces" . $idCarAnnonce . "demandées";
    }

    public function getUsers()
    {
        $users = $this->apiManager->getDBUsers();
        $this->displayData($users);
    }
}
