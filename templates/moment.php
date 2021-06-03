<?php

  include_once "libs/maLibUtils.php";
  include_once "libs/maLibForms.php";
  include_once "libs/modele.php";
// Si la page est appelée directement par son adresse, on redirige en passant pas la page index

?>


<div id="div_canvas">
  <canvas onmousemove="afficherPopup(event, false)" onmouseout="cacherPopup()" id="stockGraph" width="1000" height="20">
  </canvas><br/>
  <?php
    mkForm("index.php");
    mkInput("hidden","view","filmserie"); //On donne l'id du média
    mkInput("hidden","id",$id); //On donne l'id du média
    mkInput("hidden","media",$media_type); //On donne le type du média
    mkInput("hidden","idMoment","","",["id"=>"hidden_idMoment"]); //On donne le type du média
    mkInput("submit","offset","","bouton_submit",["id"=>"popup_moment","onmousemove"=>"afficherPopup(event, true)","onmouseout"=>"cacherPopup()","onclick"=>"afficherMoment(event)"]);
    endForm();
  ?>
  <button type="button" class="bouton_submit">Ajouter un moment</button>
</div>

<?php
if($idMoment = valider("idMoment")){
$moment = getInfoMoment($idMoment);
$moment["timecode"]["heure"] = round($moment["offset"]/60, 0);
$moment["timecode"]["minute"] = round($moment["offset"] - $moment["timecode"]["heure"]*60, 0);
echo
"<div id=\"moment\">
  <div id=\"moment_fond_vide\" onclick=\"retournerPageFilmSerie()\"></div>
  <div id=\"moment_fond\">
    <div id=\"moment_informations\">
      <h2>" . $tabInformation['titre'] . " - " . $moment["label"] . "</h2>
      <p>Timecode : " . $moment["timecode"]["heure"] . "h" . $moment["timecode"]["minute"] . "min</p>
      <p>Résumé : " . $moment["resume"] . "</p>
      <h3>Avis</h3>";
if (isset($_SESSION["connecte"])){
  //Création du formulaire de recherche
  mkForm("controleur.php","get","",["class" => "form_avis"]);
  mkInput("text","avis","","champ_texte");
  mkInput("hidden","idMedia",$tabInformation["id"]); //On donne l'id du média
  mkInput("hidden","mediaType",$tabInformation["typeMedia"]); //On donne le type de média
  mkInput("hidden","idMoment",$idMoment); //On donne l'id du moment
  mkInput("submit","action","Poster un avis sur ce moment","bouton_submit");
  endForm();
}
//Lister les avis déjà existants
$tabAvisMoment = listerAvisMoment($idMoment);
//tprint($tabAvis);
foreach ($tabAvisMoment as $avisMoment) {
  echo afficherAvis($avisMoment);
}

echo
    "</div>
  </div>
</div>";
}
?>

<script type="text/javascript">
  document.body.addEventListener("load", dessiner());
  function dessiner(){
    var canvas = document.getElementById("stockGraph");
    var ctx = canvas.getContext('2d');
    ctx.beginPath();
    ctx.fillStyle = "#EDF5FF";
    ctx.fillRect(0, 9, 1000, 2);
    ctx.fillRect(0, 0, 2, 20);
    ctx.fillRect(1000, 0, -2, 20);
    ctx.closePath();

    tabInformation = <?php echo json_encode($tabInformation) ?>;
    console.log(tabInformation);

    afficherMoments();
  }

  function afficherPopup(event, submit){
    //console.log(event.pageY);
    if(!submit){
      moment = checkPositionCurseur(event.layerX);
    }
    if(submit || moment != undefined){
      console.log(moment);
      document.getElementById("popup_moment").style.visibility = "visible";
      document.getElementById("popup_moment").style.top = document.getElementById("stockGraph").offsetTop + 20 + "px";
      if(moment !=undefined){
        offsetHour = Math.floor(moment.offset/60);
        offsetMinutes = moment.offset-offsetHour*60;
        if(offsetHour != 0){
          offsetHour += "h";
        }
        else{
          offsetHour = "";
        }
        if(offsetMinutes != 0){
          offsetMinutes += "min";
        }
        else{
          offsetMinutes = "";
        }
        document.getElementById("hidden_idMoment").value = moment.id;
        document.getElementById("popup_moment").value = moment.label + " (" + offsetHour + offsetMinutes + ")";
        document.getElementById("popup_moment").style.left = moment.layerX + 259.6 - document.getElementById("popup_moment").offsetWidth/2 + "px";
      }
    }
    else{
      cacherPopup(event);
    }
  }

  function cacherPopup(event){
    //console.log(event.pageY);
    document.getElementById("popup_moment").style.visibility = "hidden";
  }

  function afficherMoment(event){
    document.getElementById("moment").style.display = "flex";

  }

  function checkPositionCurseur(CooX){
    for(var i = 0; i<tabMoments.length ; i++){
      if(CooX >= tabMoments[i].layerX-8 && CooX <= tabMoments[i].layerX+8){
        moment = tabMoments[i];
        return tabMoments[i];
      }
    }
  }

  function afficherMoments(){
    tabMoments = <?php echo json_encode(recupererMoments($tabInformation["id"], $tabInformation["typeMedia"])) ?>;
    var canvas = document.getElementById("stockGraph");
    var ctx = canvas.getContext('2d');
    for(var i = 0; i<tabMoments.length ; i++){
      tabMoments[i].layerX = Math.round(tabMoments[i].offset * 1000 / tabInformation.duree);
      ctx.beginPath();
      ctx.fillStyle = "#DBE7F6";
      ctx.arc(tabMoments[i].layerX, 10, 8, 0, Math.PI * 2, true);
      ctx.fill();
    }
    console.log(tabMoments);
  }

  function retournerPageFilmSerie(){
    var argument = location.href.split('&idMoment')[0];
    document.location.href = argument;
  }
</script>