<?php

require "./Securite.class.php";
require "./AdminManager.php";

class AdminController
{
    private $adminManager;

    public function __construct()
    {
        $this->adminManager = new AdminManager();
    }

    public function getPageLogin()
    {
        require_once "views/admin/login.php";
    }
    public function connexion()
    {
        if (!empty($_POST['login']) && !empty($_POST['password'])) {
            $login = Securite::secureHTML($_POST['login']);
            $password = Securite::secureHTML($_POST['password']);
            $adminManager = new AdminManager();
            if ($this->adminManager->isConnexionValid($login, $password)) {
                $_SESSION['login'] = $login;
                header('Location: index.php?action=dashboard');
            } else {
                header('Location: index.php?action=login&error=1');
            }
        }
    }
}
