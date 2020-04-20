<?php
	$style =  blindage(isset($_POST["style"])? $_POST["style"] :"");
	$valider =  isset($_POST["valider"])? $_POST["valider"] :"";
	
	$erreur = "";
	
	if($style != "")
	{
		$_style = "";
		if($style == "lignes")
			$_style = 0;
		else
			$_style = 1;
		
		$sql = "UPDATE `utilisateur` SET `StyleFavoris`=". $_style ." WHERE `ID` = ". $user["ID"] .";"; 
		list($_, $erreur) =  SQLQuery($_DATABASE, $sql, $erreur);
		
		if($_)
			redirect("./?page=account");
	}
?>



<?php 
include("./template/_top.php");

if($erreur != "")
	echo "Erreur: " . $erreur;

include("./template/_bot.php"); 

?>