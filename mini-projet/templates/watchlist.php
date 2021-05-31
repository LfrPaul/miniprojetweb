<?php

  include_once "libs/maLibUtils.php";
  include_once "libs/maLibForms.php";
  include_once "libs/modele.php";
// Si la page est appelée directement par son adresse, on redirige en passant pas la page index
if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
  header("Location:./index.php?view=login");
  die("");
}

if (!$_SESSION["connecte"])
{
  header("Location:./index.php?view=login&alerte=Tu dois être connecté pour accèder à cette page !");
  die("");
}

?>

<?php
  //Envoie de la requête à l'API si l'id et le type de média du film/de la série est bien déclaré
  $favoris_movie = listerWatchlist($_SESSION["idUser"], "movie");
  foreach ($favoris_movie as $nombre => $favoris) {
    $id_film = $favoris["id_film"];
    $url = "https://api.themoviedb.org/3/movie/$id_film?api_key=83354d9b54af3ca7b64d6dc86ddac815&language=fr-FR";
    $favoris_movie[$nombre]["url"] = $url;
  }
  $favoris_tv = listerWatchlist($_SESSION["idUser"], "tv");
  foreach ($favoris_tv as $nombre => $favoris) {
    $id_film = $favoris["id_film"];
    $url = "https://api.themoviedb.org/3/tv/$id_film?api_key=83354d9b54af3ca7b64d6dc86ddac815&language=fr-FR";
    $favoris_tv[$nombre]["url"] = $url;
  }

  
?>

<div class="page-header">
	<h1>Vos Favoris</h1>
</div>

<div class="page-subtitle">
  <h2>Films</h2>
</div>

<div id="liste_favoris_movie" class="liste_media_basic">
  <?php
    foreach ($favoris_movie as $favoris) {
      //tprint($favoris);
      afficherFilmSerie($favoris, "movie", "watchlist");
    }
  ?>
</div>

<div class="page-subtitle">
  <h2>Séries</h2>
</div>

<div id="liste_favoris_tv" class="liste_media_basic">
  <?php
    foreach ($favoris_tv as $favoris) {
      //tprint($favoris);
      afficherFilmSerie($favoris, "tv", "watchlist");
    }
  ?>
</div>

<script type="text/javascript">
  function afficherMediaPopup(chevron, event){
    var idMedia = chevron.id.substring(8);
    refPopup = document.getElementById("chevron_popup_"+idMedia);
    if(refPopup.style.visibility == "visible"){
      refPopup.style.visibility = "hidden";
    }
    else{if(refPopup.style.visibility == "hidden" || refPopup.style.visibility == ""){
      refPopup.style.visibility = "visible";
    }}
  }
</script>