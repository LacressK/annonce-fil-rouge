<?php

    require_once __DIR__ . '/Rubrique.php';
    require_once __DIR__ . '/Utilisateur.php';


    class Annonce implements JsonSerializable {

        private $id_annonce;
        private $utilisateur;
        private $rubrique;
        private $entete;
        private $corps;
        private $date_depot;
        private $date_validite;

        public function __construct(Utilisateur $utilisateur = null, Rubrique $rubrique = null, $entete = "", $corps = "", 
                                    DateTime $date_depot = null, DateTime $date_validite = null, int $id_annonce = -1)
        {
            $this->utilisateur = $utilisateur;
            $this->rubrique = $rubrique;
            $this->entete = $entete;
            $this->corps = $corps;

            if ($date_depot == null) {
                $this->date_depot = new DateTime('now');
            } else {
                $this->date_depot = $date_depot;
            }

            if($date_validite < $this->date_depot || $date_validite == null) {
                $this->date_validite = new DateTime('4 weeks');
            } else {
                $this->date_validite = $date_validite;
            }

            $this->id_annonce = $id_annonce;
        }

        public function getId_annonce()
        {
            return $this->id_annonce;
        }

        public function setId_annonce($id_annonce)
        {
            $this->id_annonce = $id_annonce;
        }

        public function getUtilisateur()
        {
            return $this->utilisateur;
        }

        public function setUtilisateur($utilisateur)
        {
            $this->utilisateur = $utilisateur;
        }

        public function getRubrique()
        {
            return $this->rubrique;
        }

        public function setRubrique($rubrique)
        {
            $this->rubrique = $rubrique;
        }

        public function getEnTete()
        {
            return $this->entete;
        }

        public function setEnTete($entete)
        {
            $this->entete = $entete;
        }

        public function getCorps()
        {
            return $this->corps;
        }

        public function setCorps($corps)
        {
            $this->corps = $corps;
        }

        public function getDateDepot()
        {
            return $this->date_depot;
        }

        public function setDateDepot(DateTime $date_depot)
        {
            $this->date_depot = $date_depot;
        }

        public function getDateValidite()
        {
            return $this->date_validite;
        }

        public function setDateValidite(DateTime $date_validite)
        {
            if($date_validite < $this->date_depot || $date_validite == null) {
                $this->date_validite = new DateTime('4 weeks');
            } else {
                $this->date_validite = $date_validite;
            }
        }

        public function __toString() 
        {
            return $this->getUtilisateur()->getUsername()."|".$this->getEnTete()."|".$this->getDateValidite()->format('Y-d-m');
        }

        public function jsonSerialize()
        {
            return[
                'id_annonce' => $this->id_annonce,
                'entete' => $this->entete,
                'corps' => $this->corps,
                'date_deppot' => $this->date_depot,
                'date_validite' => $this->date_validite
            ];
        }
    }
?>