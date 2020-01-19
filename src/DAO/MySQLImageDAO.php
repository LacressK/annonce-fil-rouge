<?php

    require_once __DIR__ . '/DAOImage.php';
    require_once __DIR__ . '/../Metier/Annonce.php';
    require_once __DIR__ . '/../Metier/Utilisateur.php';
    require_once __DIR__ . '/../Metier/Rubrique.php';
    require_once __DIR__ . '/../Metier/Image.php';
    require_once __DIR__ . '/../BDDException.php';

    class MySQLImageDAO implements DAOImage {

        private $cnx;

        public function __construct($cnx)   //Constructor
        {
            $this->cnx = $cnx;
        }

        public function __destruct() {      // Destructor
            $this->cnx = null;
        }

        public function getByAnnonce(\Annonce $annonce)
        {
            try {
                $requete = $this->cnx->prepare('SELECT * FROM Image
                WHERE Image.id_annonce = :id_annonce');

                $requete->bindValue(':id_annonce', $annonce->getId_annonce());
                $requete->execute();

                $requete->setFetchMode(PDO::FETCH_ASSOC);
                $data = $requete->fetchAll();

                $resultat = [];

                foreach($data as $items)
                {
                    $resultat[] = new Image($annonce, $items['chemin'], $items['id_image']);
                }
                return $resultat;

            } catch (PDOException $e) {
                throw new BDDException($e->getMessage(), $e->getCode());
                return -1;
            }
        }

        public function getById(int $id_image) {
            try {
                $requete = $this->cnx->prepare('SELECT * FROM Image
                INNER JOIN Annonce ON Annonce.id_annonce = Image.id_annonce
                INNER JOIN Rubrique ON Rubrique.id_rubrique = Annonce.id_rubrique
                INNER JOIN Utilisateur ON Utilisateur.id_utilisateur = Annonce.id_utilisateur
                WHERE Image.id_image = :id_image');
                $requete->bindValue(':id_image', $id_image);
                $requete->execute();
                $requete->setFetchMode(PDO::FETCH_ASSOC);
                $data = $requete->fetchAll();

                foreach($data as $items)
                {
                    $user = new Utilisateur($items['nom'],$items['prenom'],$items['email'],$items['username'],$items['mdp'],$items['admin'],$items['id_utilisateur']);
                    $rub = new Rubrique($items['libelle'],$items['id_rubrique']);
                    $annonce = new Annonce($user, $rub, $items['entete'], $items['corps'], $dateD = new DateTime($items['date_depot']), $dateV = new DateTime($items['date_validite']), $items['id_annonce']); 
                    $resultat = new Image($annonce, $items['chemin'], $items['id_image']);
                }
                return $resultat;

            } catch(PDOException $e) {
                throw new BDDException($e->getMessage(), $e->getCode());
                return -1;
            }
        }

        public function insert(\Image $image)
        {
            try {
            $this->cnx->beginTransaction();
            $requete = $this->cnx->prepare('INSERT INTO Image(id_annonce, chemin) VALUES(:id_annonce, :chemin)');
            $requete->bindValue(':id_annonce', $image->getAnnonce()->getId_annonce());
            $requete->bindValue(':chemin', $image->getChemin());

            $requete->execute();
            $curId = $this->cnx->lastInsertId();
            $this->cnx->commit();
            return $this->getById($curId);
                
            } catch (PDOException $e) {
                throw new BDDException($e->getMessage(), $e->getCode());
                $this->cnx->rollBack();

                return -1;
            }
        }

        public function update(\Image $image)
        {
            try {
                $this->cnx->beginTransaction();
                $requete = $this->cnx->prepare('UPDATE Image SET chemin = :chemin WHERE id_image = :id_image');
                $requete->bindValue(':chemin', $image->getChemin());
                $requete->bindValue(':id_image', $image->getId_image());

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

        public function delete(\Image $image)
        {
            try {
                $this->cnx->beginTransaction();
                $requete = $this->cnx->prepare("DELETE FROM Image WHERE id_image = :id_image");
                $requete->bindValue(':id_image', $image->getId_image());

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