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
  <div>
    <img class="img-circle avatar_profil" src="ressources/avatars/<?php echo $_SESSION["idUser"]?>.jpg">
    <?php
      mkForm("controleur.php","POST","multipart/form-data");
    ?>
      <?php 
        mkLabel("bouton_avatar","Importer une Image",["class"=>"img-circle", "id"=>"div_bouton_avatar"]);
        mkInput("file","newavatar","","",["id"=>"bouton_avatar"]);
      ?>
    <?php
      mkInput("submit","action","Changer d avatar","bouton_submit");
      endForm();
    ?>
  </div>
  <?php  
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
      afficherFilmSerie($visionne, "movie", "profil");
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
      afficherFilmSerie($visionne, "tv", "profil");
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