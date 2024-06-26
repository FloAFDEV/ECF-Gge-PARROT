<?php


require_once "./models/Model.php";

$adminManager = new AdminManager();

class AdminManager extends Model
{
    // Constantes pour les rôles
    const ROLE_SUPERADMIN = 'superAdmin';
    const ROLE_ADMIN = 'admin';
    const ROLE_EMPLOYE = 'employé';
    const ROLE_USER = "client";

    // Fonction pour hacher le mot de passe avec Bcrypt
    private function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    // Fonction pour hacher le mot de passe lors de l'inscription ou de la mise à jour
    public function hashAndSavePassword($email, $password)
    {
        // Hache le mot de passe
        $hashedPassword = $this->hashPassword($password);
        try {
            // Met à jour le mot de passe haché dans la base de données
            $stmt = $this->getBdd()->prepare("UPDATE Users SET password_hash = ? WHERE email = ?");
            $stmt->execute([$hashedPassword, $email]);
            // Retourne true si la mise à jour s'est bien déroulée
            return true;
        } catch (PDOException $e) {
            throw new Exception("Erreur dans la mise à jour du mot de passe");
        }
    }

    // Fonction pour récupérer le mot de passe de l'utilisateur à partir de l'email
    private function getPasswordHashByEmail($email)
    {
        $stmt = $this->getBdd()->prepare("SELECT password_hash FROM Users WHERE email = ?");
        $stmt->execute([$email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // var_dump($result);
        return $result ? $result['password_hash'] : null;
    }

    // Fonction pour vérifier si la connexion est valide
    public function isConnexionValid($email, $password)
    {
        // Récupérer le mot de passe haché de la base de données
        $passwordBD = $this->getPasswordHashByEmail($email);
        // Récupérer le rôle de l'utilisateur
        $userRole = $this->getUserRoleByEmail($email);
        // Afficher les identifiants récupérés à des fins de débogage
        // var_dump("Email récupéré de la base de données : ", $email);
        // var_dump("Mot de passe haché récupéré de la base de données : ", $passwordBD);
        // var_dump("Rôle récupéré de la base de données : ", $userRole);
        // var_dump("Mot de passe en clair saisi avant password_verify : ", $password);
        // var_dump("Mot de passe haché de la base de données avant password_verify : ", $passwordBD);
        // Vérifier si le mot de passe correspond au mot de passe haché
        $isValid = password_verify($password, $passwordBD);
        // Afficher les résultats de la vérification
        // var_dump("Mot de passe saisi : ", $password);
        // var_dump("La vérification de mot de passe est un succès ? ", $isValid);
        // // Retourne le résultat de la vérification
        // var_dump($isValid);
        return $isValid;
    }

    // Fonction pour récupérer l'ID de l'utilisateur à partir de l'email
    public function getUserIdByEmail($email)
    {
        // var_dump($email);
        $stmt = $this->getBdd()->prepare("SELECT Id_Users FROM Users WHERE email = ?");
        $stmt->execute([$email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // var_dump($result);
        return $result ? $result['Id_Users'] : null;
    }

    // Fonction pour récupérer le rôle de l'utilisateur à partir de l'email
    public function getUserRoleByEmail($email)
    {
        try {
            if (!$email) {
                throw new Exception("L'e-mail fourni est vide ou nul.");
            }
            // Prépare et exécute la requête SQL pour obtenir le rôle de l'utilisateur par e-mail
            $stmt = $this->getBdd()->prepare("SELECT userRole FROM Users WHERE email = ?");
            $stmt->execute([$email]);
            // Vérifie si la requête a retourné des résultats
            if ($stmt->rowCount() > 0) {
                // Récupère le résultat de la requête
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                // Vérifie si le résultat est non vide
                if ($result && isset($result['userRole'])) {
                    // Retourne le rôle de l'utilisateur
                    return $result['userRole'];
                } else {
                    // Lance une exception si aucun rôle n'est trouvé pour l'e-mail donné
                    throw new Exception("Aucun rôle trouvé pour l'utilisateur avec l'email : $email");
                }
            } else {
                // Lance une exception si aucun utilisateur n'est trouvé avec l'e-mail donné
                throw new Exception("Aucun utilisateur trouvé avec l'email : $email");
            }
        } catch (PDOException $e) {
            // Gère les erreurs de requête SQL
            throw new Exception("Erreur lors de l'exécution de la requête SQL : " . $e->getMessage());
        }
    }

    //Recupere le nom de l'utolisateur 
    public function getUserNameByEmail($email)
    {
        $stmt = $this->getBdd()->prepare("SELECT name FROM Users WHERE email = ?");
        $stmt->execute([$email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['name'] : null;
    }

    // Vérifie si un utilisateur a le rôle requis
    public function checkUserRole($email, $requiredRole)
    {
        $userRole = $this->getUserRoleByEmail($email);
        return $userRole === $requiredRole || in_array($requiredRole, explode(',', $userRole));
    }

    // Vérifie si un utilisateur est un superadmin
    public function isSuperAdmin($email)
    {
        return $this->checkUserRole($email, self::ROLE_SUPERADMIN);
    }

    // Vérifie si un utilisateur est un admin
    public function isAdmin($email)
    {

        return $this->checkUserRole($email, self::ROLE_ADMIN);
    }

    // Vérifie si un utilisateur est un employé
    public function isEmploye($email)
    {
        return $this->checkUserRole($email, self::ROLE_EMPLOYE);
    }

    // Vérifie les identifiants de connexion
    public function checkCredentials($email, $password)
    {
        try {
            // Récupère le mot de passe haché de la base de données
            $passwordBD = $this->getPasswordHashByEmail($email);
            // Vérifie si le mot de passe haché existe
            if ($passwordBD === null) {
                throw new Exception("Aucun utilisateur trouvé avec l'email : $email");
            }
            // Hache le mot de passe entré par l'utilisateur
            $hashedPassword = $this->hashPassword($password);
            // Vérifie si le mot de passe haché correspond au mot de passe haché enregistré en base de données
            return password_verify($password, $passwordBD);
        } catch (Exception $e) {
            // Gère les erreurs
            throw new Exception("Erreur lors de la vérification des identifiants : " . $e->getMessage());
        }
    }
}
