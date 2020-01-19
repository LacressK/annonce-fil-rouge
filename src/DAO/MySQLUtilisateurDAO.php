<?php

    require_once __DIR__ . '/DAOUtilisateur.php';
    require_once __DIR__ . '/../Metier/Utilisateur.php';
    require_once __DIR__ . '/../BDDException.php';
    
    class MySQLUtilisateurDAO {

        private $cnx;

        public function __construct($cnx)   //Constructor
        {
            $this->cnx = $cnx;
        }

        public function __destruct()        // Destructor
        {
            $this->cnx = null;
        }

        public function getById(int $id) {
            try {
                $requete = $this->cnx->prepare("SELECT * FROM Utilisateur WHERE id_utilisateur = :id");
                $requete->bindValue(':id', $id);
                $requete->execute();

                $requete->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Utilisateur');
                $resultat = $requete->fetch();
                return $resultat;

            } catch (PDOException $e) {
                throw new BDDException($e->getMessage(), $e->getCode());
            }
        }

        public function insert(Utilisateur $utilisateur)        //return ID of the last INSERT, -1 for failure
        {
            try {
                $this->cnx->beginTransaction();
                $requete = $this->cnx->prepare("INSERT INTO Utilisateur(nom, prenom, email, username, mdp) VALUES (:nom, :prenom, :email, :username, :mdp)");
                $requete->bindValue(':nom', $utilisateur->getNom());
                $requete->bindValue(':prenom', $utilisateur->getPrenom());
                $requete->bindValue(':email', $utilisateur->getEmail());
                $requete->bindValue(':username', $utilisateur->getUsername());
                $requete->bindValue(':mdp', $utilisateur->getMdp());
                // $requete->bindValue(':admin', intval($utilisateur->getAdmin()));
                $requete->execute();

                $curId = $this->cnx->lastInsertId();
                $this->cnx->commit();
                return $this->getById($curId);
               
                
            } catch (PDOException $e) {
                throw new BDDException($e->getMessage(), $e->getCode());
                $this->cnx->rollBack();
                
                return NULL;
            }
        }

        public function identifier(Utilisateur $utilisateur)     //retourne le id d'utilisateur identifié
        {
            try {
                $this->cnx->beginTransaction();
                $requete = $this->cnx->prepare("SELECT * FROM Utilisateur WHERE :USR = username AND :MDP = mdp");
                $requete->bindValue(':USR', $utilisateur->getUsername());
                $requete->bindValue(':MDP', $utilisateur->getMdp());
                $requete->execute();
                $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Utilisateur');
                
                if($resultat = $requete->fetch()) {
                    $this->cnx->commit();
                    return $resultat;
                } else {
                    throw new BDDException('Aucun utilisateur correspondant.', 101);
                }
            } catch (PDOException $e) {
                throw new BDDException($e->getMessage(), $e->getCode());
                $this->cnx->rollBack();

                return -1;
            }
        }
    }

?>