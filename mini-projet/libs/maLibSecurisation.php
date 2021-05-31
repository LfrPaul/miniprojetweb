<?php

include_once "maLibUtils.php";	// Car on utilise la fonction valider()
include_once "modele.php";
session_start();

/**
 * @file login.php
 * Fichier contenant des fonctions de vérification de logins
 */

/**
 * Cette fonction vérifie si le login/passe passés en paramètre sont légaux
 * Elle stocke le pseudo de la personne dans des variables de session : session_start doit avoir été appelé...
 * Elle enregistre aussi une information permettant de savoir si l'utilisateur qui se connecte est administrateur ou non
 * Elle enregistre l'état de la connexion dans une variable de session "connecte" = true
 * @pre login et passe ne doivent pas être vides
 * @param string $login
 * @param string $password
 * @return false ou true ; un effet de bord est la création de variables de session
 */
function verifUser($login,$password)
{
  $idUser = verifUserBdd($login, $password);
  if ($idUser) {
    // On débute une session (nouvelle session ou réutiliser l'ancienne session)
    $_SESSION["idUser"] = $idUser;
    $_SESSION["pseudo"] = $login;
    $_SESSION["connecte"] = true;
    //$_SESSION["admin"] = isAdmin($idUser);
    //connecterUtilisateur($idUser);
    return true;
  } else {
    return false;
  }
}




/**
 * Fonction à placer au début de chaque page privée
 * Cette fonction redirige vers la page $urlBad en envoyant un message d'erreur 
	et arrête l'interprétation si l'utilisateur n'est pas connecté
 * Elle ne fait rien si l'utilisateur est connecté, et si $urlGood est faux
 * Elle redirige vers urlGood sinon
 */
function securiser($urlBad,$urlGood=false)
{
	if (!isset($_SESSION["idUser"])) {
	  // Cas utilisateur non connecté : on redirige vers $urlBad
	  rediriger($urlBad, array("msg" => "Accès interdit : connexion requise"));
	} else {
	  // Cas utilisateur connecté
	  if ($urlGood) {
	    // Si une page de redirection $urlGood est spécifiée
  	  rediriger($urlGood);
	  }
	}
}

// Déconnecte un utilisateur

function seDeconnecter()
{
  $idUser = $_SESSION["idUser"];
  deconnecterUtilisateur($idUser);
  session_destroy();
  rediriger("login.php", array("msg" => "Déconnexion effectuée avec succès"));
}






?>
