<?php

    
    require_once __DIR__ . '/DAOAnnonce.php';
    require_once __DIR__ . '/../Metier/Rubrique.php';
    require_once __DIR__ . '/../Metier/Utilisateur.php';
    require_once __DIR__ . '/../Metier/Annonce.php';
    require_once __DIR__ . '/../BDDException.php';

    class MySQLAnnonceDAO implements DAOAnnonce {

        private $cnx;

        public function __construct($cnx)   //Constructor
        {
            $this->cnx = $cnx;
        }

        public function __destruct() {      // Destructor
            $this->cnx = null;
        }

        public function getAll() // : tableau d'Annonce(s)
        {
            try {
                $requete = $this->cnx->prepare("SELECT * FROM Annonce 
                INNER JOIN Rubrique ON Rubrique.id_rubrique = Annonce.id_rubrique
                INNER JOIN Utilisateur ON Utilisateur.id_utilisateur = Annonce.id_utilisateur");

                $requete->execute();

                $requete->setFetchMode(PDO::FETCH_ASSOC);
                $data = $requete->fetchAll();
                $resultat = [];

                foreach($data as $items)
                {
                    $user = new Utilisateur($items['nom'],$items['prenom'],$items['email'],$items['username'],$items['mdp'],$items['admin'],$items['id_utilisateur']);
                    $rub = new Rubrique($items['libelle'],$items['id_rubrique']);
                    $resultat[] = new Annonce($user, $rub, $items['entete'], $items['corps'], $dateD = new DateTime($items['date_depot']), $dateV = new DateTime($items['date_validite']), $items['id_annonce']);                
                }
                return $resultat;

            } catch (PDOException $e) {
                throw new BDDException($e->getMessage(), $e->getCode());
                return -1;
            }
        }

        public function getByRubrique(Rubrique $rubrique) // : tableau d'Annonce(s)
        {
            try {
                $requete = $this->cnx->prepare("SELECT * FROM Annonce 
                INNER JOIN Rubrique ON Rubrique.id_rubrique = Annonce.id_rubrique
                INNER JOIN Utilisateur ON Utilisateur.id_utilisateur = Annonce.id_utilisateur
                WHERE Rubrique.id_rubrique = :id_rubrique");

                $requete->bindValue(':id_rubrique', $rubrique->getId_rubrique());
                $requete->execute();

                $requete->setFetchMode(PDO::FETCH_ASSOC);
                $data = $requete->fetchAll();
                $resultat = [];

                foreach($data as $items)
                {
                    $user = new Utilisateur($items['nom'],$items['prenom'],$items['email'],$items['username'],$items['mdp'],$items['admin'],$items['id_utilisateur']);
                    $rub = new Rubrique($items['libelle'],$items['id_rubrique']);
                    $resultat[] = new Annonce($user, $rub, $items['entete'], $items['corps'], $dateD = new DateTime($items['date_depot']), $dateV = new DateTime($items['date_validite']), $items['id_annonce']);                
                }
                return $resultat;

            } catch (PDOException $e) {
                throw new BDDException($e->getMessage(), $e->getCode());
                return -1;
            }
        }

        public function getByUtilisateur(Utilisateur $utilisateur)
        {
            try {
                $requete = $this->cnx->prepare("SELECT * FROM Annonce 
                INNER JOIN Rubrique ON Rubrique.id_rubrique = Annonce.id_rubrique
                INNER JOIN Utilisateur ON Utilisateur.id_utilisateur = Annonce.id_utilisateur
                WHERE Utilisateur.id_utilisateur = :id_utilisateur");

                $requete->bindValue(':id_utilisateur', $utilisateur->getId_utilisateur());
                $requete->execute();

                $requete->setFetchMode(PDO::FETCH_ASSOC);
                $data = $requete->fetchAll();
                $resultat = [];

                foreach($data as $items)
                {
                    $user = new Utilisateur($items['nom'],$items['prenom'],$items['email'],$items['username'],$items['mdp'],$items['admin'],$items['id_utilisateur']);
                    $rub = new Rubrique($items['libelle'],$items['id_rubrique']);
                    $resultat[] = new Annonce($user, $rub, $items['entete'], $items['corps'], $dateD = new DateTime($items['date_depot']), $dateV = new DateTime($items['date_validite']), $items['id_annonce']);        
                }
                return $resultat;

            } catch (PDOException $e) {
                throw new BDDException($e->getMessage(), $e->getCode());
                return -1;
            }
        }

        public function getById(int $id)    //Retourne tableau de Rubrique
        {
            try {
                $requete = $this->cnx->prepare("SELECT * FROM Annonce 
                INNER JOIN Rubrique ON Rubrique.id_rubrique = Annonce.id_rubrique
                INNER JOIN Utilisateur ON Utilisateur.id_utilisateur = Annonce.id_utilisateur
                WHERE Annonce.id_annonce = :id");

                $requete->bindValue(':id', $id);
                $requete->execute();

                $requete->setFetchMode(PDO::FETCH_ASSOC);
                $data = $requete->fetchAll();
                $resultat = [];

                foreach($data as $items)
                {
                    $user = new Utilisateur($items['nom'],$items['prenom'],$items['email'],$items['username'],$items['mdp'],$items['admin'],$items['id_utilisateur']);
                    $rub = new Rubrique($items['libelle'],$items['id_rubrique']);
                    $resultat = new Annonce($user, $rub, $items['entete'], $items['corps'], $dateD = new DateTime($items['date_depot']), $dateV = new DateTime($items['date_validite']), $items['id_annonce']);        
                }
                return $resultat;

            } catch (PDOException $e) {
                throw new BDDException($e->getMessage(), $e->getCode());
                return -1;
            }
        }

        public function insert(Annonce $annonce) // retourne le id d'annonce généré
        {
            try {
                $this->cnx->beginTransaction();
                $requete = $this->cnx->prepare("INSERT INTO Annonce(id_rubrique,id_utilisateur,entete,corps)
                VALUES (:id_rubrique, :id_utilisateur, :entete, :corps)");

                $requete->bindValue(':id_rubrique', $annonce->getRubrique()->getId_rubrique(), PDO::PARAM_INT);
                $requete->bindValue(':id_utilisateur', $annonce->getUtilisateur()->getId_utilisateur(), PDO::PARAM_STR);
                $requete->bindValue(':entete', $annonce->getEnTete(), PDO::PARAM_STR);
                $requete->bindValue(':corps', $annonce->getCorps(), PDO::PARAM_STR);

                $rowcount = $requete->execute();
                $curId = $this->cnx->lastInsertId();
                $this->cnx->commit();
                return $this->getById($curId);
                
            } catch (PDOException $e) {
                throw new BDDException($e->getMessage(), $e->getCode());
                $this->cnx->rollBack();

                return -1;
            }
        }

        public function delete(Annonce $annonce) : int // retourne le row count, -1 for failure
        {   
            try {
                $this->cnx->beginTransaction();
                $requete = $this->cnx->prepare("DELETE FROM Annonce WHERE id_annonce = :id_annonce AND id_utilisateur = :id_utilisateur");
                $requete->bindValue(':id_annonce', $annonce->getId_annonce());
                $requete->bindValue(':id_utilisateur', $annonce->getUtilisateur()->getId_utilisateur());

                $requete->execute();
                $rowcount = $requete->rowCount();
                $this->cnx->commit();

                return $rowcount;
                
            } catch (PDOException $e) {
                throw new BDDException($e->getMessage(), $e->getCode());
                $this->cnx->rollBack();

                return -1;
            }
        }

        public function update(Annonce $annonce) : int // retourne le row count
        {
            try {
                $this->cnx->beginTransaction();
                $requete = $this->cnx->prepare("UPDATE Annonce SET entete = :entete, corps = :corps WHERE id_annonce = :id_annonce");
                $requete->bindValue(':id_annonce', $annonce->getId_annonce());
                $requete->bindValue(':entete', $annonce->getEnTete());
                $requete->bindValue(':corps', $annonce->getCorps());
                $requete->execute();
                $rowcount = $requete->rowCount();
                $this->cnx->commit();

                return $rowcount;
                
            } catch (PDOException $e) {
                throw new BDDException($e->getMessage(), $e->getCode());
                $this->cnx->rollBack();

                return -1;
            }
        }

        public function updateRubrique($id_Annonce, $id_Rubrique): int // retourne le row count
        {
            try {
                $this->cnx->beginTransaction();
                $requete = $this->cnx->prepare("UPDATE Annonce SET id_rubrique = :id_rubrique where id_annonce = :id_annonce");
                $requete->bindParam(':id_annonce', $id_Annonce);
                $requete->bindParam(':id_rubrique', $id_Rubrique);
                $requete->execute();
                $rowcount = $requete->rowCount();
                $this->cnx->commit();

                return $rowcount;
                
            } catch (PDOException $e) {
                throw new BDDException($e->getMessage(), $e->getCode());
                $this->cnx->rollBack();

                return -1;
            }
        }

        public function deletePerimees() : int// retourne le row count
        {
            try {
                $this->cnx->beginTransaction();
                $requete = $this->cnx->prepare("DELETE FROM Annonce WHERE CURDATE() >= date_validite");
                $requete->execute();
                $rowcount = $requete->rowCount();
                $this->cnx->commit();
                
                return $rowcount;

            } catch (PDOException $e) {
                throw new BDDException($e->getMessage(), $e->getCode());
                $this->cnx->rollBack();

                return -1;
            }
        }
    }

?>