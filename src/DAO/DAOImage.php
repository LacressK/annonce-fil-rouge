<?php

    require_once __DIR__ . '/../Metier/Annonce.php';
    require_once __DIR__ . '/../Metier/Image.php';

    interface DAOImage {
        public function insert(Image $image);
        public function delete(Image $image);
        public function update(Image $image);
        public function getByAnnonce(Annonce $annonce);
    }

?>