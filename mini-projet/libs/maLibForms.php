<?php


/*
Ce fichier d�finit diverses fonctions permettant de faciliter la production de mises en formes complexes : 
tableaux, formulaires, ...
*/

function mkLigneEntete($tabAsso,$listeChamps=false)
{
	// Fonction appel�e dans mkTable, produit une ligne d'ent�te
	// contenant les noms des champs � afficher dans mkTable
	// Les champs � afficher sont d�finis � partir de la liste listeChamps 
	// si elle est fournie ou du tableau tabAsso
	
	// Si $listeChamps n'est pas FAUX
	if ($listeChamps) {
	  echo "<tr>";
	  // Ici, $champ vaut chaque valeur dans $listeChamp
	  // Syntaxe 1 : foreach($tab as $valeur)
	  foreach ($listeChamps as $champ) {
	    echo "<th>" . $champ . "</th>";
	  }
	  echo "</tr>\n";
	} else {
	  echo "<tr>";
	  // Ici, $cle vaut chaque cl� dans $tabAsso (dict)
	  // Syntaxe 1 : foreach($tab as $cl� => $valeur)
	  foreach ($tabAsso as $cle => $value) {
	    echo "<th>" . $cle . "</th>";
	  }
	  echo "</tr>\n";
	}
}

function mkLigne($tabAsso,$listeChamps=false)
{
	// Fonction appel�e dans mkTable, produit une ligne 
	// contenant les valeurs des champs � afficher dans mkTable
	// Les champs � afficher sont d�finis � partir de la liste listeChamps 
	// si elle est fournie ou du tableau tabAsso
	// Si $listeChamps n'est pas FAUX
	if ($listeChamps) {
	  echo "<tr>";
	  // Ici, $champ vaut chaque valeur dans $listeChamp
	  // Syntaxe 1 : foreach($tab as $valeur)
	  foreach ($listeChamps as $champ) {
	    echo "<td>" . $tabAsso[$champ] . "</td>";
	  }
	  echo "</tr>\n";
	} else {
	  echo "<tr>";
	  // Ici, $cle vaut chaque cl� dans $tabAsso (dict)
	  // Syntaxe 1 : foreach($tab as $cl� => $valeur)
	  foreach ($tabAsso as $cle => $value) {
	    echo "<td>" . $value . "</td>";
	  }
	  echo "</tr>\n";
	}
}

function mkTable($tabData,$listeChamps=false)
{
	// Produit un tableau affichant les donn�es pass�es en param�tre
	// Si listeChamps est vide, on affiche toutes les donn�es de $tabData
	// S'il est d�fini, on affiche uniquement les champs list�s dans ce tableau, 
	// dans l'ordre du tableau
	
	echo "<table>\n";
	mkLigneEntete($tabData[0], $listeChamps);
	foreach ($tabData as $user) {
  	mkLigne($user, $listeChamps);
	}
  echo "</table>\n";
}


function mkSelect($nomChampSelect, $tabData,$champValue, $champLabel,$selected=false,$champLabel2=false)
{
	// Produit un menu d�roulant portant l'attribut name = $nomChampSelect
	// TNE: Si cette variable se termine par '[]', il faudra affecter l'attribut multiple � la balise select

	// Produire les options d'un menu d�roulant � partir des donn�es pass�es en premier param�tre
	// $champValue est le nom des cases contenant la valeur � envoyer au serveur
	// $champLabel est le nom des cases contenant les labels � afficher dans les options
	// $selected contient l'identifiant de l'option � s�lectionner par d�faut
	// si $champLabel2 est d�fini, il indique le nom d'une autre case du tableau 
	// servant � produire les labels des options
	
	echo "<select name=\"$nomChampSelect\">\n";
	foreach ($tabData as $tabAsso) {
	  $sel = "";
	  if ($selected === $tabAsso[$champValue]) {
	    $sel = " selected=\"selected\"";
	  }
	  $label = $tabAsso[$champLabel];
	  if ($champLabel2) {
	    $label .= " - " . $tabAsso[$champLabel2];
	  }
	  echo ("<option value=\"$tabAsso[$champValue]\"$sel>" .
	        $label . "</option>\n");
	}
	echo "</select>\n";
}

function mkForm($action="",$method="get",$enctype="")
{
	// Produit une balise de formulaire NB : penser � la balise fermante !!
	echo "<form action=\"$action\" method=\"$method\" enctype=\"$enctype\">\n";
}
function endForm()
{
	// produit la balise fermante
	echo "</form>\n";
}

function mkInput($type,$name,$value="",$class="",$param=[])
{
	$parametre = "";
	foreach ($param as $nom_param => $value_param) {
		$parametre .= "$nom_param=\"$value_param\"";
	}
	// Produit un champ formulaire
	echo ("<input type=\"$type\" name=\"$name\" value=\"$value\" class=\"$class\"  $parametre/>\n");
}
function mkLabel($for,$label,$param=[])
{
	$parametre = "";
	foreach ($param as $nom_param => $value_param) {
		$parametre .= "$nom_param=\"$value_param\"";
	}
	// Produit le label d'un input
	echo ("<label for=\"$for\" $parametre>$label</label>\n");
}

function mkRadioCb($type,$name,$value,$checked=false,$label="",$class="",$id="",$param=[],$labelparam=[],$function="")
{
	// Produit un champ formulaire de type radio ou checkbox
	// Et s�lectionne cet �l�ment si le quatri�me argument est vrai
	if ($checked) {
	  $ch = " checked=\"checked\"";
	} else {
	  $ch = "";
	}

	$parametre = "";
	foreach ($param as $nom_param => $value_param) {
		$parametre .= "$nom_param=\"$value_param\"";
	}

	$html_label = "";
	if($label != "" && $id!=""){

		$label_parametre = "";
		foreach ($labelparam as $nom_param => $value_param) {
			$label_parametre .= "$nom_param=\"$value_param\"";
		}

		$html_label = "<label $label_parametre for='$id'>$label</label>";
	}

	echo ("<input type=\"$type\" name=\"$name\"" .
	      " value=\"$value\" id=\"$id\" class=\"$class\" $parametre $ch>\n".$html_label);
}
?>
