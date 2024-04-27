<?php

require_once "./models/Model.php";

$adminManager = new AdminManager();

class AdminManager extends Model
{
    // Constantes pour les rôles
    const ROLE_SUPERADMIN = 'superAdmin';
    const ROLE_ADMIN = 'admin';
    const ROLE_EMPLOYE = 'employé';

    // Fonction pour hacher le mot de passe avec Bcrypt
    private function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    // Fonction pour hacher le mot de passe lors de l'inscription ou de la mise à jour
    public function hashAndSavePassword($email, $password)
    {
        $hashedPassword = $this->hashPassword($password);
        // Enregistre $hashedPassword dans la base de données
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
        // // Retourner le résultat de la vérification
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
        $stmt = $this->getBdd()->prepare("SELECT role FROM Users WHERE email = ?");
        $stmt->execute([$email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // var_dump($result);
        return $result ? $result['role'] : null;
    }

    // Vérifie si un utilisateur a le rôle requis
    public function checkUserRole($email, $requiredRole)
    {

        $userRole = $this->getUserRoleByEmail($email);
        return $userRole === $requiredRole;
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
        // Vérifie si les identifiants sont valides
        $isValid = $this->isConnexionValid($email, $password);
        // Si les identifiants ne sont pas valides, retourne null
        if (!$isValid) {
            return null;
        }
        // Si les identifiants sont valides, récupère le rôle de l'utilisateur
        $userRole = $this->getUserRoleByEmail($email);
        // Assurez-vous d'obtenir d'abord l'ID de l'utilisateur avant de l'utiliser
        $userId = $this->getUserIdByEmail($email);
        var_dump($userId, $userRole, $email);
        // Retourne l'ID de l'utilisateur et son rôle
        return ["userId" => $userId, "userRole" => $userRole];
    }
}
