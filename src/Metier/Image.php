<?php

    class Image {

        private $annonce;
        private $chemin;
        private $id_image;

        public function __construct(Annonce $annonce, string $chemin, int $id_image = NULL) {
            $this->annonce = $annonce;
            $this->chemin = $chemin;
            $this->id_image = $id_image;
        }

        /**
         * Get the value of annonce
         */ 
        public function getAnnonce()
        {
                return $this->annonce;
        }

        /**
         * Set the value of annonce
         *
         * @return  self
         */ 
        public function setAnnonce($annonce)
        {
                $this->annonce = $annonce;

                return $this;
        }

        /**
         * Get the value of chemin
         */ 
        public function getChemin()
        {
                return $this->chemin;
        }

        /**
         * Set the value of chemin
         *
         * @return  self
         */ 
        public function setChemin($chemin)
        {
                $this->chemin = $chemin;

                return $this;
        }

        /**
         * Get the value of id_image
         */ 
        public function getId_image()
        {
                return $this->id_image;
        }

        /**
         * Set the value of id_image
         *
         * @return  self
         */ 
        public function setId_image($id_image)
        {
                $this->id_image = $id_image;

                return $this;
        }
    }

?>