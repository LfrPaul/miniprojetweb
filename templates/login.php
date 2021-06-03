<?php

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
<div id="login_container">
  <div id="login_gauche">
    <div class="page-header">
      <h1>Se connecter</h1>
    </div>

    <?php if ($alerte = valider("alertelogin")){
      echo "<p style='color:red;'>$alerte</p>";
    }
    ?>

    <p class="lead">

     <form role="form" action="controleur.php" >
      <div class="form-group">
        <label for="email">Login</label>
        <input type="text" class="form-control" id="email" name="login" value="<?php echo $login;?>" >
      </div>
      <div class="form-group">
        <label for="pwd">Passe</label>
        <input type="password" class="form-control" id="pwd" name="passe" value="<?php echo $passe;?>">
      </div>
      <div class="checkbox">
        <label><input type="checkbox" name="remember" <?php echo $checked;?> >Se souvenir de moi</label>
      </div>
      <button type="submit" name="action" value="Connexion" class="btn btn-default">Connexion</button>
    </form>

    </p>
  </div>



  <div id="login_droite">
    <div class="page-header">
      <h1>Créer son compte</h1>
    </div>

    <?php if ($alerte = valider("alerteregister")){
      echo "<p style='color:red;'>$alerte</p>";
    }
    ?>

    <p class="lead">

     <form role="form" action="controleur.php" method="POST">
      <div class="form-group">
        <label for="email">Login</label>
        <input type="text" class="form-control" id="email" name="login" value="<?php echo $login;?>" >
      </div>
      <div class="form-group">
        <label for="pwd">Passe</label>
        <input type="password" class="form-control" id="pwd" name="passe" value="<?php echo $passe;?>">
      </div>
      <div class="checkbox">
        <label><input type="checkbox" name="remember" <?php echo $checked;?> >Se souvenir de moi</label>
      </div>
      <button type="submit" name="action" value="Créer un Compte" class="btn btn-default">Créer un Compte</button>
    </form>

    </p>
  </div>
</div>



