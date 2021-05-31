<?php

/*
Dans ce fichier, on définit diverses fonctions permettant de récupérer des données utiles pour notre TP d'identification. Deux parties sont à compléter, en suivant les indications données dans le support de TP
*/


/********* PARTIE 1 : prise en main de la base de données *********/


// inclure ici la librairie faciliant les requêtes SQL
include_once("maLibSQL.pdo.php");

function listerUtilisateurs($classe = "both")
{
	// Cette fonction liste les utilisateurs de la base de données 
	// et renvoie un tableau d'enregistrements. 
	// Chaque enregistrement est un tableau associatif contenant les champs 
	// id,pseudo,blacklist,connecte,couleur

	// Lorsque la variable $classe vaut "both", elle renvoie tous les utilisateurs
	// Lorsqu'elle vaut "bl", elle ne renvoie que les utilisateurs blacklistés
	// Lorsqu'elle vaut "nbl", elle ne renvoie que les utilisateurs non blacklistés
  $requete = "SELECT * FROM users";
  if ($classe === "bl") {
    $requete = $requete . " WHERE blacklist";
  }
  if ($classe === "nbl") {
    $requete = $requete . " WHERE NOT blacklist";
  }
  $requete = $requete . ";";
  
  //echo $requete;
  return parcoursRs(SQLSelect($requete));
}


function interdireUtilisateur($idUser)
{
	// cette fonction affecte le booléen "blacklist" à vrai pour l'utilisateur concerné 
  $requete = "UPDATE users SET blacklist = '1' WHERE id='$idUser';";
  return SQLUpdate($requete);
}

function autoriserUtilisateur($idUser)
{
	// cette fonction affecte le booléen "blacklist" à faux pour l'utilisateur concerné 
  $requete = "UPDATE users SET blacklist = '0' WHERE id='$idUser';";
  return SQLUpdate($requete);
}

function verifUserBdd($login,$passe)
{
	// Vérifie l'identité d'un utilisateur 
	// dont les identifiants sont passes en paramètre
	// renvoie faux si user inconnu
	// renvoie l'id de l'utilisateur si succès

	$SQL="SELECT id FROM users WHERE pseudo=BINARY '$login' AND motdepasse='$passe'";

	return SQLGetChamp($SQL);
	// si on avait besoin de plus d'un champ
	// on aurait du utiliser SQLSelect
}
function mkUser($pseudo, $passe)
{
	// Cette fonction crée un nouvel utilisateur et renvoie l'identifiant de l'utilisateur créé
	$SQL = "INSERT into users(pseudo, motdepasse) VALUES ('$pseudo', '$passe');";

 	SQLInsert($SQL);
}


function isAdmin($idUser)
{
	// vérifie si l'utilisateur est un administrateur
	$SQL ="SELECT admin FROM users WHERE id='$idUser'";
	return SQLGetChamp($SQL); 
}

function getPseudo($idUser){
	$SQL = "SELECT pseudo FROM users WHERE id='$idUser'";
	return parcoursRs(SQLSelect($SQL))[0]["pseudo"];
}

/********* AVIS/NOTE *********/

function creerAvis($idMedia, $idUser, $avis, $media_type){
	$SQL = "INSERT INTO avisglobal(id_film, id_user, avis, type_media) VALUES ('$idMedia', '$idUser', '$avis', '$media_type');";
	SQLInsert($SQL);
}

function creerNote($idMedia, $idUser, $note, $media_type){
	$SQL = "DELETE FROM notes WHERE id_film='$idMedia' AND id_user='$idUser' AND type_media='$media_type'";
	SQLDelete($SQL);

	$SQL = "INSERT into notes(id_film, id_user, note, type_media) VALUES ('$idMedia', '$idUser', '$note', '$media_type');";
	SQLInsert($SQL);
}

function listerNote($idMedia, $media_type){
	$SQL = "SELECT count(note) FROM notes WHERE id_film='$idMedia' AND type_media='$media_type' GROUP BY id_film";
	return SQLGetChamp($SQL);
}

function getNote($idMedia, $media_type){
	$SQL = "SELECT AVG(note) FROM notes WHERE id_film='$idMedia' AND type_media='$media_type' GROUP BY id_film";
	return SQLGetChamp($SQL);
}

function listerAvis($idFilm, $media_type){
	$SQL = "SELECT * FROM avisglobal WHERE id_film='$idFilm' AND type_media='$media_type'";
	return parcoursRs(SQLSelect($SQL));
}

/********* VISIONNE *********/

function ajouterVisionne($idMedia, $idUser, $media_type){
	$SQL = "INSERT into visionne(id_user, id_film, type_media) VALUES('$idUser', '$idMedia', '$media_type');";
	SQLInsert($SQL);
}

function checkVisionne($idMedia, $idUser, $media_type){
	$SQL = "SELECT COUNT(*) FROM visionne WHERE id_film='$idMedia' AND id_user='$idUser' AND type_media='$media_type'";
	return SQLGetChamp($SQL);
}

function listerVisionne($idUser, $media_type){
	$SQL = "SELECT id_film FROM visionne WHERE id_user='$idUser' AND type_media='$media_type'";
	return parcoursRs(SQLSelect($SQL));
}

/********* FAVORIS *********/

function ajouterFavoris($idMedia, $idUser, $media_type){
	$SQL = "INSERT into favoris(id_user, id_film, type_media) VALUES('$idUser', '$idMedia', '$media_type');";
	SQLInsert($SQL);
}

function supprimerFavoris($idMedia, $idUser, $media_type){
	$SQL = "DELETE FROM favoris WHERE id_film='$idMedia' AND id_user='$idUser' AND type_media='$media_type';";
	SQLInsert($SQL);
}

function listerFavoris($idUser, $media_type){
	$SQL = "SELECT id_film FROM favoris WHERE id_user='$idUser' AND type_media='$media_type'";
	return parcoursRs(SQLSelect($SQL));
}

function checkFavoris($idMedia, $idUser, $media_type){
	$SQL = "SELECT COUNT(*) FROM favoris WHERE id_film='$idMedia' AND id_user='$idUser' AND type_media='$media_type'";
	return SQLGetChamp($SQL);
}

/********* WATCHLIST *********/

function ajouterWatchlist($idMedia, $idUser, $media_type){
	$SQL = "INSERT into watchlist(id_user, id_film, type_media) VALUES('$idUser', '$idMedia', '$media_type');";
	SQLInsert($SQL);
}

function supprimerWatchlist($idMedia, $idUser, $media_type){
	$SQL = "DELETE FROM watchlist WHERE id_film='$idMedia' AND id_user='$idUser' AND type_media='$media_type';";
	SQLInsert($SQL);
}

function listerWatchlist($idUser, $media_type){
	$SQL = "SELECT id_film FROM watchlist WHERE id_user='$idUser' AND type_media='$media_type'";
	return parcoursRs(SQLSelect($SQL));
}

function checkWatchlist($idMedia, $idUser, $media_type){
	$SQL = "SELECT COUNT(*) FROM watchlist WHERE id_film='$idMedia' AND id_user='$idUser' AND type_media='$media_type'";
	return SQLGetChamp($SQL);
}

/********* PARTIE 2 *********/

function connecterUtilisateur($idUser)
{
	// cette fonction affecte le booléen "connecte" à vrai pour l'utilisateur concerné 
  $requete = "UPDATE users SET connecte = '1' WHERE id='$idUser';";
  return SQLUpdate($requete);
}

function deconnecterUtilisateur($idUser)
{
	// cette fonction affecte le booléen "connecte" à faux pour l'utilisateur concerné 
  $requete = "UPDATE users SET connecte = '0' WHERE id='$idUser';";
  return SQLUpdate($requete);
}

function changerCouleur($idUser,$couleur="black")
{
	// cette fonction modifie la valeur du champ 'couleur' de l'utilisateur concerné
	SQLUpdate("UPDATE users SET couleur='$couleur' WHERE id='$idUser';");
}

function changerPasse($idUser,$passe)
{
	// cette fonction modifie le mot de passe d'un utilisateur
	SQLUpdate("UPDATE users SET passe='$passe' WHERE id='$idUser';");
}

function changerPseudo($idUser,$pseudo)
{
	// cette fonction modifie le pseudo d'un utilisateur
	SQLUpdate("UPDATE users SET pseudo='$pseudo' WHERE id='$idUser';");
}

function promouvoirAdmin($idUser)
{
	// cette fonction fait de l'utilisateur un administrateur
  $requete = "UPDATE users SET admin = '1' WHERE id='$idUser';";
  return SQLUpdate($requete);
}

function retrograderUser($idUser)
{
	// cette fonction fait de l'utilisateur un simple mortel
  $requete = "UPDATE users SET admin = '0' WHERE id='$idUser';";
  return SQLUpdate($requete);
}


/********* PARTIE 3 *********/

function listerUtilisateursConnectes()
{
	// Liste les utilisteurs connectes
}

function listerConversations($mode="tout")
{
	// Liste toutes les conversations ($mode="tout")
	// OU uniquement celles actives  ($mode="actives"), ou inactives  ($mode="inactives")
}

function archiverConversation($idConversation)
{
	// rend une conversation inactive
}

function creerConversation($theme)
{
	// crée une nouvelle conversation et renvoie son identifiant
}

function reactiverConversation($idConversation)
{	
	// rend une conversation active

}

function supprimerConversation($idConv)
{
	// supprime une conversation et ses messages

	// NB : on aurait pu aussi demander à mysql de supprimer automatiquement
	// les messages lorsqu'une conversation est supprimée, 
	// en déclarant idConversation comme clé étrangère vers le champ id de la table 
	// des conversations et en définissant un trigger
}


function enregistrerMessage($idConversation, $idAuteur, $contenu)
{
	// Enregistre un message dans la base en encodant les caractères spéciaux HTML : <, > et & pour interdire les messages HTML
}

function listerMessages($idConv,$format="asso")
{
	// Liste les messages de cette conversation, au format JSON ou tableau associatif
	// Champs à extraire : contenu, auteur, couleur 
	// en ne renvoyant pas les utilisateurs blacklistés
	
}

function listerMessagesFromIndex($idConv,$index)
{
	// Liste les messages de cette conversation, 
	// dont l'id est superieur à l'identifiant passé
	// Champs à extraire : contenu, auteur, couleur 
	// en ne renvoyant pas les utilisateurs blacklistés

}

function getConversation($idConv)
{	
	// Récupère les données de la conversation (theme, active)
}



?>
