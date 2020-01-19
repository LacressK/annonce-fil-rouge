<?php

    require_once __DIR__ . '/DAO/ConnexionBDD.php';
    require_once __DIR__ . '/DAO/MySQLRubriqueDAO.php';
    require_once __DIR__ . '/DAO/MySQLUtilisateurDAO.php';
    require_once __DIR__ . '/DAO/MySQLAnnonceDAO.php';
    require_once __DIR__ . '/DAO/MySQLImageDAO.php';
    require_once __DIR__ . '/Metier/Annonce.php';
    require_once __DIR__ . '/Metier/Rubrique.php';
    require_once __DIR__ . '/Metier/Utilisateur.php';
    require_once __DIR__ . '/BDDException.php';
    
    $cnx = ConnexionBdd::getConnexion();

/*---------------------------------------*/
        /* Main MySQLAnnonceDAO */
/*---------------------------------------*/

    $MAD = new MySQLAnnonceDAO($cnx);

    $newUtilisateurA = new Utilisateur('Personne','X','PX@email.fr','PX','secret',FALSE);
    $newRubriqueA = new Rubrique('Bateaux');
    $newUtilisateurB = new Utilisateur('Personne1','X','PX@email.fr','PX','secret',TRUE, 1);
    $newRubriqueB = new Rubrique('Bateaux', 3);

    $newAnnonceA = new Annonce($newUtilisateurA, $newRubriqueA, 'EnteteA', 'CorpsA');
    $newAnnonceB = new Annonce($newUtilisateurB, $newRubriqueB, 'EnteteB', 'CorpsB');
    $newAnnonceC = new Annonce($newUtilisateurB, $newRubriqueB, 'EnteteC', 'CorpsC', null, null, 2);

    $newAnnonceD = new Annonce($newUtilisateurB, $newRubriqueB, 'EnteteD', 'CorpsC', null, new DateTime('2000-01-01'));

    try {

        // print_r ($MAD->getById(1));
        // print_r ($MAD->getByUtilisateur($newUtilisateurB));
        // print_r ($MAD->getByRubrique($newRubriqueB));
        // print_r($MAD->insert($newAnnonceD));
        // print ($MAD->insert($newAnnonceB));
        // $MAD->delete($newAnnonceC);
        // $MAD->update($newAnnonceC);
        // $MAD->deletePerimees();


    } catch(BDDException $e) {
        print 'Erreur '.$e->getCode().' : '.$e->getMessage(); 
    }
/*---------------------------------------*/
        /* Main MySQLUtilisateurDAO */
/*---------------------------------------*/
    
    $MUD = new MySQLUtilisateurDAO($cnx);
    $newUtilisateur1 = new Utilisateur('Michel','Michel','mimi@mail.fr','admin','Admin123');
    $newUtilisateur2 = new Utilisateur('Jacques','Martin','Vivo@email.fr','Vivo','part');
    $newUtilisateur3 = new Utilisateur('Pierre','Paul','PPJ@email.fr','Jacques', 'Avogardro');
    $newUtilisateur4 = new Utilisateur('Essai','Essai','Essai', 'Franklin', 'tortue');

    try {

        // print_r($MUD->insert($newUtilisateur3));
        // print_r($MUD->identifier($newUtilisateur1));

    } catch(BDDException $e) {
        print 'Erreur '.$e->getCode().' : '.$e->getMessage();
    }

/*---------------------------------------*/
        /* Main MySQLRubriqueDAO */
/*---------------------------------------*/
    
    $MRD = new MySQLRubriqueDAO($cnx);

    $newRubBatiments = new Rubrique('Informatique');
    $newRubSports = new Rubrique('Sports', 2);

    try {
    // print_r ($MRD->getAll());
    // print_r($MRD->getById(1));
    // print_r($MRD->insert($newRubSports));
    // $MRD->delete($newRubSports);
    // $MRD->update($newRubSports);

    } catch(BDDException $e) {
        print 'Erreur '.$e->getCode().' : '.$e->getMessage();
    }

    /*-----------------------------------*/
            /* Main MySQLImageDAO /*
    /*-----------------------------------*/

        $MID = new MySQLImageDAO($cnx);

        $newAnnonceF = new Annonce($newUtilisateurB, $newRubriqueB, 'EnteteF', 'CorpsF', null, new DateTime('2000-01-01'), 2);
        $newImage = new Image($newAnnonceF, '/src/Web/image/bdd_stock/P0D3349605G.jpg');

        try {
            // print_r($MID->getByAnnonce($newAnnonceF));
            // print_r($MID->getById(1));
            // print_r($MID->insert($newImage));
            // print $MID->update($newImage);
            // print $MID->delete($newImage);

        } catch(BDDException $e) {
            print 'Erreur '.$e->getCode().' : '.$e->getMessage();
        }

?>