<?php

require_once("Model.php");


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

    public function deleteAnnonce($annonceId)
    {
        try {
            // Préparer la requête de suppression
            $stmt = $this->getBdd()->prepare("DELETE FROM CarAnnonce WHERE Id_CarAnnonce = :annonceId");
            // Binder le paramètre :testimonialId avec la valeur de $testimonialId
            $stmt->bindParam(':annonceId', $annonceId, PDO::PARAM_INT);
            // Exécuter la requête de suppression
            $result = $stmt->execute();
            var_dump($result);
            // Vérifier si la suppression a réussi
            if ($result) {
                error_log("ApiManager::deleteAnnonce - Annonce $annonceId supprimé avec succès");
                return true;
            } else {
                error_log("ApiManager::deleteAnnonce - Échec de la suppression de l'annonce $annonceId");
                return false;
            }
            // Retourne le résultat de l'exécution (true ou false)
            return $result;
        } catch (PDOException $e) {
            // Gérer les erreurs PDO
            error_log("ApiManager::deleteAnnonce - Erreur PDO: " . $e->getMessage());
            var_dump($e->getMessage());
            return false;
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
            var_dump($stmt);
            // Exécution de la requête avec les valeurs des champs
            $stmt->execute([
                htmlspecialchars($pseudo),
                htmlspecialchars($email),
                htmlspecialchars($message),
                htmlspecialchars($TestimonialformData['createdAt']),
                $note,
                isset($TestimonialformData['userId']) ? htmlspecialchars($TestimonialformData['userId']) : null, // Si userId est null =>valeur null dans la base de données
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
        try {
            // Exécute la requête SQL et retourne tous les résultats
            return $this->executeAndFetchAll($req);
        } catch (PDOException $e) {
            // En cas d'erreur, enregistre l'erreur et lance une exception
            error_log("Error in getDBTestimonials: " . $e->getMessage());
            throw new Exception("Failed to fetch testimonials from database.");
        }
    }

    public function getDBValidTestimonials()
    {
        $req = "SELECT * FROM Testimonials WHERE valid = 1";
        return $this->executeAndFetchAll($req);
    }



    public function getDBTestimonialId($testimonialId)

    {
        $req = "SELECT * FROM Testimonials WHERE Id_Testimonials = :testimonialId";
        // var_dump($testimonialId);
        try {
            // Je prépare la requête dans le statement
            $stmt = $this->getBdd()->prepare($req);
            // Je lie le paramètre :id à la valeur de $id
            $stmt->bindValue(":testimonialId", $testimonialId, PDO::PARAM_INT);
            // Je lance son exécution
            $stmt->execute();
            var_dump($stmt);
            // Je récupère les données
            $car = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // Je ferme le curseur pour finir la requête et la connexion à la DB
            $stmt->closeCursor();
            // Je retourne les données au controller
            return $car;
        } catch (PDOException $e) {
            // Je gère l'exception en loggant l'erreur
            error_log("Error in Id: " . $e->getMessage());
            // Je renvoie une réponse adaptée à l'erreur
            return "réussi";
        }
    }

    public function updateTestimonialValidation($testimonialId, $newValidity)
    {
        // Prépare la requête SQL pour mettre à jour le statut de validation dans la table CarAnnonce
        $query = "UPDATE Testimonials SET valid = :status WHERE Id_Testimonials = :id";
        try {
            // Prépare la requête SQL
            $valid = 0;
            if ($newValidity) {
                $valid = 1;
            }
            $stmt = $this->getBdd()->prepare($query);
            // Journalise l'ID de l'annonce et la nouvelle validité pour le suivi et le débogage
            error_log("Testimonial ID: " . $testimonialId);
            error_log("New Validity: " . $valid);
            // Lie les valeurs aux paramètres de la requête SQL
            $stmt->bindValue(':id', $testimonialId, PDO::PARAM_INT);
            $stmt->bindValue(':status', $valid, PDO::PARAM_INT);
            // Journalise la requête SQL pour le suivi et le débogage
            error_log("SQL Query: " . $query);
            // Exécute la requête SQL préparée
            return $stmt->execute();
        } catch (PDOException $e) {
            // Gère les erreurs PDO, les journalise pour le suivi et retourne faux en cas d'erreur
            error_log("Error in updateTestimonialValidation: " . $e->getMessage());
            return false;
        }
    }


    public function deleteTestimonial($testimonialId)
    {
        try {
            // Préparer la requête de suppression
            $stmt = $this->getBdd()->prepare("DELETE FROM Testimonials WHERE Id_Testimonials = :testimonialId");
            // Binder le paramètre :testimonialId avec la valeur de $testimonialId
            $stmt->bindParam(':testimonialId', $testimonialId, PDO::PARAM_INT);
            // Exécuter la requête de suppression
            $result = $stmt->execute();
            // Vérifier si la suppression a réussi
            if ($result) {
                error_log("ApiManager::deleteTestimonial - Témoignage $testimonialId supprimé avec succès");
                return true;
            } else {
                error_log("ApiManager::deleteTestimonial - Échec de la suppression du témoignage $testimonialId");
                return false;
            }
            // Retourne le résultat de l'exécution (true ou false)
            return $result;
        } catch (PDOException $e) {
            // Gérer les erreurs PDO
            error_log("ApiManager::deleteTestimonial - Erreur PDO: " . $e->getMessage());
            return false;
        }
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
    public function createUser($email, $name, $phone, $pseudo, $userRole, $passwordHash, $primaryGarage_Id, $Id_Garage)
    {
        // Prépare la requête SQL pour insérer un nouvel utilisateur
        $req = "INSERT INTO Users (email, name, phone, pseudo, userRole, password_hash, primaryGarage_Id, Id_Garage) 
            VALUES (:email, :name, :phone, :pseudo, :userRole, :passwordHash, :primaryGarage_Id, :Id_Garage)";
        // Préparez les paramètres
        $params = array(
            ':email' => $email,
            ':name' => $name,
            ':phone' => $phone,
            ':pseudo' => $pseudo,
            ':userRole' => $userRole,
            ':passwordHash' => $passwordHash,
            ':primaryGarage_Id' => $primaryGarage_Id,
            ':Id_Garage' => $Id_Garage
        );
        // Exécute la requête et retournez le résultat
        return $this->executeAndFetchAll($req, $params);
    }

    // Méthode pour insérer un message global
    public function insertMessageAnnonce($Id_CarAnnonce, $formData)
    {
        // Vérification des propriétés de l'objet et définition des valeurs par défaut
        $userName = isset($formData["userName"]) ? $formData["userName"] : '';
        $userEmail = isset($formData["userEmail"]) ? $formData["userEmail"] : '';
        $userPhone = isset($formData["userPhone"]) ? $formData["userPhone"] : '';
        $message = isset($formData["message"]) ? $formData["message"] : '';
        $createdAt = isset($formData["createdAt"]) ? $formData["createdAt"] : date('Y-m-d H:i:s'); // Date de création actuelle
        // Requête SQL paramétrée pour insérer les données dans la table MessageAnnonce
        $sql = "INSERT INTO MessageAnnonce (userName, userEmail, userPhone, message, createdAt, Id_CarAnnonce) VALUES (?, ?, ?, ?, ?, ?)";
        try {
            // Préparation de la requête SQL
            $stmt = $this->getBdd()->prepare($sql);
            // Liaison des valeurs aux paramètres de la requête
            $stmt->bindParam(1, $userName, PDO::PARAM_STR);
            $stmt->bindParam(2, $userEmail, PDO::PARAM_STR);
            $stmt->bindParam(3, $userPhone, PDO::PARAM_STR);
            $stmt->bindParam(4, $message, PDO::PARAM_STR);
            $stmt->bindParam(5, $createdAt, PDO::PARAM_STR);
            $stmt->bindParam(6, $Id_CarAnnonce, PDO::PARAM_INT);
            // Exécution de la requête
            $stmt->execute();
            // Retourne true si l'insertion est réussie
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            // Gestion des erreurs et enregistrement dans le journal des erreurs
            error_log("Error in insertMessageAnnonce: " . $e->getMessage());
            // Retourne false si l'insertion a échoué
            return false;
        }
    }


    public function getDBContactMessage()
    {
        $req = "SELECT * FROM ContactMessage";
        return $this->executeAndFetchAll($req);
    }

    public function insertContactMessage($formData)
    {
        $req = "INSERT INTO ContactMessage (name, email, phone, message) VALUES (?, ?, ?, ?)";
        try {
            var_dump($formData);
            $stmt = $this->getBdd()->prepare($req);
            // Valider et échapper les données
            $name = htmlspecialchars(strip_tags(substr($formData["name"], 0, 255)), ENT_QUOTES, 'UTF-8'); // Limiter la longueur du champ à 255 caractères et échapper les caractères spéciaux
            $email = filter_var($formData["email"], FILTER_VALIDATE_EMAIL); // Validation de l'e-mail
            $phone = htmlspecialchars(strip_tags(preg_replace("/[^0-9]/", "", $formData["phone"])), ENT_QUOTES, 'UTF-8'); // Supprime les caractères non numériques et échapper les caractères spéciaux
            $message = htmlspecialchars(strip_tags(substr($formData["message"], 0, 1000)), ENT_QUOTES, 'UTF-8'); // Limite la longueur du champ à 1000 caractères et échapper les caractères spéciaux
            var_dump($formData["name"]);  // Vérification de la validité de l'e-mail
            if (!$email) {
                return false; // Retourne false si l'e-mail est invalide
            }
            // Exécute la requête avec les valeurs des champs
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


    // Méthode pour créer une annonce
    public function createAnnonce($annonceData)
    {
        try {
            // Démarre une transaction
            $this->getBdd()->beginTransaction();
            // Requête pour insérer dans la table CarAnnonce
            $reqAnnonce = "INSERT INTO CarAnnonce (title, createdAt, valid, Id_Garage) VALUES (:title, :createdAt, :valid, :Id_Garage)";
            $stmtAnnonce = $this->getBdd()->prepare($reqAnnonce);
            $stmtAnnonce->execute([
                ':title' => $annonceData['annonce_title'],
                ':createdAt' => $annonceData['annonce_createdAt'],
                ':valid' => $annonceData['annonce_valid'],
                ':Id_Garage' => $annonceData['Id_Garage']
            ]);
            // Récupère l'ID de l'annonce insérée
            $annonceId = $this->getBdd()->lastInsertId();
            // Requête pour insérer dans la table Cars
            $reqCar = "INSERT INTO Cars (mileage, registration, price, description, main_image_url, power, power_unit, color, Id_CarAnnonce) 
                VALUES (:mileage, :registration, :price, :description, :main_image_url, :power, :power_unit, :color, :Id_CarAnnonce)";
            $stmtCar = $this->getBdd()->prepare($reqCar);
            $stmtCar->execute([
                ':mileage' => $annonceData['mileage'],
                ':registration' => $annonceData['registration'],
                ':price' => $annonceData['price'],
                ':description' => $annonceData['description'],
                ':main_image_url' => $annonceData['main_image_url'],
                ':power' => $annonceData['power'],
                ':power_unit' => $annonceData['power_unit'],
                ':color' => $annonceData['color'],
                ':Id_CarAnnonce' => $annonceId
            ]);
            // Récupère l'ID de la voiture insérée
            $carId = $this->getBdd()->lastInsertId();
            // Requête pour insérer dans la table CarsEnergy
            $reqEnergy = "INSERT INTO CarsEnergy (Id_Cars, Id_EnergyType) VALUES (:Id_Cars, :Id_EnergyType)";
            $stmtEnergy = $this->getBdd()->prepare($reqEnergy);
            $stmtEnergy->execute([
                ':Id_Cars' => $carId,
                ':Id_EnergyType' => $annonceData['Id_EnergyType']
            ]);
            // Requête pour insérer dans la table Models
            $reqModels = "INSERT INTO Models (model_name, category_model, Id_Brand, Id_Cars) VALUES (:model_name, :category_model, :Id_Brand, :Id_Cars)";
            $stmtModels = $this->getBdd()->prepare($reqModels);
            $stmtModels->execute([
                ':model_name' => $annonceData['model_name'],
                ':category_model' => $annonceData['category_model'],
                ':Id_Brand' => $annonceData['Id_Brand'],
                ':Id_Cars' => $carId
            ]);
            // Récupère l'ID du modèle inséré
            $modelId = $this->getBdd()->lastInsertId();
            // Requête pour insérer dans la table ModelsManufactureYears
            $reqManufactureYears = "INSERT INTO ModelsManufactureYears (Id_Model, Id_ManufactureYears) VALUES (:Id_Model, :Id_ManufactureYears)";
            $stmtManufactureYears = $this->getBdd()->prepare($reqManufactureYears);
            $stmtManufactureYears->execute([
                ':Id_Model' => $modelId,
                ':Id_ManufactureYears' => $annonceData['manufacture_year']
            ]);
            // Valide la transaction
            $this->getBdd()->commit();
            // Retourne true si tout s'est bien passé
            return true;
        } catch (PDOException $e) {
            // En cas d'erreur, annule la transaction
            $this->getBdd()->rollBack();
            error_log("Error in createAnnonce: " . $e->getMessage());
            return false;
        }
    }


    // Méthode pour mettre à jour une annonce
    public function updateAnnonce($id, $annonceData)
    {
        try {
            $this->getBdd()->beginTransaction();
            // Met à jour la table CarAnnonce
            $reqAnnonce = "UPDATE CarAnnonce SET title = :title, createdAt = :createdAt, valid = :valid, Id_Garage = :Id_Garage WHERE Id_CarAnnonce = :id";
            $stmtAnnonce = $this->getBdd()->prepare($reqAnnonce);
            $stmtAnnonce->execute([
                ':title' => $annonceData['annonce_title'],
                ':createdAt' => $annonceData['annonce_createdAt'],
                ':valid' => $annonceData['annonce_valid'],
                ':Id_Garage' => $annonceData['Id_Garage'],
                ':id' => $id
            ]);
            // Met à jour la table Cars
            $reqCar = "UPDATE Cars SET mileage = :mileage, registration = :registration, price = :price, description = :description, main_image_url = :main_image_url, power = :power, power_unit = :power_unit, color = :color WHERE Id_CarAnnonce = :id";
            $stmtCar = $this->getBdd()->prepare($reqCar);
            $stmtCar->execute([
                ':mileage' => $annonceData['mileage'],
                ':registration' => $annonceData['registration'],
                ':price' => $annonceData['price'],
                ':description' => $annonceData['description'],
                ':main_image_url' => $annonceData['main_image_url'],
                ':power' => $annonceData['power'],
                ':power_unit' => $annonceData['power_unit'],
                ':color' => $annonceData['color'],
                ':id' => $id
            ]);
            // Mettre à jour la table CarsEnergy
            $reqEnergy = "UPDATE CarsEnergy SET Id_EnergyType = :Id_EnergyType WHERE Id_Cars = (SELECT Id_Cars FROM Cars WHERE Id_CarAnnonce = :id)";
            $stmtEnergy = $this->getBdd()->prepare($reqEnergy);
            $stmtEnergy->execute([
                ':Id_EnergyType' => $annonceData['Id_EnergyType'],
                ':id' => $id
            ]);

            $this->getBdd()->commit();
            return true;
        } catch (PDOException $e) {
            $this->getBdd()->rollBack();
            error_log("Error in updateAnnonce: " . $e->getMessage());
            return false;
        }
    }

    // // Méthode pour supprimer une annonce
    // public function deleteAnnonce($id)
    // {
    //     try {
    //         $this->getBdd()->beginTransaction();
    //         // Supprime les enregistrements de la table CarsOptions liés à cette annonce
    //         $reqOptions = "DELETE FROM CarsOptions WHERE Id_Cars = (SELECT Id_Cars FROM Cars WHERE Id_CarAnnonce = :id)";
    //         $stmtOptions = $this->getBdd()->prepare($reqOptions);
    //         $stmtOptions->execute([':id' => $id]);
    //         // Supprime les enregistrements de la table CarsEnergy liés à cette annonce
    //         $reqEnergy = "DELETE FROM CarsEnergy WHERE Id_Cars = (SELECT Id_Cars FROM Cars WHERE Id_CarAnnonce = :id)";
    //         $stmtEnergy = $this->getBdd()->prepare($reqEnergy);
    //         $stmtEnergy->execute([':id' => $id]);
    //         // Supprime les enregistrements de la table Cars liés à cette annonce
    //         $reqCar = "DELETE FROM Cars WHERE Id_CarAnnonce = :id";
    //         $stmtCar = $this->getBdd()->prepare($reqCar);
    //         $stmtCar->execute([':id' => $id]);
    //         // Supprime l'annonce de la table CarAnnonce
    //         $reqAnnonce = "DELETE FROM CarAnnonce WHERE Id_CarAnnonce = :id";
    //         $stmtAnnonce = $this->getBdd()->prepare($reqAnnonce);
    //         $stmtAnnonce->execute([':id' => $id]);
    //         $this->getBdd()->commit();
    //         return true;
    //     } catch (PDOException $e) {
    //         $this->getBdd()->rollBack();
    //         error_log("Error in deleteAnnonce: " . $e->getMessage());
    //         return false;
    //     }
    // }

    public function updateValidationStatus($annonceId, $newValidity)
    {
        // Prépare la requête SQL pour mettre à jour le statut de validation dans la table CarAnnonce
        $query = "UPDATE CarAnnonce SET valid = :status WHERE Id_CarAnnonce = :id";
        try {
            // Prépare la requête SQL
            $stmt = $this->getBdd()->prepare($query);
            // Journalise l'ID de l'annonce et la nouvelle validité pour le suivi et le débogage
            error_log("Annonce ID: " . $annonceId);
            error_log("New Validity: " . $newValidity);
            // Lie les valeurs aux paramètres de la requête SQL
            $stmt->bindValue(':id', $annonceId, PDO::PARAM_INT);
            $stmt->bindValue(':status', $newValidity, PDO::PARAM_INT);
            // Journalise la requête SQL pour le suivi et le débogage
            error_log("SQL Query: " . $query);
            // Exécute la requête SQL préparée
            $stmt->execute();
            // Vérifie le nombre de lignes affectées par la mise à jour
            $rowCount = $stmt->rowCount();
            // Journalise le nombre de lignes affectées pour le suivi
            error_log("Rows affected: " . $rowCount);
            // Retourne vrai si au moins une ligne a été mise à jour avec succès, sinon retourne faux
            return $rowCount > 0;
        } catch (PDOException $e) {
            // Gère les erreurs PDO, les journalise pour le suivi et retourne faux en cas d'erreur
            error_log("Error in updateValidationStatus: " . $e->getMessage());
            return false;
        }
    }
}
