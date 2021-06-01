<?php
//session_start();

	include_once "libs/maLibUtils.php";
	include_once "libs/maLibSQL.pdo.php";
	include_once "libs/maLibSecurisation.php"; 
	include_once "libs/modele.php"; 

	$addArgs = "";

	if ($action = valider("action"))
	{
		ob_start ();
		echo "Action = '$action' <br />";
		// ATTENTION : le codage des caractères peut poser PB si on utilise des actions comportant des accents... 
		// A EVITER si on ne maitrise pas ce type de problématiques

		/* TODO: A REVOIR !!
		// Dans tous les cas, il faut etre logue... 
		// Sauf si on veut se connecter (action == Connexion)

		if ($action != "Connexion") 
			securiser("login");
		*/

		// Un paramètre action a été soumis, on fait le boulot...
		switch($action)
		{
			
			
			// Connexion //////////////////////////////////////////////////
			case 'Connexion' :
				// On verifie la presence des champs login et passe
				if ($login = valider("login"))
				if ($passe = valider("passe"))
				{
					// On verifie l'utilisateur, 
					// et on crée des variables de session si tout est OK
					// Cf. maLibSecurisation
					if (verifUser($login,$passe)) {
						// tout s'est bien passé, doit-on se souvenir de la personne ? 
						if (valider("remember")) {
							setcookie("login",$login , time()+60*60*24*30);
							setcookie("passe",$password, time()+60*60*24*30);
							setcookie("remember",true, time()+60*60*24*30);
						} else {
							setcookie("login","", time()-3600);
							setcookie("passe","", time()-3600);
							setcookie("remember",false, time()-3600);
						}

					}	
					else{
						header("Location:./index.php?view=login&alerte=Les informations fournies ne sont pas valides !");
						die("");
					}
				}

				// On redirigera vers la page index automatiquement
			break;
			case 'Créer un Compte' :
				// On verifie la presence des champs login et passe
				if ($login = valider("login"))
				if ($passe = valider("passe"))
				{
					mkUser($login, $passe);
					// On verifie l'utilisateur, 
					// et on crée des variables de session si tout est OK
					// Cf. maLibSecurisation
					if (verifUser($login,$passe)) {
						copy("ressources\avatars\default.jpg", "ressources\avatars\\".$_SESSION["idUser"].".jpg");
						// tout s'est bien passé, doit-on se souvenir de la personne ? 
						if (valider("remember")) {
							setcookie("login",$login , time()+60*60*24*30);
							setcookie("passe",$password, time()+60*60*24*30);
							setcookie("remember",true, time()+60*60*24*30);
						} else {
							setcookie("login","", time()-3600);
							setcookie("passe","", time()-3600);
							setcookie("remember",false, time()-3600);
						}

					}	
					else{
						header("Location:./index.php?view=login&alerte=Les informations fournies ne sont pas valides !");
						die("");
					}
				}

				// On redirigera vers la page index automatiquement
			break;

			case 'Logout' :
				session_destroy();
			break;

			case 'Changer de pseudo' :
				if ($newpseudo = valider("newpseudo"))
				changerPseudo($_SESSION["idUser"], $newpseudo);
				$_SESSION["pseudo"] = $newpseudo;
				$addArgs .= "?view=profil";
			break;

			case 'Changer d avatar' :
				$dir = "ressources\avatars\\";
				list($width, $height) = getimagesize($_FILES["newavatar"]['tmp_name']);
				$new_width = min($width,$height);
				$new_height = min($width,$height);
				$dstx = ($width-$new_width)/2;
				$dsty = ($height-$new_height)/2;
				$thumb = imagecreatetruecolor($new_width, $new_height);
				$source = imagecreatefromjpeg($_FILES["newavatar"]['tmp_name']);



				imagecopyresampled($thumb, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

				imagejpeg($thumb, $dir.$_SESSION["idUser"].".jpg");
				imagedestroy($thumb);
				imagedestroy($source);


				$addArgs .= "?view=profil";
			break;

			case 'Poster un avis' :
				if ($avis = valider("avis"))
				if ($idMedia = valider("idMedia"))
				if ($mediaType = valider("mediaType"))
				creerAvis($idMedia, $_SESSION["idUser"], $avis, $mediaType);
				$addArgs .= "?view=filmserie&id=$idMedia&media=$mediaType";
			break;

			case 'Noter' :
				if(isset($_REQUEST["note"]))
				if ($idMedia = valider("idMedia"))
				if ($mediaType = valider("mediaType")){
					$note = valider("note");
					creerNote($idMedia, $_SESSION["idUser"], $note, $mediaType);
				}
				$addArgs .= "?view=filmserie&id=$idMedia&media=$mediaType";
			break;

			case 'Marquer comme Visionné' :
				if ($view = valider("view"))
				if ($idMedia = valider("idMedia"))
				if ($mediaType = valider("mediaType"))
				ajouterVisionne($idMedia, $_SESSION["idUser"], $mediaType);
				supprimerWatchlist($idMedia, $_SESSION["idUser"], $mediaType);
				$addArgs .= "?view=$view&id=$idMedia&media=$mediaType";
			break;

			case 'Marquer comme non-vu' :
				if ($view = valider("view"))
				if ($idMedia = valider("idMedia"))
				if ($mediaType = valider("mediaType"))
				supprimerVisionne($idMedia, $_SESSION["idUser"], $mediaType);
				$addArgs .= "?view=$view&id=$idMedia&media=$mediaType";
			break;

			case 'Ajouter aux Favoris' :
				if ($idMedia = valider("idMedia"))
				if ($mediaType = valider("mediaType"))
				ajouterFavoris($idMedia, $_SESSION["idUser"], $mediaType);
				$addArgs .= "?view=filmserie&id=$idMedia&media=$mediaType";
			break;

			case 'Retirer des Favoris' :
				if ($view = valider("view"))
				if ($idMedia = valider("idMedia"))
				if ($mediaType = valider("mediaType"))
				supprimerFavoris($idMedia, $_SESSION["idUser"], $mediaType);
				$addArgs .= "?view=$view&id=$idMedia&media=$mediaType";
			break;

			case 'Ajouter à la Watchlist' :
				if ($idMedia = valider("idMedia"))
				if ($mediaType = valider("mediaType"))
				ajouterWatchlist($idMedia, $_SESSION["idUser"], $mediaType);
				$addArgs .= "?view=filmserie&id=$idMedia&media=$mediaType";
			break;

			case 'Retirer de la Watchlist' :
				if ($idMedia = valider("idMedia"))
				if ($mediaType = valider("mediaType"))
				supprimerWatchlist($idMedia, $_SESSION["idUser"], $mediaType);
				$addArgs .= "?view=watchlist";
			break;

		}

	}

	// On redirige toujours vers la page index, mais on ne connait pas le répertoire de base
	// On l'extrait donc du chemin du script courant : $_SERVER["PHP_SELF"]
	// Par exemple, si $_SERVER["PHP_SELF"] vaut /chat/data.php, dirname($_SERVER["PHP_SELF"]) contient /chat

	$urlBase = dirname($_SERVER["PHP_SELF"]) . "/index.php";
	// On redirige vers la page index avec les bons arguments

	header("Location:" . $urlBase . $addArgs);

	// On écrit seulement après cette entête
	ob_end_flush();
	
?>










