<?php

require_once "models/Model.php";

// Fonction étendue et lée à la base de données
// APIManager étendra Model (héritage)
// APIManager appelle la classe Model
class APIManager extends Model
{

    //requête pour getAllAnnonces

    public function getAllAnnonces()
    {
        // Requête pour récupérer toutes les voitures
        $req = "SELECT
            Cars.Id_Cars,
            Cars.mileage,
            Cars.registration,
            Cars.price,
            Cars.description,
            Cars.main_image_url,
            Cars.power,
            Cars.power_unit,
            Cars.color,
            CarAnnonce.Id_CarAnnonce,
            CarAnnonce.title AS annonce_title,
            CarAnnonce.createdAt AS annonce_createdAt,
            CarAnnonce.valid AS annonce_valid,
            Garage.garageName AS annonce_garageName,
            Models.model_name,
            Models.category_model,
            Brands.brand_name,
            Brands.brand_logo_url,
            ManufactureYears.manufacture_year,
            EnergyType.fuel_type,
            GROUP_CONCAT(Options.options_name) AS options_name
        FROM
            Cars
        INNER JOIN Models ON Cars.Id_Cars = Models.Id_Cars
        INNER JOIN Brands ON Models.Id_Brand = Brands.Id_Brand
        INNER JOIN ModelsManufactureYears ON Models.Id_Model = ModelsManufactureYears.Id_Model
        INNER JOIN ManufactureYears ON ModelsManufactureYears.Id_ManufactureYears = ManufactureYears.Id_ManufactureYears
        INNER JOIN CarsEnergy ON Cars.Id_Cars = CarsEnergy.Id_Cars
        INNER JOIN EnergyType ON CarsEnergy.Id_EnergyType = EnergyType.Id_EnergyType
        LEFT JOIN CarAnnonce ON Cars.Id_CarAnnonce = CarAnnonce.Id_CarAnnonce
        LEFT JOIN Garage ON CarAnnonce.Id_Garage = Garage.Id_Garage
        LEFT JOIN CarsOptions ON Cars.Id_Cars = CarsOptions.Id_Cars
        LEFT JOIN Options ON CarsOptions.Id_Options = Options.Id_Options
        GROUP BY
            Cars.Id_Cars,
            Cars.mileage,
            Cars.registration,
            Cars.price,
            Cars.description,
            Cars.main_image_url,
            Cars.power,
            Cars.power_unit,
            Cars.color,
            CarAnnonce.Id_CarAnnonce,
            CarAnnonce.title,
            CarAnnonce.createdAt,
            Garage.garageName,
            Models.model_name,
            Models.category_model,
            Brands.brand_name,
            Brands.brand_logo_url,
            ManufactureYears.manufacture_year,
            EnergyType.fuel_type
        ";

        try {
            $stmt = $this->getBdd()->prepare($req);
            $stmt->execute();
            $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            return $cars;
        } catch (PDOException $e) {
            error_log("PDO Error in getAllAnnonces: " . $e->getMessage());
            return [];
        }
    }



    public function getAnnonceByID($id)
    {
        //requête pour AnnoncesByID
        $req = "SELECT
        Cars.Id_Cars,
        Cars.mileage,
        Cars.registration,
        Cars.price,
        Cars.description,
        Cars.main_image_url,
        CarAnnonce.Id_CarAnnonce,
        CarAnnonce.title AS annonce_title,
        CarAnnonce.createdAt AS annonce_createdAt,
        CarAnnonce.valid AS annonce_valid,
        Garage.garageName AS annonce_garageName,
        Cars.power,
        Cars.power_unit,
        Cars.color,
        Models.model_name,
        Models.category_model,
        Brands.brand_name,
        Brands.brand_logo_url,
        ManufactureYears.manufacture_year,
        EnergyType.fuel_type,
        GROUP_CONCAT(Options.options_name) AS options_name
    FROM
        Cars
    INNER JOIN Models ON Cars.Id_Cars = Models.Id_Cars
    INNER JOIN Brands ON Models.Id_Brand = Brands.Id_Brand
    INNER JOIN ModelsManufactureYears ON Models.Id_Model = ModelsManufactureYears.Id_Model
    INNER JOIN ManufactureYears ON ModelsManufactureYears.Id_ManufactureYears = ManufactureYears.Id_ManufactureYears
    INNER JOIN CarsEnergy ON Cars.Id_Cars = CarsEnergy.Id_Cars
    INNER JOIN EnergyType ON CarsEnergy.Id_EnergyType = EnergyType.Id_EnergyType
    LEFT JOIN CarAnnonce ON Cars.Id_CarAnnonce = CarAnnonce.Id_CarAnnonce
    LEFT JOIN Garage ON CarAnnonce.Id_Garage = Garage.Id_Garage
    LEFT JOIN CarsOptions ON Cars.Id_Cars = CarsOptions.Id_Cars
    LEFT JOIN Options ON CarsOptions.Id_Options = Options.Id_Options
    WHERE CarAnnonce.Id_CarAnnonce = :id
        GROUP BY
        Cars.Id_Cars,
        Cars.mileage,
        Cars.registration,
        Cars.price,
        Cars.description,
        Cars.main_image_url,
        CarAnnonce.Id_CarAnnonce,
        CarAnnonce.title,
        CarAnnonce.createdAt,
        Garage.garageName,
        Cars.power,
        Cars.power_unit,
        Cars.color,
        Models.model_name,
        Models.category_model,
        Brands.brand_name,
        Brands.brand_logo_url,
        ManufactureYears.manufacture_year,
        EnergyType.fuel_type
    ";

        try {
            // Je prépare la requête dans le statement
            $stmt = $this->getBdd()->prepare($req);
            // Je lie le paramètre :id à la valeur de $id
            $id = filter_var($id, FILTER_VALIDATE_INT);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            // Je lance son exécution
            $stmt->execute();
            // Je récupère les données
            $car = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // Je ferme le curseur pour finir la requête et la connexion à la DB
            $stmt->closeCursor();
            // Je retourne les données au controller
            return $car;
        } catch (PDOException $e) {
            // Je gère l'exception en loggant l'erreur
            error_log("Error in getAnnonceByID: " . $e->getMessage());
            // Je renvoie une réponse adaptée à l'erreur
            return [];
        }
    }


    public function getDBModels()
    {
        //requête pour Models
        $req = "SELECT * FROM Models";

        //je prépare la requête dans le statement
        $stmt = $this->getBdd()->prepare($req);
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
        try {
            $stmt = $this->getBdd()->prepare($req);
            $stmt->execute();
            $datas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            return $datas;
        } catch (PDOException $e) {
            // Loguer l'erreur
            error_log("PDO Error in executeAndFetchAll: " . $e->getMessage());
            return [];
        }
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

    public function getDBOptions()
    {
        $req = "SELECT * FROM Options";
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

    public function getDBCars()
    {
        $req = "SELECT * FROM Cars";
        return $this->executeAndFetchAll($req);
    }

    public function getDBMessage()
    {
        $req = "SELECT * FROM MessageAnnonce";
        return $this->executeAndFetchAll($req);
    }

    public function getDBResetPassword()
    {
        $req = "SELECT * FROM ResetPassword";
        return $this->executeAndFetchAll($req);
    }

    public function getDBUsers()
    {
        $req = "SELECT * FROM Users";
        return $this->executeAndFetchAll($req);
    }
}
