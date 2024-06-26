<?php 
session_start();
ob_start(); 
$requete = $requete_fichePersonne->fetch();

$identite = explode(" ", $requete["personne"])?>


<section class="formulaire_modification">
    <h1>Modification du profil : <br><?=$requete["personne"]?></h1>

    <form action="index.php?action=modifPersonne&id=<?=$requete["id"]?>" method="post">
        <div class="container_formFilm">
            <div class="nom">
                <label for="nom">Nom</label>
                <div class="input_form_nom">
                    <input name="nom" id="nom" type="text" value="<?=$identite[0]?>">
                </div>
            </div>

            <div class="prenom">
                <label for="prenom">Prenom</label>
                <div class="input_form_prenom">
                    <input name="prenom" id="prenom" type="text" value="<?=$identite[1]?>">
                </div>
            </div>

            <div class="sexe">
                <label for="sexe">Sexe</label>
                <div class="input_form_sexe">
                    <input name="sexe" id="sexe" type="text" value="<?=$requete["sexe"]?>">
                </div>
            </div>

            <div class="dateNaissance">
                <label for="date_naissance">Date de sortie du film</label>
                <div class="input_form_dateNaissance">
                    <input name="date_naissance" id="date_naissance" type="date" value="<?=$requete["date_naissance"]?>">
                </div>
            </div>

            <div class="profil">
                <label for="profil">Image de la personne (url de l'image)</label>
                <div class="input_form_urlAffiche">
                    <input name="profil" id="profil" type="url" value="<?=$requete["profil"]?>">
                </div>
            </div>
        </div>
        
        
        <div class="formulaire_modif_button">
            <input class="button_delete" type="submit" name="delete" value="Supprimer">
            <input class="button_validate" type="submit" name="submit" value="Valider">
        </div>
    </form>
</section>

<section class="return_fiche">
    <a href="index.php?action=film_fiche_view&id=<?=$requete["id"]?>">
    <div class="logo_return">
            <i class="ri-arrow-left-line"></i>
        </div>
    </a>
</section>


<?php

$titre = "";
$titre_secondaire = $titre;
$contenu = ob_get_clean();
require_once "template.php";