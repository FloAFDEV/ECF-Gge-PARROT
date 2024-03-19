<?php

require_once "config.php";

// Déclaration de ma classe abstraite (càd jamais instanciable)
abstract class Model
{
    // Je veux créer une instance de PDO unique
    private static $pdo;

    private static function setBdd($dbConfig)
    {
        if (!isset($dbConfig['DB_HOST']) || !isset($dbConfig['DB_DATABASE']) || !isset($dbConfig['DB_USERNAME']) || !isset($dbConfig['DB_PASSWORD'])) {
            throw new Exception("Configuration de la base de données manquante.");
        }
        $dns = 'mysql:host=' . $dbConfig['DB_HOST'] . ';dbname=' . $dbConfig['DB_DATABASE'] . ';charset=utf8';
        $user = $dbConfig['DB_USERNAME'];
        $password = $dbConfig['DB_PASSWORD'];
        // Connexion à la base de données
        self::$pdo = new PDO($dns, $user, $password);
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }

    protected function getBdd()
    {
        // On vérifie si l'instance de PDO existe
        if (self::$pdo === null) {
            self::setBdd($GLOBALS['dbConfig']); // Passer les informations de configuration de la base de données
        }
        return self::$pdo;
    }

    // Fonction statique appelable de n'importe où à partir de la classe "Model" qui convertit les informations en format JSON
    public static function sendJSON($info)
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($info);
    }
}
