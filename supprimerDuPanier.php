<?php
	$erreur = ""; 
	
	$ID = isset($_GET["ID"])? $_GET["ID"] : "";
	
	if($logged)
	{
		if($ID != "")
		{
			$sql = "DELETE FROM `panier` WHERE `ID` = " . $ID . " AND `OwnerID` = '" . $user["ID"] . "'";
			list ($_, $erreur) = SQLquery($_DATABASE, $sql, $erreur);
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
