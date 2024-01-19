<?php

// Déclaration de ma classe abstraite (càd jamais instenciable)

abstract class Model
{
    // je veux créer une instance de PDO unique
    private static $pdo;

    private static function setBdd()
    {
        // Connexion à la base de données
        self::$pdo = new PDO("mysql:host=localhost;dbname=DBGarageParrot;charset=utf8", "root", "root");
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }

    protected function getBdd()
    {
        // On vérifie si l'instance de PDO existe
        if (self::$pdo === null) {
            self::setBdd();
        }
        return self::$pdo;
    }
}
