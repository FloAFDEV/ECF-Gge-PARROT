<?php

require_once "./Securite.class.php";
require_once "../models/admin.manager.php";


class AdminController
{
    private $adminManager;

    public function __construct()
    {
        $this->adminManager = new AdminManager();
    }

    public function getPageAdmin()
    {
        $baseUrl = getenv('BASE_URL');
        // Construit les données nécessaires à destination du Frontpour la page de connexion
        $adminPageData = [
            'pageTitle' => 'Connexion',
            'formFields' => [
                ['name' => 'email', 'label' => 'Adresse e-mail', 'type' => 'email'],
                ['name' => 'password', 'label' => 'Mot de passe', 'type' => 'password']
            ],
            'actionUrl' => $baseUrl . 'admin', // URL de l'endpoint API pour la soumission du formulaire de connexion
        ];
        // Renvoie les données sous forme de JSON
        header('Content-Type: application/json');
        echo json_encode($adminPageData);
    }
}
