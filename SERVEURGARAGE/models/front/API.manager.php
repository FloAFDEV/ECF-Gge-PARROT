<?php

require_once "models/Model.php";

// Fonction étendue et lée à la base de données
// APIManager étendra Model (héritage)
// APIManager appelle la classe Model
class APIManager extends Model
{
    public function getDBCars()
    {
        //requête pour Cars
        $req = "SELECT
        Cars.*,
        Models.model_name,
        Models.category_model,
        Brands.brand_name,
        Brands.brand_logo_url,
        ManufactureYears.manufacture_year,
        EnergyType.fuel_type,
        CarAnnonce.title AS annonce_title,
        CarAnnonce.createdAt AS annonce_createdAt,
        Garage.garageName AS annonce_garageName
    FROM
        Cars
    INNER JOIN Models ON Cars.Id_Cars = Models.Id_Cars
    INNER JOIN Brands ON Models.Id_Brand = Brands.Id_Brand
    INNER JOIN ModelsManufactureYears ON Models.Id_Model = ModelsManufactureYears.Id_Model
    INNER JOIN ManufactureYears ON ModelsManufactureYears.Id_ManufactureYears = ManufactureYears.Id_ManufactureYears
    INNER JOIN CarsEnergy ON Cars.Id_Cars = CarsEnergy.Id_Cars
    INNER JOIN EnergyType ON CarsEnergy.Id_EnergyType = EnergyType.Id_EnergyType
    LEFT JOIN CarAnnonce ON Cars.Id_CarAnnonce = CarAnnonce.Id_CarAnnonce
    LEFT JOIN Garage ON CarAnnonce.Id_Garage = Garage.Id_Garage";

        //je prépare la requête dans le statement
        $stmt = $this->getBdd()->prepare($req);
        // Je lance son execution
        $stmt->execute();
        // Je récupère les données
        $Cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Je ferme le cursor pour finir la requete et la connexion à la DB
        $stmt->closeCursor();
        // Je retourne les données au controller
        return $Cars;
    }

    public function getDBModels($idModels)
    {
        //requête pour Models
        $req = "SELECT
        Models.*,
        Brands.brand_name,
        Brands.brand_logo_url,
        Models.model_name,
        Models.category_model,
        ManufactureYears.manufacture_year
    FROM
        Models
        INNER JOIN Brands ON Models.Id_Brand = Brands.Id_Brand
        INNER JOIN ModelsManufactureYears ON Models.Id_Model = ModelsManufactureYears.Id_Model
        INNER JOIN ManufactureYears ON ModelsManufactureYears.Id_ManufactureYears = ManufactureYears.Id_ManufactureYears
        WHERE Models.Id_Model = :idModels";

        //je prépare la requête dans le statement
        $stmt = $this->getBdd()->prepare($req);
        // Je type les données avec PDO::PARAM_INT
        $stmt->bindValue(":idModels", $idModels, PDO::PARAM_INT);
        // Je lance son execution
        $stmt->execute();
        // Je récupère les données
        $Models = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Je ferme le cursor pour finir la requete et la connexion à la DB
        $stmt->closeCursor();
        // Je retourne les données au controller
        return $Models;
    }

    public function getDBBrands()
    {
        //requête pour Brands
        $req = "SELECT *
        FROM Brands";
        //Je prépare ma requête
        $stmt = $this->getBdd()->prepare($req);
        // Je lance son execution
        $stmt->execute();
        // Je récupère les données
        $brands = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Je ferme le cursor pour finir la requete et la connexion à la DB
        $stmt->closeCursor();
        // Je retourne les données au controller
        return $brands;
    }

    public function getDBGarage()
    {
        //requête pour Garage
        $req = "SELECT *
        FROM Garage";

        //Je prépare ma requête
        $stmt = $this->getBdd()->prepare($req);
        // Je lance son execution
        $stmt->execute();
        // Je récupère les données
        $garage = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Je ferme le cursor pour finir la requete et la connexion à la DB
        $stmt->closeCursor();
        // Je retourne les données au controller
        return $garage;
    }

    // Mise en place d'une méthode commune pour exécuter la requête et récupérer toutes les données sans redondance (DRY) 
    private function executeAndFetchAll($req)
    {
        $stmt = $this->getBdd()->prepare($req);
        $stmt->execute();
        $datas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $datas;
    }
    // Mise en place d'une méthode commune pour exécuter la requête et récupérer toutes les données sans redondance (DRY) 

    public function getDBImages()
    {
        $req = "SELECT * FROM Images";
        return $this->executeAndFetchAll($req);
    }

    public function getDBTestimonials()
    {
        $req = "SELECT * FROM Testimonials";
        return $this->executeAndFetchAll($req);
    }

    public function getDBOpening()
    {
        $req = "SELECT * FROM Opening";
        return $this->executeAndFetchAll($req);
    }

    public function getDBGarageServices()
    {
        $req = "SELECT * FROM GarageServices";
        return $this->executeAndFetchAll($req);
    }

    public function getDBManufactureYears()
    {
        $req = "SELECT * FROM ManufactureYears";
        return $this->executeAndFetchAll($req);
    }

    public function getDBEnergyType()
    {
        $req = "SELECT * FROM EnergyType";
        return $this->executeAndFetchAll($req);
    }

    public function getDBidCarAnnonce()
    {
        $req = "SELECT * FROM CarAnnonce";
        return $this->executeAndFetchAll($req);
    }

    public function getDBUsers()
    {
        $req = "SELECT * FROM Users";
        return $this->executeAndFetchAll($req);
    }
}
