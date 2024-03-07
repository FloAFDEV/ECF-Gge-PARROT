<?php

// Charge les variables d'environnement à partir du fichier .env
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $envVars = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($envVars as $envVar) {
        // Ignorer les lignes commençant par #
        if (strpos($envVar, '#') === 0) {
            continue;
        }
        // Sépare la variable et sa valeur
        list($name, $value) = explode('=', $envVar, 2);
        // Définir la variable d'environnement
        putenv("$name=$value");
    }
}

// Configuration de la base de données
$env = getenv('APP_ENV');

if ($env === 'production') {
    $dbConfig = [
        'DB_HOST' => getenv('DB_HOST_PROD'),
        'DB_DATABASE' => getenv('DB_DATABASE_PROD'),
        'DB_USERNAME' => getenv('DB_USERNAME_PROD'),
        'DB_PASSWORD' => getenv('DB_PASSWORD_PROD')
    ];
} else {
    $dbConfig = [
        'DB_HOST' => getenv('DB_HOST_DEV'),
        'DB_DATABASE' => getenv('DB_DATABASE_DEV'),
        'DB_USERNAME' => getenv('DB_USERNAME_DEV'),
        'DB_PASSWORD' => getenv('DB_PASSWORD_DEV')
    ];
}
