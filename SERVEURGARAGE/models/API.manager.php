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
    private function executeAndFetchAll($req, $params = [])
    {
        try {
            $stmt = $this->getBdd()->prepare($req);
            $stmt->execute($params);
            $datas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            return $datas;
        } catch (PDOException $e) {
            // Loguer l'erreur
            error_log("PDO Error in executeAndFetchAll: " . $e->getMessage());
            return [];
        }
    }

    public function getDBAllMessageAnnonce()
    {
        try {
            // Requête pour récupérer tous les messages avec leurs IDs associés
            $query = "SELECT * FROM MessageAnnonce";
            // Exécute la requête avec la méthode executeAndFetchAll()
            $messages = $this->executeAndFetchAll($query);
            // Retourne les messages au format JSON
            return $messages;
        } catch (Exception $e) {
            // Gère les erreurs
            $error = ['error' => $e->getMessage()];
            return $error;
        }
    }

    public function insertMessageAnnonce($formData)
    {
        // Je prépare la requête SQL pour insérer les données dans la base de données
        $sql = "INSERT INTO MessageAnnonce (userName, userEmail, userPhone, message, createdAt, Id_Users, Id_CarAnnonce) VALUES (?, ?, ?, ?, ?, ?, ?)";
        try {
            // Je prépare la requête SQL
            $stmt = $this->getBdd()->prepare($sql);
            // Je lie les valeurs aux paramètres de la requête
            $stmt->bindValue(1, $formData->userName);
            $stmt->bindValue(2, $formData->userEmail);
            $stmt->bindValue(3, $formData->userPhone);
            $stmt->bindValue(4, $formData->message);
            $stmt->bindValue(5, $formData->createdAt);
            // Si Id_Users est null, je lie NULL à la colonne correspondante dans la base de données
            if ($formData->Id_Users === null) {
                $stmt->bindValue(6, null, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(6, $formData->Id_Users);
            }
            $stmt->bindValue(7, $formData->Id_CarAnnonce);
            // J'exécute la requête
            $stmt->execute();
            // Je vérifie si l'insertion a réussi en vérifiant le nombre de lignes affectées
            if ($stmt->rowCount() > 0) {
                // Je retourne true si l'insertion est réussie
                return true;
            } else {
                // Je retourne false si l'insertion a échoué
                return false;
            }
        } catch (PDOException $e) {
            // Je gère les erreurs éventuelles lors de l'exécution de la requête
            error_log("Error in insertMessageAnnonce: " . $e->getMessage());
            return false;
        }
    }

    // Mise en place d'une méthode commune pour exécuter la requête et récupérer toutes les données sans redondance (DRY) 
    public function insertContactMessage($formData)
    {
        $req = "INSERT INTO ContactMessage (name, email, phone, message, created_at) VALUES (?, ?, ?, ?, ?)";
        try {
            $stmt = $this->getBdd()->prepare($req);
            $stmt->execute([
                $formData->name,
                $formData->email,
                $formData->phone,
                $formData->message,
                $formData->createdAt
            ]);
            return true;
        } catch (PDOException $e) {
            error_log("Error in insertContactMessage: " . $e->getMessage());
            // Retourner false si l'insertion a échoué
            return false;
        }
    }

    public function getDBContactMessage()
    {
        $req = "SELECT * FROM ContactMessage";
        return $this->executeAndFetchAll($req);
    }

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
