<?php
define("URL", str_replace("index.php", "", (isset($_SERVER['HTTPS']) ? "https" : "http") .
    "://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]"));
//On passe de http://localhost/..
// -> https://wwww.site.com/...

try {
    if (empty($_GET['page'])) {
        throw new Exception("La page demandée n'éxiste pas");
    } else {
        $url = explode("/", filter_var($_GET['page'], FILTER_SANITIZE_URL));
        if (!isset($url[0]) || !isset($url[1]))
            throw new Exception("La page demandée n'éxiste pas");
        switch ($url[0]) {
            case "front":
                switch ($url[1]) {
                    case "modèles":
                        echo "données JSON des Modèles" . $url[2] . " demandées";
                        break;
                    case "garage":
                        echo "données JSON du Garage" . $url[3] . "demandées";
                        break;
                    case "annonces":
                        echo "données JSON des Annonces demandées";
                        break;
                    case "brands":
                        echo "données JSON des Brands (marques)" . $url[4] . " demandées";
                        break;
                    case "cars":
                        echo "données JSON des Cars (voitures)" . $url[5] . "demandées";
                        break;
                    default:
                        throw new Exception("Oups cette page n'éxiste pas");
                }
                break;
            case "back":
                echo "page back demandée";
                break;
            default:
                throw new Exception("La page n'éxiste pas");
        }
        // echo "<pre>";
        // print_r($url);
        // echo "</pre>";
        // echo "La page demandée est: " . $_GET['page'];
    }
} catch (Exception $e) {
    $msg = $e->getMessage();
    echo $msg;
}
