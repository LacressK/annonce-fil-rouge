<?php

    require_once __DIR__ . '/../Metier/Rubrique.php';

    interface DAORubrique {
        public function getAll();
        public function insert(Rubrique $rubrique);
        public function delete(Rubrique $rubrique);
        public function update(Rubrique $rubrique);
    }

?>