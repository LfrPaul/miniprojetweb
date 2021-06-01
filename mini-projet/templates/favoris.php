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

//Si l'utilisateur arrive à accèder à cette page sans être connecté, on le renvoie automatique vers la page de login
if (!$_SESSION["connecte"])
{
  header("Location:./index.php?view=login&alerte=Tu dois être connecté pour accèder à cette page !");
  die("");
}

?>

<?php
  //Pour tout les favoris de l'utilisateur, on envoie une requete à l'API pour récupérer les infos du média
  //On récupérera le tableau des ID des médias favoris, et on leur associera une url qui sera l'url d'envoie de la requete à l'API
  //La fonction listerFavoris permet de récupérer tous les films/séries favoris (selon le deuxieme paramètre) d'un utilisateur

  // On récupère les films favoris
  $favoris_movie = listerFavoris($_SESSION["idUser"], "movie");
  foreach ($favoris_movie as $nombre => $favoris) {//On parcourt le tableau renvoyé par listerFavoris
    $id_film = $favoris["id_film"];
    $url = "https://api.themoviedb.org/3/movie/$id_film?api_key=83354d9b54af3ca7b64d6dc86ddac815&language=fr-FR";//Url qui enverra la requête à l'API
    $favoris_movie[$nombre]["url"] = $url;
  }

  // On récupère les séries favories
  $favoris_tv = listerFavoris($_SESSION["idUser"], "tv");
  foreach ($favoris_tv as $nombre => $favoris) {//On parcourt le tableau renvoyé par listerFavoris
    $id_film = $favoris["id_film"];
    $url = "https://api.themoviedb.org/3/tv/$id_film?api_key=83354d9b54af3ca7b64d6dc86ddac815&language=fr-FR";//Url qui enverra la requête à l'API
    $favoris_tv[$nombre]["url"] = $url;
  }

  
?>

<div class="page-header">
	<h1>Mes Favoris</h1>
</div>

<div class="page-subtitle">
  <h2>Films</h2>
</div>

<div class="liste_media_basic">
  <?php
    //On affiche tous les films favoris
    foreach ($favoris_movie as $favoris) {
      afficherFilmSerie($favoris, "movie","favoris");
    }
  ?>
</div>

<div class="page-subtitle">
  <h2>Séries</h2>
</div>

<div class="liste_media_basic">
  <?php
    //On affiche toutes les séries favorites
    foreach ($favoris_tv as $favoris) {
      afficherFilmSerie($favoris, "tv","favoris");
    }
  ?>
</div>


<script type="text/javascript">
  //Fonction qui affichera/cachera la popup en haut à gauche de l'affichage d'un média lors du clique sur le chevron
  function afficherMediaPopup(id_media, event, icone){
    refPopup = document.getElementById("chevron_popup_"+id_media);//On récupère la référence vers la popup
    if(refPopup.style.visibility == "visible"){//Si la popup est visible, alors on veut la cacher
      //On la rend cachée
      refPopup.style.visibility = "hidden";

      //On chance le chevron haut en chevron bas
      icone.classList.remove("fa-chevron-up");
      icone.classList.add("fa-chevron-down");
    }
    else{if(refPopup.style.visibility == "hidden" || refPopup.style.visibility == ""){//Si la popup est caché, alors on veut l'afficher
      //On la rend visible
      refPopup.style.visibility = "visible";

      //On chance le chevron bas en chevron haut
      icone.classList.remove("fa-chevron-down");
      icone.classList.add("fa-chevron-up");
    }}
  }
</script>