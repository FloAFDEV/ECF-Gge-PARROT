<?php

require_once "./models/Model.php";

class AdminManager extends Model
{

    // Constantes pour les rôles
    const ROLE_SUPERADMIN = 'superadmin';
    const ROLE_ADMIN = 'admin';
    const ROLE_EMPLOYE = 'employé';

    // Fonction pour récupérer le mot de passe de l'utilisateur à partir de la base de données
    private function getPasswordUser($login)
    {
        $req = "SELECT password FROM users WHERE email = :login";
        $stmt = $this->getBdd()->prepare($req);
        $stmt->bindValue(":login", $login, PDO::PARAM_STR);
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $admin['password'];
    }

    // Fonction pour vérifier la validité de la connexion
    public function isConnexionValid($login, $password)
    {
        $passwordBD = $this->getPasswordUser($login);
        return password_verify($password, $passwordBD);
    }

    // Fonction pour récupérer l'ID de l'utilisateur à partir de la base de données
    public function getUserIDFromDatabase($email)
    {
        // Validation de l'entrée GET
        if (!isset($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email invalide");
        }
        $stmt = $this->getBdd()->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['id'];
    }

    // Fonction pour récupérer l'email de l'utilisateur à partir de la base de données
    public function getUserEmailFromDatabase($userId)
    {
        // Validation de l'entrée GET
        if (!isset($userId) || !is_numeric($userId)) {
            throw new Exception("ID utilisateur invalide");
        }
        $stmt = $this->getBdd()->prepare("SELECT email FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['email'];
    }

    // Fonction pour récupérer le rôle de l'utilisateur à partir de la base de données
    public function getUserRoleFromDatabase($email)
    {
        // Validation de l'entrée GET
        if (!isset($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email invalide");
        }
        $stmt = $this->getBdd()->prepare("SELECT role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['role'];
    }


    // Verifie si un utilisateur a le rôle requis
    public function checkUserRole($email, $requiredRole)
    {
        $userRole = $this->getUserRoleFromDatabase($email);
        return $userRole === $requiredRole;
    }

    // Verifie si un utilisateur est un superadmin
    public function isSuperAdmin($email)
    {
        return $this->checkUserRole($email, self::ROLE_SUPERADMIN);
    }

    // Verifie si un utilisateur est un admin
    public function isAdmin($email)
    {
        return $this->checkUserRole($email, self::ROLE_ADMIN);
    }

    // Verifie si un utilisateur est un employé
    public function isEmploye($email)
    {
        return $this->checkUserRole($email, self::ROLE_EMPLOYE);
    }
}
