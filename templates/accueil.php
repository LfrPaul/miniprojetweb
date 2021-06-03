<?php

//C'est la propriété php_self qui nous l'indique : 
// Quand on vient de index : 
// [PHP_SELF] => /chatISIG/index.php 
// Quand on vient directement par le répertoire templates
// [PHP_SELF] => /chatISIG/templates/accueil.php

// Si la page est appelée directement par son adresse, on redirige en passant pas la page index
// Pas de soucis de bufferisation, puisque c'est dans le cas où on appelle directement la page sans son contexte
if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
	header("Location:../index.php?view=accueil");
	die("");
}

?>


    <div class="page-header">
      <h1>Holliiwood</h1>
    </div>

    <p class="lead">Bienvenue sur Holliiwood<?php if($_SESSION["connecte"]){echo ", " . $_SESSION["pseudo"];}?></p>

    <p>Toutes les fonctionnalités dont tu as besoin se situent dans la barre de navigation en haut de la page</p>
    <p>Ici, tous les films et toutes les séries sont répertoriés. Tu peux indiquer si tu as visionné un d'entre eux.</p>
    <p>Tu peux aussi créer ta propre watchlist, ainsi que ta liste de films et séries favoris.</p></br>

    <p>Si tu as bien aimé un film ou une série, tu lui attribuer une note (de 0 à 5 étoiles), ou lui donner un avis global.</p>
    <p>Tu peux aussi indiquer un moment qui apparaitra sur la timeline en bas des informations, et ainsi permettre aux autres utilisateurs de donner leurs avis dessus.</p>
    <br>

    <p class="lead"></p>