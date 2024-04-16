<?php
// On utilise le fichier CinemaController
use Controller\CinemaController;

// On charge l'ensemble des classes du projet
spl_autoload_register(function ($class_name){
    include $class_name . '.php';
});

// On instancie CinemaController
$ctrlCinema = new CinemaController();

// $_GET['action'].........
if(isset($_GET["action"])){
    switch ($_GET["action"]) {
        case "listFilms" : $ctrlCinema->listFilms(); break;
        case "listActeurs" : $ctrlCinema->listActeurs(); break;

        case "home_view"            : $ctrlCinema->viewHome(); break;
        case "film_view"            : $ctrlCinema->viewFilm($_GET["genre"]); break;
        case "realisateur_view"     : $ctrlCinema->viewRealisateur(); break;
        case "acteur_view"          : $ctrlCinema->viewActeur(); break;
        case "add_view"             : $ctrlCinema->viewAdd(); break;
        case "add_personne_view"    : $ctrlCinema->viewAddPersonne(); break;
        case "add_film_view"        : $ctrlCinema->viewAddFilm(); break;
        case "addFilm"              : $ctrlCinema->addFilm(); break;
        case "addPersonne"          : $ctrlCinema->addPersonne(); break;

        case "film_fiche_view"      : $ctrlCinema->viewFicheFilm($_GET["id"]); break;
        case "modif_film"           : $ctrlCinema->viewModifFilm($_GET["id"]); break;
        case "modifFilm"            : $ctrlCinema->ModifFilm($_GET["id"]); break;

        case "personne_fiche_view"  : $ctrlCinema->viewFichePersonne($_GET["id"]); break;
        case "modif_personne"       : $ctrlCinema->viewModifPersonne($_GET["id"]); break;
        case "modifPersonne"        : $ctrlCinema->ModifPersonne($_GET["id"]); break;

        //case "listRole"             : $ctrlCinema->choiceRole(); break;
    }
}