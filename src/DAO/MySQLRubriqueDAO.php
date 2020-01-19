<?php

    require_once __DIR__ . '/DAORubrique.php';
    require_once __DIR__ . '/../Metier/Rubrique.php';
    require_once __DIR__ . '/../BDDException.php';

    class MySQLRubriqueDAO implements DAORubrique {

        private $cnx;

        public function __construct($cnx)
        {
            $this->cnx = $cnx;
        }

        public function __destruct() {
            $this->cnx = null;
        }

        public function getAll()    //Retourne tableau de Rubrique
        {
            try {
                $requete = $this->cnx->prepare("SELECT id_rubrique,libelle FROM Rubrique");
                $requete->execute();

                $requete->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Rubrique');
                $resultat = $requete->fetchAll();
                return $resultat;

            } catch (PDOException $e) {
                throw new BDDException($e->getMessage(), $e->getCode());
            }
        }

        public function getById(int $id)    //Retourne tableau de Rubrique
        {
            try {
                $requete = $this->cnx->prepare("SELECT id_rubrique,libelle FROM Rubrique WHERE id_rubrique = :id");
                $requete->bindValue(':id', $id);
                $requete->execute();

                $requete->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Rubrique');
                $resultat = $requete->fetch();
                return $resultat;

            } catch (PDOException $e) {
                throw new BDDException($e->getMessage(), $e->getCode());
            }
        }

        public function getByLibelle(string $libelle)    //Retourne tableau de Rubrique
        {
            try {
                $requete = $this->cnx->prepare("SELECT * FROM Rubrique WHERE libelle = :libelle");
                $requete->bindValue(':libelle', $libelle);
                $requete->execute();

                $requete->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Rubrique');
                $resultat = $requete->fetch();
                return $resultat;

            } catch (PDOException $e) {
                throw new BDDException($e->getMessage(), $e->getCode());
            }
        }

        public function insert(Rubrique $rubrique)     //return ID of the last INSERT, -1 for failure
        {
            // Essai BeginTransaction / Commit / Rollback functions
            try {
                $this->cnx->beginTransaction();
                $requete = $this->cnx->prepare("INSERT INTO Rubrique(libelle) VALUES (:libelle)");
                $requete->bindValue(':libelle', $rubrique->getLibelle());
                $requete->execute();
                $curId =  $this->cnx->lastInsertId();
                $this->cnx->commit();
                return $this->getById($curId);
                
            } catch (PDOException $e) {
                throw new BDDException($e->getMessage(), $e->getCode());
                $this->cnx->rollBack();

                return -1;
            }
        }

        public function delete(Rubrique $rubrique)     //return rowcount, -1 for failure
        {   
            try {
                $this->cnx->beginTransaction();
                $requete = $this->cnx->prepare("DELETE FROM Rubrique WHERE id_rubrique = :ID");
                $requete->bindValue(':ID', $rubrique->getId_rubrique());
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

        public function update(Rubrique $rubrique) //return row count //(int $id, string $libelle)
        {
            try {
                $this->cnx->beginTransaction();
                $requete = $this->cnx->prepare("UPDATE Rubrique SET libelle = :lib WHERE id_rubrique = :id");
                $requete->bindValue(':id', $rubrique->getId_rubrique());
                $requete->bindValue(':lib', $rubrique->getLibelle());
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