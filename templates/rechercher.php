<?php

  include_once "libs/maLibUtils.php";
  include_once "libs/maLibForms.php";
// Si la page est appelée directement par son adresse, on redirige en passant pas la page index
if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
	header("Location:../index.php?view=login");
	die("");
}

// Chargement eventuel des données en cookies
$login = valider("login", "COOKIE");
$passe = valider("passe", "COOKIE"); 
if ($checked = valider("remember", "COOKIE")) $checked = "checked"; 

?>

<div class="page-header">
	<h1>Recherche</h1>
</div>

<div id="form-recherche">  
  <?php
    //Création du formulaire de recherche
    mkForm("index.php");
    mkInput("text","query_recherche",valider("query_recherche"),"champ_texte");
    mkInput("hidden","page",1); //Servira à charger plus d'élements si l'utilisateur le souhaite
    mkInput("submit","view","Rechercher","bouton_submit");
    endForm();
  ?>
  <!--<input type="text" id="champ_recherche">
  <button onclick="genererListe()">Rechercher</button>-->
</div>


<?php
  //Envoie de la requête à l'API si l'utilisateur a recherché 
  if($page = valider("page"))
  if($query_recherche = str_replace("+", "%20", valider("query_recherche"))){// on récupère la recherche en remplaçant le + (espace avant) par le code %20 (espace encodé)

    $url = "https://api.themoviedb.org/3/search/multi?api_key=83354d9b54af3ca7b64d6dc86ddac815&language=fr-FR&query=$query_recherche&include_adult=false&page=$page"; //Url de requête vers l'API
    //echo str_replace(" ", "%20", $url);

    //json_decode transforme le JSON obtenu par un objet PHP, file_get_contents récupère l'objet JSON contenu à l'url
    $listeResultats = json_decode(file_get_contents(str_replace(" ", "%20", $url)), true)["results"]; //On ne récupère que le tableau de résultats
    //tprint($listeResultats);
  }
?>

<!--Liste qui contiendra les résultats de la recherche-->
<div id="listeFilmSerie">
  <?php
    if(valider("query_recherche"))//On n'affiche la liste que si l'utilisateur a recherché quelquechose
    foreach ($listeResultats as $resultat) { // on parcourt le tableau de résultat obtenu 
      //tprint($resultat);
      mkFilmserie($resultat);
    }
  ?>
</div>

<!--
<script>
  var results = [];
  function genererListe(){
    requestJSON(document.getElementById("champ_recherche").value);
  }

  //Fonction envoyant la requête JSON à l'API, en prenant en paramètre la chaine de caractère de la recherche
  function requestJSON(query){
    var request = new XMLHttpRequest();
    url = "https://api.themoviedb.org/3/search/multi?api_key=83354d9b54af3ca7b64d6dc86ddac815&language=fr-FR&query="+query+"&include_adult=false"
    request.open('GET', url, true);
    request.responseType = 'json';

    request.onload = function() {
      results = request.response.results;
      console.log(results);
      afficherListe(results);
    };
    
    request.send();

  }

  //Fonction affichant les résultats sur la page
  function afficherListe(resultats){
    //innerHTML à afficher
    var innerHTML = "";

    //On parcourt le tableau des résultats renvoyé par l'API
    for(var i=0; i<resultats.length ;i++){
      //On n'affiche que les résultats ayant une affiche et un synopsis
      if(resultats[i].poster_path != undefined && resultats[i].overview != ""){

        //L'api peut renvoyer les films (movie) et les séries (tv), dans ce cas les noms de variables sont différents
        //Si le résultat est une série
        if(resultats[i].media_type == "tv"){
          //On récupère le nom de la série
          titre = resultats[i].name;

          //On récupère la première date de diffusion (substring(0,4) pour ne garder que l'année)
          annee = resultats[i].first_air_date.substring(0,4);
          console.log("année"+annee);
        }
        //Si le résultat est un film
        if(resultats[i].media_type == "movie"){
          //On récupère le titre du film
          titre = resultats[i].title;

          //On récupère la date de sortie (substring(0,4) pour ne garder que l'année)
          annee = resultats[i].release_date.substring(0,4);
        }

        //On ajoute dans innerHTML le film/série à la position i dans le tableau, on affichera son affiche, son titre, son annee de sortie/lancement
        innerHTML += "<a href='index.php?view=filmserie&id=" + resultats[i].id + "&media=" + resultats[i].media_type + "' class='resultat_recherche' id='multi" + i + "'><img style='height:300px' src='https://image.tmdb.org/t/p/original/" + resultats[i].poster_path + "'><div class='info_recherche'><h2>" + titre + " (" + annee + ")</h2><p>" + resultats[i].overview + "</p></div></a>";
      }
    }

    //On affiche la liste dans la division qui a pour id "listerFilmSerie"
    document.getElementById("listeFilmSerie").innerHTML = innerHTML;


  }
</script>
-->
