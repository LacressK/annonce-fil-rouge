<?php

    require_once __DIR__ . '/Controleur/main.php';

    session_start();

    $controleur = new main;

    if (!empty($_GET) || !empty($_POST)) {
        $controleur->parse();
    } else {
        $controleur->afficherAcceuil();
    }

?>