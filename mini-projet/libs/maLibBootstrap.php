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
		$annee = substr($resultat["release_date"],0,4); // on récupère le champ "release_date"
	}
	if($media_type == "tv"){ // si le type de média est série
		$titre = $resultat["name"]; // on récupère le champ "name"
		$annee = substr($resultat["first_air_date"],0, 4); // on récupère le champ "first_air_date"
	}

	if(listerNote($id, $media_type) != 0){
        $note = round(getNote($id, $media_type),2)."<i class='fas fa-star'></i>/5";
        }
    else{
        $note = "Non-noté";
    } 

	//Ici on ne veut récupèrer que les films et les séries
	if($media_type == "tv" || $media_type == "movie"){
		echo "<a href='index.php?view=filmserie&id=$id&media=$media_type' class='resultat_recherche'><img style='height:300px' src='https://image.tmdb.org/t/p/original/$lien_affiche'><div class='info_recherche'><h2>$titre ($annee)</h2><p>$synopsis</p></div><div class='note_recherche'>$note</div></a>";
		//On retourne le "innerHTML" qu'on veut afficher
	}
	}
}


function recupererInfo($tabinfo, $media_type, $tailleimage){
	$tableau = [];
	if($media_type == "movie"){
		$tableau["id"] = $tabinfo["id"];
		$tableau["typeMedia"] = $media_type;
		$tableau["titre"] = $tabinfo["title"];
		$tableau["synopsis"] = $tabinfo["overview"];
		$tableau["lienAffiche"] = "https://image.tmdb.org/t/p/$tailleimage/" . $tabinfo["poster_path"];
		$tableau["date"]["annee"] = substr($tabinfo["release_date"],0,4);
		$tableau["date"]["mois"] = substr($tabinfo["release_date"],5,2);
		$tableau["date"]["jour"] = substr($tabinfo["release_date"],-2);
	}
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
	}

	return $tableau;
}


function afficherAvis($avis){
	$avatar = "ressources/avatars/".$avis['id_user'].".jpg";
	$pseudo = getPseudo($avis["id_user"]);
	$innerHTML = "<div class='avis_global'><img src='$avatar' class='img-circle avis_global_avatar'><div id='avis_global_droite'><h4>$pseudo</h4><p>".$avis["avis"]."</p></div></div>";
	return $innerHTML;
}

function afficherFilmSerie($media, $media_type, $page=""){

	//Envoie de la requête à l'API
	$url = $media["url"]; //Url de requête vers l'API

	//json_decode transforme le JSON obtenu par un objet PHP, file_get_contents récupère l'objet JSON contenu à l'url

	$resultatFavoris = json_decode(file_get_contents($url), true); //On ne récupère que le tableau de résultats

	$tabInformation = recupererInfo($resultatFavoris, $media_type, "w342");
	//tprint($resultatInformations);

	//On récupère leurs valeurs
	$id_media = $tabInformation["id"];
	$titre_media = $tabInformation["titre"];
	$annee = $tabInformation["date"]["annee"];
	$lien_affiche = $tabInformation["lienAffiche"];
			
	$innerHTML_popup = "";

	if($page != ""){
		if($page == "watchlist"){
			$innerHTML_popup = "<p class='media_chevron' id='chevron_$id_media' onclick='afficherMediaPopup(this, event)'><i class='fas fa-chevron-down'></i></p><a href='controleur.php?action=Retirer de la Watchlist&idMedia=$id_media&mediaType=$media_type' class='media_chevron_popup' id='chevron_popup_$id_media'>Retirer de la Watchlist</a>";
		}
		if($page == "favoris"){
			$innerHTML_popup = "<p class='media_chevron' id='chevron_$id_media' onclick='afficherMediaPopup(this, event)'><i class='fas fa-chevron-down'></i></p><a href='controleur.php?action=Retirer des Favoris&idMedia=$id_media&mediaType=$media_type&view=favoris' class='media_chevron_popup' id='chevron_popup_$id_media'>Retirer des Favoris</a>";
		}
	}

	//Ici on ne veut récupèrer que les films et les séries
	echo "<div><a href='index.php?view=filmserie&id=$id_media&media=$media_type' class='media_basic'><div class='media_image'><img src='$lien_affiche'></div><div class='media_title'><h5>$titre_media ($annee)</h5></div></a>$innerHTML_popup</div>";
	//On retourne le "innerHTML" qu'on veut afficher

}

?>

