<?php

  include_once "libs/maLibUtils.php";
  include_once "libs/maLibForms.php";
// Si la page est appelée directement par son adresse, on redirige en passant pas la page index
if (basename($_SERVER["PHP_SELF"]) != "index.php" || !$_SESSION["connecte"])
{
	header("Location:./index.php?view=login&alerte=Tu dois être connecté pour accèder à cette page !");
	die("");
}

?>

<div class="page-header">
	<h1>Mon Profil</h1>
</div>

<div id="profil">
  <img class="img-circle" src="ressources/avatars/<?php echo $_SESSION["idUser"]?>.jpg" id="avatar">
  <?php
  mkForm("controleur.php","POST","multipart/form-data");
  mkInput("file","newavatar","");
  mkInput("submit","action","Changer d avatar","bouton_submit");
  endForm();
  ?>
  <h2 id="pseudonyme"><?php echo $_SESSION["pseudo"];?></h2>
  <?php
  mkForm("controleur.php");
  mkInput("text","newpseudo","","champ_texte");
  mkInput("submit","action","Changer de pseudo","bouton_submit");
  endForm();
  ?>

</div>


<div class="page-subtitle">
  <h2>Films Visionnés</h2>
</div>

<div id="liste_visionne_movie" class="liste_media_basic">
  <?php
    $visionne_list = listerVisionne($_SESSION["idUser"], "movie");
    foreach ($visionne_list as $nombre => $visionne) {
      $id_media = $visionne["id_film"];
      $url = "https://api.themoviedb.org/3/movie/$id_media?api_key=83354d9b54af3ca7b64d6dc86ddac815&language=fr-FR";
      $visionne_list[$nombre]["url"] = $url;
      $visionne["url"] = $url;
      afficherFilmSerie($visionne, "movie");
    }
  ?>
</div>

<div class="page-subtitle">
  <h2>Séries Visionnées</h2>
</div>

<div id="liste_visionne_tv" class="liste_media_basic">
  <?php
    $visionne_list = listerVisionne($_SESSION["idUser"], "tv");
    foreach ($visionne_list as $nombre => $visionne) {
      $id_media = $visionne["id_film"];
      $url = "https://api.themoviedb.org/3/tv/$id_media?api_key=83354d9b54af3ca7b64d6dc86ddac815&language=fr-FR";
      $visionne_list[$nombre]["url"] = $url;
      $visionne["url"] = $url;
      afficherFilmSerie($visionne, "tv");
    }
  ?>
</div>