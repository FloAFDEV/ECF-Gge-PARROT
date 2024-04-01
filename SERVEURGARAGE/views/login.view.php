<?php ob_start(); ?>

<?php

$content = ob_get_clean();
$titre = "Login";
require "view/commons/template.php";
