<?php

    require_once __DIR__ . '/../BDDException.php';
    require_once __DIR__ . '/../DAO/ConnexionBDD.php';
    require_once __DIR__ . '/../DAO/MySQLAnnonceDAO.php';
    require_once __DIR__ . '/../DAO/MySQLUtilisateurDAO.php';
    require_once __DIR__ . '/../DAO/MySQLRubriqueDAO.php';
    require_once __DIR__ . '/../DAO/MySQLImageDAO.php';
    require_once __DIR__ . '/../Metier/Rubrique.php';
    require_once __DIR__ . '/../Metier/Annonce.php';
    require_once __DIR__ . '/../Metier/Utilisateur.php';
    require_once __DIR__ . '/../Metier/Image.php';

    class main {

        private $etatPOST;
        private $etatGET;
        private $twig;
        private $cnx;

        public function __construct() {

            $this->cnx = ConnexionBdd::getConnexion();

            if (!empty($_POST['Etat'])) {
                $this->etatPOST = $_POST['Etat'];
            }
            else if (!empty($_GET['Etat'])) {
                $this->etatGET = $_GET['Etat'];
            }

            require_once (__DIR__. '/../../vendor/autoload.php');
            $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../Vue');
            $this->twig = new \Twig\Environment($loader, ['debug' => true]);
            
            $this->twig->addExtension(new \Twig\Extension\DebugExtension());
            
            if (isset($_SESSION['session'])) {
                $this->twig->addGlobal('session', $_SESSION['session']);
            }
        }

        public function parse() {

            if (!empty($this->etatGET)) {
                switch ($this->etatGET) {
                    case 'afficherAccueil' :
                        $this->afficherAcceuil();
                        break;

                    case 'selectedAnnonce' :
                        $this->selectedAnnonce();
                        break;

                    case 'selectedRubrique' :
                        $this->selectedRubrique();
                        break;

                    case 'afficherRubriques' :
                        $this->afficherRubriques();
                        break;

                    case 'ajouterRubriques' :
                        $this->ajouterRubrique();
                        break;

                    case 'modifierRubriques' :
                        $this->modifierRubrique();
                        break;

                    case 'supprimerRubriques' :
                        $this->supprimerRubrique();
                        break;

                    case 'creerUtilisateurRedirection' :
                        echo $this->twig->render('creerUtilisateur.html.twig');
                        break;

                    case 'creerUtilisateur' :
                        $this->creerUtilisateur();
                        break;

                    case 'connexionUtilisateur' :
                        $this->connexionUtilisateur();
                        break;

                    case 'deconnexionUtilisateur' :
                        $this->deconnexionUtilisateur();
                        break;

                    case 'annoncesUtilisateur' :
                        $this->annoncesUtilisateur();
                        break;

                    case 'creerAnnonceRedirection' :
                        $this->creerAnnonceRedirection();
                        break;

                    case 'creerAnnonce' :
                        $this->creerAnnonce();
                        break;

                    case 'modifierAnnonce' :
                        $this->modifierAnnonce();
                        break;

                    case 'supprimerAnnonce' :
                        $this->supprimerAnnonce();
                        break;

                    // AJAX //

                    case 'listerAnnoncesAjax' :
                        $this->listerAnnoncesAjax();
                        break;
                    
                    // FIN AJAX //

                    default :
                        print 'Pas de page correspondante';
                        break;
                }
            }
        }

        public function afficherAcceuil(string $message = NULL) {
        
            $mad = new MySQLAnnonceDAO($this->cnx);
    
            try {
                $listAnnonces = $mad->getAll();
                
            } catch(BDDException $e) {
                print 'Erreur '.$e->getCode().' : '.$e->getMessage();
            }
            print $this->twig->render('accueil.html.twig', ['annonces' => $listAnnonces, 'message' => $message]);
        }

        private function selectedAnnonce() {
            
            $mad = new MySQLAnnonceDAO($this->cnx);
            $mid = new MySQLImageDAO($this->cnx);

            try {

                $annonce = $mad->getById($_GET['annonceID']);
                $image = $mid->getByAnnonce($annonce);
                // print_r($image);

            } catch(BDDException $e) {
                print 'Erreur '.$e->getCode().' : '.$e->getMessage(); 
            }
            print $this->twig->render('selectedAnnonce.html.twig', ['annonce' => $annonce, 'image' => $image]);
        }

        private function selectedRubrique() {

            $mad = new MySQLAnnonceDAO($this->cnx);
            $rubrique = new Rubrique('',$_GET['rubriqueID']);

            try 
            {
                $annonces = $mad->getByRubrique($rubrique);

            } catch(BDDException $e) {
                print 'Erreur '.$e->getCode().' : '.$e->getMessage(); 
            }
            print $this->twig->render('selectedRubrique.html.twig', ['annonces' => $annonces]);
        }
        
        private function afficherRubriques(string $message = NULL) {

            $mrd = new MySQLRubriqueDAO($this->cnx);
    
            try {
                $listRubrique = $mrd->getAll();
                
            } catch(BDDException $e) {
                print 'Erreur '.$e->getCode().' : '.$e->getMessage();
            }
            echo $this->twig->render('rubriques.html.twig', ['listRubrique' => $listRubrique, 'message' => $message]);
        }
    
        private function ajouterRubrique() {        
    
            $mrd = new MySQLRubriqueDAO($this->cnx);
            $rubrique = new Rubrique($_POST['newRubrique']);
    
            try {
                $mrd->insert($rubrique);
                $message = 'La rubrique '.$_POST['newRubrique'].' a bien était ajoutée.';
            } catch(BDDException $e) {
                if ($e->getCode() == 23000) {
                    $message = 'La rubrique '.$_POST['newRubrique'].' existe déjà.';
                } else {
                    $this->gestionErreur('Erreur '.$e->getCode().' : '.$e->getMessage());
                }
            }
            $this->afficherRubriques($message);
        }
    
        private function modifierRubrique() {
    
            $mrd = new MySQLRubriqueDAO($this->cnx);
            $rubrique = new Rubrique($_POST['updRubrique'],$_GET['ID']);
    
            try {
                $mrd->update($rubrique);
                $message = 'La rubrique a bien était modifiée en '.$_POST['updRubrique'].'.';
            } catch(BDDException $e) {
                if ($e->getCode() == 23000) {
                    $message = 'La rubrique '.$_POST['updRubrique'].' existe déjà.';
                } else {
                    $this->gestionErreur('Erreur '.$e->getCode().' : '.$e->getMessage());
                }
            }
            $this->afficherRubriques($message);
        }
    
        private function supprimerRubrique() {

            if (isset($_SESSION['session']) && isset($_GET['ID'])) {    // Check for identify user
                if ($_SESSION['session']->getAdmin() == TRUE) {     //  Check if identify user is admin

                    $mrd = new MySQLRubriqueDAO($this->cnx);
                    $rubrique = new Rubrique('',$_GET['ID']);
            
                    try {
                        if ($mrd->delete($rubrique)) {
                            $message = 'La rubrique a bien était supprimée.';
                        }

                    } catch(BDDException $e) {
                        if ($e->getCode() == 23000) {
                            $message = 'Opération impossible : La rubrique que vous souhaité supprimer contient des annonces. Pour la supprimer, vous devez d\'abord supprimé les annonces qui lui sont liés.';
                        } else {
                            $this->gestionErreur('Erreur '.$e->getCode().' : '.$e->getMessage());
                        }
                    }
                    $this->afficherRubriques($message);
                } else {
                    $this->gestionErreur('Vous n\'êtes pas administrateur.');
                }
            } else {
                $this->gestionErreur('Vous n\'êtes pas connectez.');
            }
        }
    
        private function creerUtilisateur() {
    
            $utilisateur = new Utilisateur($_POST['nom'],$_POST['prenom'],$_POST['mail'],$_POST['pseudo'], $_POST['mdp']);
            $mud = new MySQLUtilisateurDAO($this->cnx);
    
            try {
                if (gettype($mud->insert($utilisateur)) != NULL) {
                    $message = 'Votre compte à bien était crée.';
                } else {
                    $message = 'Une erreur est survenue : Votre compte n\'à pas pu être crée.';
                }
            } catch(BDDException $e) {
                if ($e->getCode() == 23000) {
                    $message = 'L\'adresse e-mail ou le pseudonyme renseigné est déjà utilisé.';
                } else {
                    $this->gestionErreur('Erreur '.$e->getCode().' : '.$e->getMessage());
                }
            }
            $this->afficherAcceuil($message);
        }
    
        private function connexionUtilisateur() {
    
            $utilisateur = new Utilisateur('','','',$_POST['username'], $_POST['pass']);
            $mud = new MySQLUtilisateurDAO($this->cnx);
    
            try {
                if ($_SESSION['session'] = $mud->identifier($utilisateur)) {
                    if (isset($_SESSION['session'])) {
                        $this->twig->addGlobal('session', $_SESSION['session']);
                        $message = 'Vous vous êtes bien connecter en tant que '.$_SESSION['session']->getUsername().'.';
                    }
                }
            } catch(BDDException $e) {
                if ($e->getCode() == 101) {
                    $message = $e->getMessage();
                } else {
                    $this->gestionErreur('Erreur '.$e->getCode().' : '.$e->getMessage());
                }
            }
            $this->afficherAcceuil($message);
        }

        private function deconnexionUtilisateur() {
            session_destroy();
            header('Location:?Etat=afficherAccueil');
        }

        private function annoncesUtilisateur(string $message = NULL) {
            $mad = new MySQLAnnonceDAO($this->cnx);
            $mrd = new MySQLRubriqueDAO($this->cnx);

            try {
                $listRubriques = $mrd->getAll();
                $annonce = $mad->getByUtilisateur($_SESSION['session']);
            } catch(BDDException $e) {
                $this->gestionErreur('Erreur '.$e->getCode().' : '.$e->getMessage());
            }
            print $this->twig->render('annoncesUtilisateur.html.twig', ['annonce' => $annonce, 'rubriques' => $listRubriques, 'message' => $message]);
        }

        private function creerAnnonceRedirection() {
            $mrd = new MySQLRubriqueDAO($this->cnx);

            try {
                $listRubriques = $mrd->getAll();
            } catch(BDDException $e) {
                print 'Erreur '.$e->getCode().' : '.$e->getMessage(); 
            }
            print $this->twig->render('creerAnnonce.html.twig', ['rubriques' => $listRubriques]);
        }

        private function creerAnnonce() {
            $mad = new MySQLAnnonceDAO($this->cnx);
            $mrd = new MySQLRubriqueDAO($this->cnx);

            try {

                $rubrique = $mrd->getByLibelle($_POST['selectionRub']);
                $dateV = new DateTime($_POST['dateV']);
                $createdAnnonce = new Annonce($_SESSION['session'], $rubrique, $_POST['entete'], $_POST['corps'], NULL, $dateV);
                
                if ($mad->insert($createdAnnonce)) {
                    $message = 'Votre annonce à bien était crée.';
                }

            }catch(BDDException $e) {
                $this->gestionErreur('Erreur '.$e->getCode().' : '.$e->getMessage());
            }
            $this->annoncesUtilisateur($message);
        }

        private function modifierAnnonce() {
            $mad = new MySQLAnnonceDAO($this->cnx);
            $mrd = new MySQLRubriqueDAO($this->cnx);

            try {

                $rubrique = $mrd->getByLibelle($_POST['selectionRub']);
                $annonceEdit = new Annonce($_SESSION['session'], $rubrique, $_POST['entete'], $_POST['corps'], NULL, NULL, $_GET['modifierAnnonceID']);

                if ($mad->update($annonceEdit) == 1) {
                    if ($mad->updateRubrique($_GET['modifierAnnonceID'], $rubrique->getId_rubrique()) == 1) {
                        $message = 'Votre annonce à bien était modifié en '.$_POST['entete'];
                    } else { $message = 'Une erreur est survenue : Votre annonce n\'à pu être modifié.'; }
                } else { $message = 'Une erreur est survenue : Votre annonce n\'à pu être modifié.'; }
                
            } catch(BDDException $e) {
                $this->gestionErreur('Erreur '.$e->getCode().' : '.$e->getMessage());
            }
            $this->annoncesUtilisateur($message);
        }

        private function supprimerAnnonce() {

            if (isset($_SESSION['session']) && isset($_GET['supprimerAnnonceID'])) {    
                $mad = new MySQLAnnonceDAO($this->cnx);
                $rubrique = new Rubrique();
                $annonceSupp = new Annonce($_SESSION['session'], $rubrique, "", "", NULL, NULL, $_GET['supprimerAnnonceID']);

                try {
                    /*Utiliser le retour 'rowcount' de la fonction 'delete' pour confirmer ou infirmer le succes du delete*/
                    if ($mad->delete($annonceSupp) == 1) {
                        $message = 'Vous avez supprimé votre annonce avec succés.';
                    } else {
                        $message = 'La suppression n\'a pas aboutie.';
                    }
                } catch(BDDException $e) {
                    $this->gestionErreur('Erreur '.$e->getCode().' : '.$e->getMessage());
                }
                $this->annoncesUtilisateur($message);
            }
            else {
                $this->gestionErreur('Vous n\'êtes pas connectez.');
            }
        }

        private function gestionErreur(string $messageErreur) {
            print $this->twig->render('erreur.html.twig', ['messageErreur' => $messageErreur]);
            exit();
        }

        // AJAX //

        public function listerAnnoncesAjax() {

            $mrd = new MySQLRubriqueDAO($this->cnx);
            $mad = new MySQLAnnonceDAO($this->cnx);
    
            try {
                $listRubrique = $mrd->getAll();
                if(isset($_GET['rubrique']) AND !empty($_GET['rubrique'])) {
                    $id_rubrique = $_GET['rubrique'];
                    $rubrique = new Rubrique('', $id_rubrique);
                    $annonces = $mad->getByRubrique($rubrique);
                    echo json_encode($annonces, JSON_UNESCAPED_UNICODE);
                    exit();
                }
                echo $this->twig->render('listerAnnoncesAjax.html.twig', ['listRubrique' => $listRubrique]);
                
            } catch(BDDException $e) {
                print 'Erreur '.$e->getCode().' : '.$e->getMessage();
            }
        }

        // FIN AJAX //
    }

?>