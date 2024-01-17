<?php
class APIController
{
    public function getBrands()
    {
        echo "Envoi des informations sur les marques";
    }
    public function getCars($idCars)
    {
        echo "Données JSON sur la voiture" . $idCars . "demandée";
    }
    public function getModels($idModels)
    {
        echo "Données JSON sur les modèles" . $idModels . "demandée";
    }
    public function getGarage($idGarage)
    {
        echo "Données JSON sur le garage" . $idGarage . "demandée";
    }
    public function getImages($idImages)
    {
        echo "Données JSON sur les images" . $idImages . "demandée";
    }
    public function getTestimonials($idTestimonials)
    {
        echo "Données JSON sur les témoignages" . $idTestimonials . "demandée";
    }
    public function getOpening($idOpening)
    {
        echo "Données JSON sur les horaires" . $idOpening . "demandée";
    }
    public function getGarageServices($idGarageServices)
    {
        echo "Données JSON sur les services" . $idGarageServices . "demandée";
    }
    public function getOptions($idOptions)
    {
        echo "Données JSON sur les options" . $idOptions . "demandée";
    }
    public function getManufactureYears($idManufactureYears)
    {
        echo "Données JSON sur les années de fabrication" . $idManufactureYears . "demandée";
    }
    public function getEnergyType($idEnergytype)
    {
        echo "Données JSON sur les energies utilisées" . $idEnergytype . "demandée";
    }
    public function getCarAnnonce($idCarAnnonce)
    {
        echo "Données JSON sur les annonces" . $idCarAnnonce . "demandée";
    }
}
