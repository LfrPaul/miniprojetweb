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
