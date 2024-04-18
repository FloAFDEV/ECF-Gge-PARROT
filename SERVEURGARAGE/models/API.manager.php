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
    {  // Vérification des champs requis
        if (empty($formData['userName']) || empty($formData['userEmail']) || empty($formData['userPhone']) || empty($formData['message'])) {
            return false; // Les champs requis ne doivent pas être vides
        }
        // Validation de l'e-mail
        if (!filter_var($formData['userEmail'], FILTER_VALIDATE_EMAIL)) {
            return false; // L'e-mail doit être dans un format valide
        }
        // Validation du numéro de téléphone
        if (!preg_match("/^(0|\+33)[1-9][0-9]{8}$/", $formData['userPhone'])) {
            return false; // Le numéro de téléphone doit être dans un format valide
        }
        // Requête SQL paramétrée pour insérer les données dans la table MessageAnnonce
        $sql = "INSERT INTO MessageAnnonce (userName, userEmail, userPhone, message, createdAt, Id_Users, Id_CarAnnonce) VALUES (?, ?, ?, ?, ?, ?, ?)";
        try {
            // Préparation de la requête SQL
            $stmt = $this->getBdd()->prepare($sql);
            // Exécution de la requête avec les valeurs des champs
            $stmt->execute([
                $formData['userName'],
                $formData['userEmail'],
                $formData['userPhone'],
                $formData['message'],
                $formData['createdAt'],
                // Si Id_Users est null, valeur null dans la base de données
                $formData['Id_Users'] === null ? null : $formData['Id_Users'],
                $formData['Id_CarAnnonce']
            ]);
            // Retourne true si l'insertion est réussie
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            // Gestion des erreurs et enregistrement dans le journal des erreurs
            error_log("Error in insertMessageAnnonce: " . $e->getMessage());
            // Retourne false si l'insertion a échoué
            return false;
        }
    }



    public function insertContactMessage($formData)
    {
        $req = "INSERT INTO ContactMessage (name, email, phone, message) VALUES (?, ?, ?, ?)";
        try {
            $stmt = $this->getBdd()->prepare($req);

            // Valide et échappe les données
            $name = substr($formData->name, 0, 255); // Limiterla longueur du champ à 255 caractères
            $email = filter_var($formData->email, FILTER_VALIDATE_EMAIL); // Validation de l'e-mail
            $phone = preg_replace("/[^0-9]/", "", $formData->phone); // Supprime les caractères non numériques
            $message = substr($formData->message, 0, 1000); // Limite la longueur du champ à 1000 caractères

            // Vérification de la validité de l'e-mail
            if (!$email) {
                return false; // Retourne false si l'e-mail est invalide
            }

            // Exécute de la requête avec les valeurs des champs
            $stmt->execute([$name, $email, $phone, $message]);
            // Vérification du nombre de lignes affectées pour s'assurer que l'insertion s'est bien déroulée
            if ($stmt->rowCount() > 0) {
                return true; // Retourne true si l'insertion a réussi
            } else {
                return false; // Retourne false si aucune ligne n'a été insérée
            }
        } catch (PDOException $e) {
            error_log("Error in insertContactMessage: " . $e->getMessage());
            // Retourne false si une exception PDO est levée
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

    public function insertTestimonial($TestimonialformData)
    {
        // Requête SQL paramétrée pour insérer les données dans la table Testimonials
        $sql = "INSERT INTO Testimonials (pseudo, userEmail, message, createdAt, note, Id_Users) VALUES (?, ?, ?, ?, ?, ?)";
        try {
            // Conversion de la note en nombre flottant
            $note = floatval($TestimonialformData['note']);   // Préparation de la requête SQL
            $stmt = $this->getBdd()->prepare($sql);
            // Validation et échappement des données
            $pseudo = substr($TestimonialformData['pseudo'], 0, 255); // Limiter la longueur du champ à 255 caractères
            $email = filter_var($TestimonialformData['userEmail'], FILTER_VALIDATE_EMAIL); // Validation de l'e-mail
            $message = substr($TestimonialformData['message'], 0, 1000); // Limiter la longueur du champ à 1000 caractères
            // Vérification de la validité de l'e-mail
            if (!$email) {
                return false; // Retourne false si l'e-mail est invalide
            }
            // Exécution de la requête avec les valeurs des champs
            $stmt->execute([
                htmlspecialchars($pseudo),
                htmlspecialchars($email),
                htmlspecialchars($message),
                htmlspecialchars($TestimonialformData['createdAt']),
                $note,
                isset($TestimonialformData['userId']) ? htmlspecialchars($TestimonialformData['userId']) : null, // Si userId est null, utilisez la valeur null dans la base de données
            ]);
            // Retourne true si l'insertion est réussie
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            // Gestion des erreurs et enregistrement dans le journal des erreurs
            error_log("Error in insertTestimonials: " . $e->getMessage());
            // Retourne false si l'insertion a échoué
            return false;
        }
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

    public static function getUserByEmail($email)
    {
        $req = "SELECT * FROM Users WHERE email = :email";
        $params = array(':email' => $email);
        $apiManager = new self(); // Créer une instance de APIManager
        return $apiManager->executeAndFetchAll($req, $params);
    }

    // Méthode pour créer un nouvel utilisateur
    public function createUser($email, $name, $phone, $pseudo, $role, $passwordHash, $primaryGarage_Id, $Id_Garage)
    {
        // Préparez votre requête SQL pour insérer un nouvel utilisateur
        $req = "INSERT INTO Users (email, name, phone, pseudo, role, password_hash, primaryGarage_Id, Id_Garage) 
            VALUES (:email, :name, :phone, :pseudo, :role, :passwordHash, :primaryGarage_Id, :Id_Garage)";
        // Préparez les paramètres
        $params = array(
            ':email' => $email,
            ':name' => $name,
            ':phone' => $phone,
            ':pseudo' => $pseudo,
            ':role' => $role,
            ':passwordHash' => $passwordHash,
            ':primaryGarage_Id' => $primaryGarage_Id,
            ':Id_Garage' => $Id_Garage
        );
        // Exécutez la requête et retournez le résultat
        return $this->executeAndFetchAll($req, $params);
    }
}
