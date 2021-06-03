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

if(!$_SESSION['admin']){
  header("Location:./index.php?view=accueil");
  die("");
}

?>


<div class="page-header">
	<h1>Administration</h1>
</div>

<?php
  $tabSignalements = getAllSignalements();
  foreach ($tabSignalements as $signalement) {
    $avis = getAvisById($signalement["id_avis"], $signalement["tableavis"]);
    afficherAvisSignalement($avis, $signalement["tableavis"], $signalement["id_user"]);
  }
?>


