<?php

/*
Dans ce fichier, on définit diverses fonctions permettant de récupérer des données utiles pour notre TP d'identification. Deux parties sont à compléter, en suivant les indications données dans le support de TP
*/


/********* PARTIE 1 : prise en main de la base de données *********/


// inclure ici la librairie faciliant les requêtes SQL
include_once("maLibSQL.pdo.php");

function verifUserBdd($login,$passe)
{
	// Vérifie l'identité d'un utilisateur 
	// dont les identifiants sont passes en paramètre
	// renvoie faux si user inconnu
	// renvoie l'id de l'utilisateur si succès

	$SQL="SELECT id FROM users WHERE pseudo=BINARY '$login' AND motdepasse=BINARY '$passe'";

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

function isAdmin($idUser){
	$SQL = "SELECT admin FROM users WHERE id = '$idUser'";
	return SQLGetChamp($SQL);
}

function changerPseudo($idUser,$pseudo)
{
	// cette fonction modifie le pseudo d'un utilisateur
	SQLUpdate("UPDATE users SET pseudo='$pseudo' WHERE id='$idUser';");
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

function signalerAvis($idAvis, $table, $idUser){
	$SQL = "INSERT into signalementavis(id_avis, tableavis, id_user) VALUES('$idAvis', '$table', '$idUser')";
	SQLInsert($SQL);
}

function isSignalerAvis($idAvis, $table, $idUser){
	$SQL = "SELECT count(*) FROM signalementavis WHERE id_avis='$idAvis' AND tableavis='$table' AND id_user='$idUser' GROUP BY id_avis";
	return SQLGetChamp($SQL);
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

function supprimerVisionne($idMedia, $idUser, $media_type){
	$SQL = "DELETE FROM visionne WHERE id_film='$idMedia' AND id_user='$idUser' AND type_media='$media_type';";
	SQLDelete($SQL);
}

/********* FAVORIS *********/

function ajouterFavoris($idMedia, $idUser, $media_type){
	$SQL = "INSERT into favoris(id_user, id_film, type_media) VALUES('$idUser', '$idMedia', '$media_type');";
	SQLInsert($SQL);
}

function supprimerFavoris($idMedia, $idUser, $media_type){
	$SQL = "DELETE FROM favoris WHERE id_film='$idMedia' AND id_user='$idUser' AND type_media='$media_type';";
	SQLDelete($SQL);
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
	SQLDelete($SQL);
}

function listerWatchlist($idUser, $media_type){
	$SQL = "SELECT id_film FROM watchlist WHERE id_user='$idUser' AND type_media='$media_type'";
	return parcoursRs(SQLSelect($SQL));
}

function checkWatchlist($idMedia, $idUser, $media_type){
	$SQL = "SELECT COUNT(*) FROM watchlist WHERE id_film='$idMedia' AND id_user='$idUser' AND type_media='$media_type'";
	return SQLGetChamp($SQL);
}

/********* MOMENTS **********/
function creerMoment($idMedia, $media_type, $offset, $label, $resume){
	$SQL = "INSERT into moments(id_film, type_media, offset, label, resume) VALUES('$idMedia', '$media_type', '$offset', '$label', '$resume');";
	SQLInsert($SQL);
}

function recupererMoments($idMedia, $media_type){
	$SQL = "SELECT * FROM moments WHERE id_film = '$idMedia' AND type_media='$media_type'";
	return parcoursRs(SQLSelect($SQL));
}

function getInfoMoment($idMoment){
	$SQL = "SELECT * FROM moments WHERE id = '$idMoment'";
	return parcoursRs(SQLSelect($SQL))[0];
}

function listerAvisMoment($idMoment){
	$SQL = "SELECT * FROM avismoment WHERE id_moment = '$idMoment'";
	return parcoursRs(SQLSelect($SQL));
}

function creerAvisMoment($idMoment, $idUser, $avis){
	$SQL = "INSERT INTO avismoment(id_moment, id_user, avis) VALUES ('$idMoment', '$idUser', '$avis');";
	return SQLInsert($SQL);
}

/********* ADMIN *******/
function getAllSignalements(){
	$SQL = "SELECT * FROM signalementavis";
	return parcoursRs(SQLSelect($SQL));
}

function getAvisById($idAvis, $tableAvis){
	$SQL = "SELECT * FROM $tableAvis WHERE id_avis='$idAvis'";
	return parcoursRs(SQLSelect($SQL))[0];
}

function supprimerAvis($idAvis, $tableAvis){
	$SQL = "DELETE FROM $tableAvis WHERE id_avis='$idAvis';";
	SQLDelete($SQL);
	$SQL = "DELETE FROM signalementavis WHERE id_avis=$idAvis AND tableavis='$tableAvis'";
	SQLDelete($SQL);
}

function laisserAvis($idAvis, $tableAvis){
	$SQL = "DELETE FROM signalementavis WHERE id_avis=$idAvis AND tableavis='$tableAvis'";
	SQLDelete($SQL);
}


?>