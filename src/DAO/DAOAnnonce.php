<?php

    require_once __DIR__ . '/../Metier/Rubrique.php';
    require_once __DIR__ . '/../Metier/Utilisateur.php';
    require_once __DIR__ . '/../Metier/Annonce.php';

    interface DAOAnnonce {
        public function insert(Annonce $annonce);
        public function delete(Annonce $annonce);
        public function update(Annonce $annonce);
        public function getByRubrique(Rubrique $rubrique);
        public function getByUtilisateur(Utilisateur $utilisateur);
        public function deletePerimees();
    }

?>