<?php

    class Utilisateur{

        private $id_utilisateur;
        private $nom;
        private $prenom;
        private $email;
        private $username;
        private $mdp;
        private $admin;
        
        public function __construct(string $nom = "", string $prenom = "", string $email = "", string $username = "", 
                                    string $mdp = "", bool $admin = FALSE, int $id_utilisateur = -1)
        {
            $this->nom = $nom;
            $this->prenom = $prenom;
            $this->email = $email;
            $this->username = $username;
            $this->mdp = sha1($mdp);

            if (!$admin) {
                $this->admin = 0;
            } else {
                $this->admin = 1;
            }
            
            $this->id_utilisateur = $id_utilisateur;
        }

        public function getId_utilisateur()
        {
            return $this->id_utilisateur;
        }

        public function setId_utilisateur(int $id_utilisateur)
        {
            $this->id_utilisateur = $id_utilisateur;
        }

        public function getNom()
        {
            return $this->nom;
        }

        public function setNom(string $nom)
        {
            $this->nom = $nom;
        }

        public function getPrenom()
        {
            return $this->prenom;
        }

        public function setPrenom(string $prenom)
        {
            $this->prenom = $prenom;
        }

        public function getEmail()
        {
            return $this->email;
        }

        public function setEmail(string $email)
        {
            $this->email = $email;
        }

        public function getUsername()
        {
            return $this->username;
        }

        public function setUsername(string $username)
        {
            $this->username = $username;
        }

        public function getMdp()
        {
            return $this->mdp;
        }

        public function setMdp(string $mdp)
        {
            $this->mdp = $mdp;
        }

        public function getAdmin()
        {
            return $this->admin;
        }

        public function setAdmin(bool $admin)
        {
            $this->admin = $admin;
        }

        public function __toString()
        {
            return $this->username;
        }
    }
?>