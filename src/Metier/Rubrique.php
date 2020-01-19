<?php

    class Rubrique implements JsonSerializable {

        private $id_rubrique;
        private $libelle;

        public function __construct(string $libelle = "", int $id_rubrique = -1)
        {
                $this->id_rubrique = $id_rubrique;
                $this->libelle = $libelle;
        }

        public function getId_rubrique()
        {
                return $this->id_rubrique;
        }

        public function setId_rubrique($id_rubrique)
        {
                $this->id_rubrique = $id_rubrique;
        }

        public function getLibelle()
        {
                return $this->libelle;
        }

        public function setLibelle($libelle)
        {
                $this->libelle = $libelle;
        }

        public function __toString() {
                return $this->libelle;
        }

        public function jsonSerialize()
        {
                return [
                        'id' => $this->id_rubrique,
                        'libelle' => $this->libelle
                ];
        }
    }
?>