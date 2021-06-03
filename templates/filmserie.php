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


//Si l'id ou si le media n'est pas renseigné
if(!valider("media") || !valider("id")){
  //On renvoit vers la page de recherche
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
    //tprint($tabInformation);
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
        <p id="media_synopsis"><?php echo $tabInformation['synopsis'];?></p><br/>
        <?php
          if($media_type == "tv"){
            echo "<h5 id='media_date'>Nombre de saisons : ".$tabInformation['nbrSaison']."</h5>";
          }
        ?>
      </div>
      <div id="media_info_droite_bas">
        <?php
        //L'utilisateur peut donner une note seulement si il est connecté, et que le média est déjà sorti
        if (isset($_SESSION["connecte"]) && ($tabInformation["status"] == "Released" || $tabInformation["status"] == "Ended") && checkVisionne($id, $_SESSION["idUser"], $media_type)){
          mkForm("controleur.php");
          //mkInput("number","note","","champ_texte",["min"=>0,"max"=>5]); // L'utilisateur ne peut qu'entrer un nombre entre 0 et 
          mkRadioCb("radio","note",0,true,"","radio_note","radio_note0");
          for ($i=1; $i < 6; $i++) { 
            mkRadioCb("radio","note",$i,false,"<i class='far fa-star'></i>","radio_note","radio_note$i",["onchange"=>"mettreNotreA0()"],["onclick"=>"changerIconeNote(this,1)"]);
          }
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
          if(listerNote($id, $media_type) != 0){//Si il y a au moins un note attribuée à ce média on affiche la note
            //round(...,2) arrondi le nombre à la centaine
            echo round(getNote($id, $media_type),2)."<i class='fas fa-star'></i>/5"; 
          }
          else{//Sinon on indique que le média n'est pas noté
            echo "Non-noté";
          } 
        ?></p>
    </div>
  </div>
  <div id="boutons">
    <?php
    //L'utilisateur peut ajouter un média à sa watchlist/ses favoris seulement si il est connecté
    if (isset($_SESSION["connecte"])){
      mkForm("controleur.php");

      mkInput("hidden","idMedia",$id); //On donne l'id du média
      mkInput("hidden","mediaType",$media_type); //On donne le type du média
      mkInput("hidden","view","filmserie"); //On donne la view sur laquelle on veut revenir

      //Si le média n'est pas déjà dans la watchlist de l'utilisateur et si l'utilisateur n'a pas encore visionné ce média, il peut l'ajouter à sa watchlist
      if(!checkWatchlist($id, $_SESSION["idUser"], $media_type) && !checkVisionne($id, $_SESSION["idUser"], $media_type)){
        mkInput("submit","action","Ajouter à la Watchlist","bouton_submit");
      }

      //Si l'utilisateur n'a pas encore visionné ce média, et qu'il est sorti, il peut l'indiquer comme Visionné
      if(!checkVisionne($id, $_SESSION["idUser"], $media_type) && ($tabInformation["status"] == "Released" || $tabInformation["status"] == "Ended")){
        mkInput("submit","action","Marquer comme Visionné","bouton_submit");
      }

      //Si le média est dans les visionnés, l'utilisateur peut choisir de le retirer
      if(checkVisionne($id, $_SESSION["idUser"], $media_type)){
        mkInput("submit","action","Marquer comme non-vu","bouton_submit");
      }

      //Si le média n'est pas déjà dans les favoris de l'utilisateur, et que l'utilisateur l'a visionné, il peut l'ajouter à ses favoris
      if(!checkFavoris($id, $_SESSION["idUser"], $media_type) && checkVisionne($id, $_SESSION["idUser"], $media_type)){
        mkInput("submit","action","Ajouter aux Favoris","bouton_submit");
      }

      //Si le média est dans les favoris, l'utilisateur peut choisir de le retirer
      if(checkFavoris($id, $_SESSION["idUser"], $media_type)){
        mkInput("submit","action","Retirer des Favoris","bouton_submit");
      }
      endForm();
    }
    ?>
  </div>
</div>
<?php
  include("templates/moment.php");
  if (isset($_SESSION["connecte"])){
  //Création du formulaire de recherche
  mkForm("controleur.php","get","",["class" => "form_avis"]);
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


<script type="text/javascript">
  var misea0 = false;
  function changerIconeNote(radio,debut=0){
    if(debut){
      console.log(radio.nextElementSibling.nextElementSibling);
      if(radio.innerHTML == "<i class=\"fas fa-star\" aria-hidden=\"true\"></i>" && (radio.nextElementSibling.nextElementSibling.innerHTML != "<i class=\"fas fa-star\" aria-hidden=\"true\"></i>")){
        allLabel = document.getElementsByTagName('LABEL');
        for(label of allLabel){
          label.innerHTML = "<i class='far fa-star'></i>";
        }       
        document.getElementById("radio_note0").checked = true;
        misea0 = true; 
        console.log("misea0"+misea0);
        return;
      }
      else{
        allLabel = document.getElementsByTagName('LABEL');
        misea0 = false;
        console.log("misea0"+misea0);
        for(label of allLabel){
          label.innerHTML = "<i class='far fa-star'></i>";
        }
      }

    }
    if(radio.tagName == undefined){return;}

    if(radio.tagName == "LABEL"){
      console.log(radio.tagName);
      radio.innerHTML = "<i class='fas fa-star'></i>";
      changerIconeNote(radio.previousElementSibling);
    }
    else{
      console.log("RADIO");
      changerIconeNote(radio.previousElementSibling);
    }
  }

  function mettreNotreA0(){
    console.log("MISEA0 DANS FONCTION "+misea0);
    if(misea0){
        document.getElementById("radio_note0").checked = true;
        console.log("CHECK : " + document.getElementById("radio_note0").checked);  
    }
  }
</script>

