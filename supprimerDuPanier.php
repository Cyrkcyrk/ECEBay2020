<?php
	$erreur = ""; 
	
	$ID = blindage(isset($_GET["ID"])? $_GET["ID"] : "");
	
	if($logged)
	{
		if($ID != "")
		{
			$sql = "DELETE FROM `panier` WHERE `ID` = " . $ID . " AND `OwnerID` = '" . $user["ID"] . "'";
			list ($_, $erreur) = SQLquery($_DATABASE, $sql, $erreur);
			if($_)
				redirect('./?page=panier');
		}
		else
		{
			redirect('./?page=panier');
		}
	}
	else
	{
		redirect('./?page=accueil');
	}
?>

<?php 
	include("./template/_top.php");

	if($erreur != "") 
		echo "Erreur: " . $erreur;
	
	include("./template/_bot.php");
?>

