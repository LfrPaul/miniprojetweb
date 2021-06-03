<?php


  include_once "libs/modele.php";
/*
Ce fichier définit diverses fonctions permettant de faciliter la production de mises en formes complexes
Il est utilisé en conjonction avec le style de bootstrap et insère des classes bootstrap
*/

function mkHeadLink($label,$view,$currentView="",$class="")
{
	// fabrique un lien pour l'entête en insèrant la classe 'active' si view = currentView

	// EX: <?=mkHeadLink("Accueil","accueil",$view)
	// produit <li class="active"><a href="index.php?view=accueil">Accueil</a></li> si $view= accueil

	if ($view == $currentView) 
		$class .= " active";
	return "<li class=\"$class\"> <a href=\"index.php?view=$view\">$label</a></li>";
}


function mkFilmserie($resultat){
	if(isset($resultat["overview"]) && $resultat["overview"] != "" && isset($resultat["poster_path"]) && isset($resultat["id"]) && isset($resultat["media_type"])){//Si tout les champs voulus sont déclarés

	//On récupère leurs valeurs
	$id = $resultat["id"];
	$lien_affiche = $resultat["poster_path"];
	$synopsis = $resultat["overview"];
	$media_type = $resultat["media_type"];

	//Si le media_type est movie (film) ou tv (serie) le champ de titre, et de l'annee change
	if($media_type == "movie"){ // si le type de média est film
		$titre = $resultat["title"]; // on récupère le champ "title"
		$annee = "";
		if(isset($resultat["release_date"])){
		$annee = substr($resultat["release_date"],0,4);} // on récupère le champ "release_date"
	}
	if($media_type == "tv"){ // si le type de média est série
		$titre = $resultat["name"]; // on récupère le champ "name"
		$annee = "";
		if(isset($resultat["first_air_date"])){
		$annee = substr($resultat["first_air_date"],0, 4);} // on récupère le champ "first_air_date"
	}


	if(listerNote($id, $media_type) != 0){//Si il y a au moins un note attribuée à ce média on affiche la note
            //round(...,2) arrondi le nombre à la centaine
        $note = round(getNote($id, $media_type),2)."<i class='fas fa-star'></i>/5";
        }
    else{//Sinon on indique que le média n'est pas noté
        $note = "Non-noté";
    } 

    $visionne = "";
    if(isset($_SESSION["connecte"])){
    	if(checkVisionne($id, $_SESSION["idUser"], $media_type)){
    		$visionne = "<div class='visionne_recherche'>Visionné</div>";
    	}

    }

	//Ici on ne veut récupèrer que les films et les séries
	if($media_type == "tv" || $media_type == "movie"){
		echo "<a href='index.php?view=filmserie&id=$id&media=$media_type' class='resultat_recherche'><div class='resultat_recherche_gauche'><img style='height:300px' src='https://image.tmdb.org/t/p/original/$lien_affiche'><div class='info_recherche'><h2>$titre ($annee)</h2><p>$synopsis</p></div></div><div class='resultat_recherche_droite'><div class='note_recherche'>$note</div>$visionne</div></a>";
		
	}
	}
}


function recupererInfo($tabinfo, $media_type, $tailleimage){
	$tableau = [];

	//Comme les films et les séries ne renvoient pas les memes champs, on prend en compte les 2 cas

	//Si le média est un film
	if($media_type == "movie"){
		$tableau["id"] = $tabinfo["id"];
		$tableau["typeMedia"] = $media_type;
		$tableau["titre"] = $tabinfo["title"];
		$tableau["synopsis"] = $tabinfo["overview"];
		$tableau["lienAffiche"] = "https://image.tmdb.org/t/p/$tailleimage/" . $tabinfo["poster_path"];
		$tableau["date"]["annee"] = substr($tabinfo["release_date"],0,4);
		$tableau["date"]["mois"] = substr($tabinfo["release_date"],5,2);
		$tableau["date"]["jour"] = substr($tabinfo["release_date"],-2);
		$tableau["status"] = $tabinfo["status"];
		$tableau["duree"] = $tabinfo["runtime"];
	}

	//Si le média est une série
	if($media_type == "tv"){
		$tableau["id"] = $tabinfo["id"];
		$tableau["typeMedia"] = $media_type;
		$tableau["titre"] = $tabinfo["name"];
		$tableau["synopsis"] = $tabinfo["overview"];
		$tableau["lienAffiche"] = "https://image.tmdb.org/t/p/$tailleimage/" . $tabinfo["poster_path"];
		$tableau["date"]["annee"] = substr($tabinfo["first_air_date"],0,4);
		$tableau["date"]["mois"] = substr($tabinfo["first_air_date"],5,2);
		$tableau["date"]["jour"] = substr($tabinfo["first_air_date"],-2);
		$tableau["nbrSaison"] = $tabinfo["number_of_seasons"];
		$tableau["nbrEpisode"] = $tabinfo["number_of_episodes"];
		$tableau["status"] = $tabinfo["status"];
	}

	return $tableau;
}


function afficherAvis($avis){
	$avatar = "ressources/avatars/".$avis['id_user'].".jpg";//Chemin vers l'avatar de l'utilisateur ayant posté l'avis

	$pseudo = getPseudo($avis["id_user"]); //getPseudo récupère le pseudo en fonction de l'id de l'utilisateur

	//On retourne le innerHTML de l'avis
	$innerHTML = "<div class='avis_global'><img src='$avatar' class='img-circle avis_global_avatar'><div id='avis_global_droite'><h4>$pseudo</h4><p>".$avis["avis"]."</p></div></div>";
	return $innerHTML;
}

function afficherFilmSerie($media, $media_type, $page=""){

	//Envoie de la requête à l'API
	$url = $media["url"]; //Url de requête vers l'API

	//json_decode transforme le JSON obtenu par un objet PHP, file_get_contents récupère l'objet JSON contenu à l'url
	$resultatFavoris = json_decode(file_get_contents($url), true); //On ne récupère que le tableau de résultats

	$tabInformation = recupererInfo($resultatFavoris, $media_type, "w342");//recupererInfo ne retourne que les infos que l'on veut garder
	//tprint($resultatInformations);

	//On récupère leurs valeurs
	$id_media = $tabInformation["id"];
	$titre_media = $tabInformation["titre"];
	$annee = $tabInformation["date"]["annee"];
	$lien_affiche = $tabInformation["lienAffiche"];
			
	$innerHTML_popup = "";

	//Le parametre page permet de savoir sur quelle page on se situe, afin d'afficher la bonne popup
	//Selon les différentes pages, l'action de la popup sera différente
	if($page != ""){
		//Si on se trouve sur watchlist.php
		if($page == "watchlist"){
			$action = "Marquer comme Visionné";
		}
		//Si on se trouve sur favoris.php
		if($page == "favoris"){
			$action = "Retirer des Favoris";
		}
		//Si on se trouve sur profil.php
		if($page == "profil"){
			$action = "Marquer comme non-vu";
		}
		$innerHTML_popup = "<p class='media_chevron' id='chevron_$id_media'><i class='fas fa-chevron-down' onclick='afficherMediaPopup($id_media, event, this)'></i></p><a href='controleur.php?action=$action&idMedia=$id_media&mediaType=$media_type&view=$page' class='media_chevron_popup' id='chevron_popup_$id_media'>$action</a>";
	}

	//On affiche le "innerHTML"
	echo "<div><a href='index.php?view=filmserie&id=$id_media&media=$media_type' class='media_basic'><div class='media_image'><img src='$lien_affiche'></div><div class='media_title'><h5>$titre_media ($annee)</h5></div></a>$innerHTML_popup</div>";

}

?>

