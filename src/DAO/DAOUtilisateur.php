<?php

    require_once __DIR__ . '/../Metier/Utilisateur.php';

    interface DAOUtilisateur {
        public function insert(Utilisateur $utilisateur);
        public function identifier(Utilisateur $utilisateur);
    }

?>