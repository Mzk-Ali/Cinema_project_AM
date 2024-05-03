<?php

namespace Controller;
use Model\Connect;

class CinemaController {

/* ----------------------------------- Manage BDD ---------------------------------------------- */

    // Execution et recuperation donnée par requête
    public function execAndRecovery($requete){
        // Connexion à la base de données voulu
        $pdo = Connect::seConnecter();
        // Execute la requête voulu
        $requete_recovery = $pdo->query("$requete");
        return $requete_recovery;
    }

    // Execution et recuperation donnée par requête avec variable
    public function prepAndExecAndRecovery($requete, $var_exec){
        // Connexion à la base de données voulu
        $pdo = Connect::seConnecter();
        // Prepare la requête
        $requete_recovery = $pdo->prepare("$requete");
        // Execute avec les variables présents dans la requête
        $requete_recovery->execute($var_exec);
        return $requete_recovery;
    }
/* --------------------------------------------------------------------------------------------- */


/* ------------- Ensemble des requêtes contenant des infos utiles pour les views --------------- */

    // Liste Film
    public function listFilms($filtre, $genre) {
        $requete_prepare = "
                SELECT titre, date_sortie, affiche_film, film.id_film AS id
                FROM film
                ";
        $var_exec = array();

        if($genre == "defaut" && $filtre == "defaut")
        {            
            $requete_recovery = $this->execAndRecovery($requete_prepare);
        }
        else{
            if($genre != "defaut")
            {
                $requete_prepare .= '
                    INNER JOIN gestion_genre
                    ON film.id_film = gestion_genre.id_film
                    INNER JOIN genre
                    ON gestion_genre.id_genre = genre.id_genre
                    WHERE genre.genre = :genre';
                $var_exec[":genre"] = "$genre";
            }
            if($filtre != "defaut")
            {
                if($filtre == "note"){
                    $requete_prepare .= 'ORDER BY film.note DESC';
                }
                else if($filtre == "date_sortie"){
                    $requete_prepare .= 'ORDER BY film.date_sortie DESC';
                }
                else if($filtre == "duree"){
                    $requete_prepare .= 'ORDER BY film.duree DESC';
                }
            }
            $requete_recovery = $this->prepAndExecAndRecovery($requete_prepare, $var_exec);
        }
        return $requete_recovery;
    }

    // Liste des personnes présents dans la base de données
    public function listPersonne(){
        $requete = "
                SELECT CONCAT(nom, ' ',prenom) AS personne, personne.id_personne
                FROM personne
                ";
        $requete_recovery = $this->execAndRecovery($requete);
        return $requete_recovery;
    }

    // Liste Realisateur
    public function listRealisateursWithFiltre($filtre) {
        $requete = "SELECT ";
        if($filtre != "defaut"){
            if($filtre == "nombre"){
                $requete .= "COUNT(id_film) AS filtre, ";
            }
            else if($filtre == "note"){
                $requete .= "AVG(note) AS filtre, ";
            }
            else{
                $requete .= "personne.date_naissance AS filtre, ";
            }
        }
         
        $requete .= "CONCAT(nom, ' ',prenom) AS personne, realisateur.id_personne AS id, realisateur.id_realisateur AS id_realisateur, profil
            FROM film
            INNER JOIN realisateur
            ON film.id_realisateur = realisateur.id_realisateur
            INNER JOIN personne
            ON realisateur.id_personne = personne.id_personne
            GROUP BY realisateur.id_realisateur
            ";
        if($filtre != "defaut")
        {
            $requete .= "ORDER BY filtre DESC";

            $requete_recovery = $this->execAndRecovery($requete);
        }
        else
        {
            $requete_recovery = $this->execAndRecovery($requete);
        }
        return $requete_recovery;
    }


    // Liste Realisateur
    public function listRealisateurs() {
        $requete = "
            SELECT CONCAT(nom, ' ',prenom) AS personne, realisateur.id_personne AS id, realisateur.id_realisateur AS id_realisateur, profil
            FROM personne, realisateur
            WHERE realisateur.id_personne = personne.id_personne
            ";
        $requete_recovery = $this->execAndRecovery($requete);
        return $requete_recovery;
    }

    // Liste Acteur
    public function listActeurs() {
        $requete = "
            SELECT CONCAT(nom, ' ',prenom) AS personne, acteur.id_personne AS id, profil
            FROM acteur, personne
            WHERE acteur.id_personne = personne.id_personne
            ";
        $requete_recovery = $this->execAndRecovery($requete);
        return $requete_recovery;
    }

    // Liste de tous les genres de film
    public function listGenre(){
        $requete = "
            SELECT *
            FROM genre
            ";
        $requete_recovery = $this->execAndRecovery($requete);
        return $requete_recovery;
    }

    // Liste de tous les roles présents dans la base de données
    public function listRole(){
        $requete = "
            SELECT *
            FROM role
            ";
        $requete_recovery = $this->execAndRecovery($requete);
        return $requete_recovery;
    }

    // Liste Acteur et son rôle selon l'id d'un film
    public function listActeursAndRoleperFilm($id) {
        $requete_prepare = "
            SELECT CONCAT(nom, ' ',prenom) AS personne, nom_personnage, acteur.id_personne AS id, profil
            FROM contrat
            INNER JOIN acteur
            ON contrat.id_acteur = acteur.id_acteur
            INNER JOIN personne
            ON acteur.id_personne = personne.id_personne
            INNER JOIN role
            ON contrat.id_role = role.id_role
            WHERE id_film = :id";
        $var_exec["id"] = "$id";

        $requete_recovery = $this->prepAndExecAndRecovery($requete_prepare, $var_exec);
        return $requete_recovery;
    }

    // Liste Film et rôle joué selon l'id de la personne
    public function listFilmsAndRoleperActeur($id){
        $requete_prepare = "
            SELECT titre, nom_personnage, affiche_film, film.id_film AS id
            FROM film
            INNER JOIN contrat
            ON film.id_film = contrat.id_film
            INNER JOIN role
            ON contrat.id_role = role.id_role
            INNER JOIN acteur
            ON contrat.id_acteur = acteur.id_acteur
            WHERE acteur.id_personne = :id
        ";
        $var_exec["id"] = "$id";

        $requete_recovery = $this->prepAndExecAndRecovery($requete_prepare, $var_exec);
        return $requete_recovery;
    }

    // Liste genre d'un Film selon son id
    public function genreFilm($id){
        $requete_prepare = "
            SELECT genre.genre, genre.id_genre
            FROM gestion_genre, genre
            WHERE gestion_genre.id_genre = genre.id_genre
            AND gestion_genre.id_film = :id";
        $var_exec["id"] = "$id";
        
        $requete_recovery = $this->prepAndExecAndRecovery($requete_prepare, $var_exec);
        return $requete_recovery;
    }

    // Fiche Film
    public function ficheFilm($id){
        $requete_prepare = "
            SELECT titre, affiche_film, note, synopsis, date_sortie, duree, CONCAT(nom, ' ',prenom) AS personne, film.id_film AS id
            FROM film
            INNER JOIN realisateur
            ON film.id_realisateur = realisateur.id_realisateur
            INNER JOIN personne
            ON realisateur.id_personne = personne.id_personne
            WHERE film.id_film = :id";
        $var_exec["id"] = "$id";

        $requete_recovery = $this->prepAndExecAndRecovery($requete_prepare, $var_exec);
        return $requete_recovery;
    }

    // Fiche Personne
    public function fichePersonne($id) {
        $requete_prepare = "
            SELECT CONCAT(nom, ' ',prenom) AS personne, sexe,  date_naissance, profil, personne.id_personne AS id
            FROM personne
            WHERE personne.id_personne = :id";
        $var_exec["id"] = "$id";

        $requete_recovery = $this->prepAndExecAndRecovery($requete_prepare, $var_exec);
        return $requete_recovery;
    }

    // Filmographie en tant que réalisateur
    public function listFilmsPerRealisateur($id){
        $requete_prepare = "
            SELECT titre, affiche_film, film.id_film AS id
            FROM film
            INNER JOIN realisateur
            ON film.id_realisateur = realisateur.id_realisateur
            INNER JOIN personne
            ON realisateur.id_personne = personne.id_personne
            WHERE realisateur.id_personne = :id";
        $var_exec["id"] = "$id";

        $requete_recovery = $this->prepAndExecAndRecovery($requete_prepare, $var_exec);
        return $requete_recovery;
    }

    // Filmographie en tant qu'acteur
    public function listFilmsPerActeur($id){
        $requete_prepare = "
            SELECT titre, affiche_film, film.id_film AS id
            FROM film
            INNER JOIN contrat
            ON film.id_film = contrat.id_film
            INNER JOIN acteur
            ON contrat.id_acteur = acteur.id_acteur
            INNER JOIN personne
            ON acteur.id_personne = personne.id_personne
            WHERE personne.id_personne = :id";
        $var_exec["id"] = "$id";

        $requete_recovery = $this->prepAndExecAndRecovery($requete_prepare, $var_exec);
        return $requete_recovery;
    }

    // Liste acteurs en fonction du role envoyé par formulaire
    public function acteursPerRole(){
        if(isset($_POST['submit']) && $_POST["role"] != "")
        {
            $nom_personnage    = filter_input(INPUT_POST, "role", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $requete_prepare = "
                    SELECT CONCAT(nom, ' ',prenom) AS personne, acteur.id_personne AS id, profil
                    FROM personne
                    INNER JOIN acteur
                    ON personne.id_personne = acteur.id_personne
                    INNER JOIN contrat
                    ON acteur.id_acteur = contrat.id_acteur
                    INNER JOIN role
                    ON contrat.id_role = role.id_role
                    WHERE nom_personnage = :nom_personnage";

            $var_exec["nom_personnage"] = "$nom_personnage";
            $requete_recovery = $this->prepAndExecAndRecovery($requete_prepare, $var_exec);
            return $requete_recovery;
        }
        else{
            return NULL;
        }
    }

    // Infos supplémentaire du réalisateur
    public function infosRealisateur($id){
        $requete_prepare = "
            SELECT film.titre, film.date_sortie
            FROM film, realisateur
            WHERE film.id_realisateur = realisateur.id_realisateur
            AND realisateur.id_personne = :id
            ORDER BY film.date_sortie";
        $var_exec["id"] = "$id";

        $requete_recovery = $this->prepAndExecAndRecovery($requete_prepare, $var_exec);
        return $requete_recovery;
    }

    // Infos supplémentaire de l'acteur
    public function infosActeur($id){
        $requete_prepare = "
            SELECT film.titre, film.date_sortie
            FROM film
            INNER JOIN contrat
            ON film.id_film = contrat.id_film
            INNER JOIN acteur
            ON contrat.id_acteur = acteur.id_acteur
            INNER JOIN personne
            ON acteur.id_personne = personne.id_personne
            WHERE personne.id_personne = :id
            ORDER BY film.date_sortie";
        $var_exec["id"] = "$id";

        $requete_recovery = $this->prepAndExecAndRecovery($requete_prepare, $var_exec);
        return $requete_recovery;
    }

/* --------------------------------------------------------------------------------------------- */






/* ----------------------------------- Control VIEW -------------------------------------------- */

    // Fonction qui retourne toutes les réponses de requête utiles pour la vue HOME
    public function viewHome() {
        // Liste des films 
        $requete_listFilms                  = $this->listFilms("defaut", "defaut");
        // Liste des Réalisateurs
        $requete_listRealisateurs           = $this->listRealisateursWithFiltre("defaut");
        // Liste des Acteurs
        $requete_listActeurs                = $this->listActeurs();
        require "view/viewHome.php";
    }

    // Fonction qui retourne toutes les réponses de requête utiles pour la vue FILM
    public function viewFilm($genre) {
        // Liste des films selon la base de données SQL
        $requete_listFilms                  = $this->listFilms("defaut", "defaut");
        // Liste des films selon la note
        $requete_listFilmsPerNote           = $this->listFilms("note", "defaut");
        // Liste des films selon la date de sortie
        $requete_listFilmsPerDateSortie     = $this->listFilms("date_sortie", "defaut");
        // Liste des films selon la durée
        $requete_listFilmsPerDuree          = $this->listFilms("duree", "defaut");
        // Liste des films selon la genre
        $requete_listFilmsPerGenre          = $this->listFilms("defaut", $genre);
        // Liste de tous les genres de film
        $requete_listGenre                  = $this->listGenre();
        require "view/viewFilm.php";
    }

    // Fonction qui retourne toutes les réponses de requête utiles pour la vue REALISATEUR
    public function viewRealisateur() {
        // Liste des réalisateurs selon la base de données SQL
        $requete_listRealisateurs           = $this->listRealisateursWithFiltre("defaut");
        // Liste des réalisateurs selon le nombre de film
        $requete_listRealisateursPerNbr     = $this->listRealisateursWithFiltre("nombre");
        // Liste des réalisateurs selon la note
        $requete_listRealisateursPerNote    = $this->listRealisateursWithFiltre("note");
        require "view/viewRealisateur.php";
    }

    // Fonction qui retourne toutes les réponses de requête utiles pour la vue ACTEUR
    public function viewActeur(){
        // Liste des Acteurs
        $requete_listActeurs                = $this->listActeurs();
        // Liste des roles
        $requete_listRoles                  = $this->listRole();
        // Liste des acteurs selon le role
        $requete_listActeursPerRole         = $this->acteursPerRole();
        require "view/viewActeur.php";
    }

    // Fonction qui s'occupe de la vue d'ajout
    public function viewAdd(){
        $alert_message  = "Il s'agit d'un test";
        $alert_type     = "warning";
        require "view/viewAdd.php";
    }

    // Fonction qui s'occupe de la vue d'ajout Personne
    public function viewAddPersonne(){
        require "view/viewAddPersonne.php";
    }

    // Fonction qui s'occupe de la vue d'ajout Film et retourne toutes les réponses de requête utiles pour son affichage
    public function viewAddFilm(){
        // Liste des realisateurs contenu dans la base de données pour le choix du réalisateur lors de la modification
        $requete_listRealisateurs       = $this->listRealisateurs();
        // Liste des genres présents dans la base de données
        $requete_listGenre              = $this->listGenre();
        require "view/viewAddFilm.php";
    }

    // Fonction qui retourne toutes les réponses de requête utiles pour l'affichage de la Fiche d'un Film
    public function viewFicheFilm($id) {
        // Ensemble des informations du film
        $requete_ficheFilm              = $this->ficheFilm($id);
        // Liste des genres selon l'id du film
        $requete_genre_film             = $this->genreFilm($id);
        // Liste des Acteurs et leur role dans le film selon l'id du film
        $requete_listActeursThisFilm    = $this->listActeursAndRoleperFilm($id);
        require "view/ficheFilm.php";
    }

    // Fonction qui retourne toutes les réponses de requête utiles pour l'affichage de la Fiche d'une Personne
    public function viewFichePersonne($id) {
        // Ensemble des informations d'une Personne selon son id
        $requete_fichePersonne              = $this->fichePersonne($id);
        // Liste des films réalisés par un réalisateur selon son id
        $requete_listFilmsPerRealisateur    = $this->listFilmsPerRealisateur($id);
        // Liste des films dont un acteur est présent selon son id
        $requete_listFilmsAndRolePerActeur  = $this->listFilmsAndRoleperActeur($id);
        // Informations Réalisateur
        $requete_infosRealisateur           = $this->infosRealisateur($id);
        // Informations Acteur
        $requete_infosActeur                = $this->infosActeur($id);
        require "view/fichePersonne.php";
    }
    
    // Fonction qui retourne toutes les réponses de requête utiles pour l'affichage de la Modification d'un film
    public function viewModifFilm($id){
        // Liste des realisateurs contenu dans la base de données pour le choix du réalisateur lors de la modification
        $requete_listRealisateurs       = $this->listRealisateurs();
        // Ensemble des données d'un film selon l'id
        $requete_ficheFilm              = $this->ficheFilm($id);
        // Liste des genres présents dans la base de données
        $requete_listGenre              = $this->listGenre();
        // Liste des genres selon l'id du film
        $requete_genre_film             = $this->genreFilm($id);
        require "view/filmModif.php";
    }

    // Fonction qui retourne toutes les réponses de requête utiles pour l'affichage de la Modification d'une Personne
    public function viewModifPersonne($id){
        // Ensemble des informations d'une Personne selon son id
        $requete_fichePersonne          = $this->fichePersonne($id);
        require "view/personneModif.php";
    }

/* --------------------------------------------------------------------------------------------- */
}