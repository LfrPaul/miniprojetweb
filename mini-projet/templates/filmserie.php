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



if(!valider("media") || !valider("id")){
  header("Location:./index.php?view=rechercher");
  die("");
}

?>

<?php
  //Envoie de la requête à l'API si l'id et le type de média du film/de la série est bien déclaré
  if($media_type = valider("media"))
  if($id = valider("id")){// on récupère l'id

    $url = "https://api.themoviedb.org/3/$media_type/$id?api_key=83354d9b54af3ca7b64d6dc86ddac815&language=fr-FR"; //Url de requête vers l'API

    //json_decode transforme le JSON obtenu par un objet PHP, file_get_contents récupère l'objet JSON contenu à l'url
    $resultatInformations = json_decode(file_get_contents($url), true); //On ne récupère que le tableau de résultats

    $tabInformation = recupererInfo($resultatInformations, $media_type, "original");
    //tprint($resultatInformations);
  }
?>

<div class="page-header">
	<h1><?php echo $tabInformation["titre"]?></h1>
</div>

<div id="corps">
  <div id="media_info"> 
    <img src="<?php echo $tabInformation["lienAffiche"]?>" id="media_affiche">
    <div id="media_info_centre">
      <div id="media_info_droite_haut">
        <h4 id="media_date">Date de sortie : <?php echo $tabInformation['date']['jour']."/".$tabInformation['date']['mois']."/".$tabInformation['date']['annee'];?></h4>
        <h4>Synopsis</h4>
        <p id="media_synopsis"><?php echo $tabInformation['synopsis'];?></p>
      </div>
      <div id="media_info_droite_bas">
        <?php
        if (isset($_SESSION["connecte"])){
          mkForm("controleur.php");
          mkInput("number","note","","champ_texte",["min"=>0,"max"=>5]);
          mkInput("hidden","idMedia",$id); //On donne l'id du média
          mkInput("hidden","mediaType",$media_type); //On donne le type du média
          mkInput("submit","action","Noter","bouton_submit");
          endForm();
        }
        ?>
      </div>
    </div>
    <div id="media_info_droite">
      <p><?php 
          if(listerNote($id, $media_type) != 0){
            echo round(getNote($id, $media_type),2)."<i class='fas fa-star'></i>/5";
          }
          else{
            echo "Non-noté";
          } 
        ?></p>
    </div>
  </div>
  <div id="boutons">
    <?php
    if (isset($_SESSION["connecte"])){
      mkForm("controleur.php");
      mkInput("hidden","idMedia",$id); //On donne l'id du média
      mkInput("hidden","mediaType",$media_type); //On donne le type du média
      mkInput("hidden","view","filmserie"); //On donne le type du média
      if(!checkWatchlist($id, $_SESSION["idUser"], $media_type) && !checkVisionne($id, $_SESSION["idUser"], $media_type)){mkInput("submit","action","Ajouter à la Watchlist","bouton_submit");}
      if(!checkVisionne($id, $_SESSION["idUser"], $media_type)){mkInput("submit","action","Marquer comme Visionné","bouton_submit");}
      if(!checkFavoris($id, $_SESSION["idUser"], $media_type)){mkInput("submit","action","Ajouter aux Favoris","bouton_submit");}
      else{mkInput("submit","action","Retirer des Favoris","bouton_submit");}
      endForm();
    }
    ?>
  </div>
</div>
<?php
  if (isset($_SESSION["connecte"])){
  //Création du formulaire de recherche
  mkForm("controleur.php");
  mkInput("text","avis","","champ_texte");
  mkInput("hidden","idMedia",$tabInformation["id"]); //On donne l'id du média
  mkInput("hidden","mediaType",$tabInformation["typeMedia"]); //On donne le type du média
  mkInput("submit","action","Poster un avis","bouton_submit");
  endForm();}
?>

<?php
  //Lister les avis déjà existants
  $tabAvis = listerAvis($id, $media_type);
  //tprint($tabAvis);
  foreach ($tabAvis as $avis) {
    echo afficherAvis($avis);
  }
?>

