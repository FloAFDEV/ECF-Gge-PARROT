<?php

// Inclusion du fichier de configuration
require_once "config.php";

// Déclaration de la classe abstraite Model
abstract class Model
{
    // Propriété statique pour stocker l'instance unique de PDO
    private static $pdo;

    // Méthode privée pour configurer la connexion à la base de données
    private static function setBdd($dbConfig)
    {
        // Vérification de l'existence des informations de configuration de la base de données
        if (!isset($dbConfig['DB_HOST'], $dbConfig['DB_DATABASE'], $dbConfig['DB_USERNAME'], $dbConfig['DB_PASSWORD'])) {
            throw new Exception("Configuration de la base de données manquante.");
        }
        // Construction de la chaîne de connexion PDO
        $dsn = 'mysql:host=' . $dbConfig['DB_HOST'] . ';dbname=' . $dbConfig['DB_DATABASE'] . ';charset=utf8mb4';
        $user = $dbConfig['DB_USERNAME'];
        $password = $dbConfig['DB_PASSWORD'];

        // Création de l'instance PDO avec gestion des erreurs
        self::$pdo = new PDO($dsn, $user, $password);
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Méthode protégée pour obtenir l'instance de PDO
    protected function getBdd()
    {
        // Vérification si l'instance de PDO existe déjà
        if (self::$pdo === null) {
            // Si non, on configure la connexion en utilisant les informations de configuration
            self::setBdd($GLOBALS['dbConfig']);
        }
        // Retourne l'instance de PDO
        return self::$pdo;
    }

    // Méthode statique pour envoyer des données au format JSON
    public static function sendJSON($info)
    {
        // Définition des en-têtes pour permettre les requêtes cross-origin et spécifiant le type de contenu
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");
        // Encodage des données en JSON et envoi au client
        echo json_encode($info);
    }
}
// https://ggevparrot.vercel.app