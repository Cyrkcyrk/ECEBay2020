<?php
	$erreur = ""; 
	
	$ID = isset($_GET["ID"])? $_GET["ID"] : "";
	
	if($logged)
	{
		if($ID != "")
		{
			$sql = "DELETE FROM `item` WHERE `ID` = " . $ID . " AND `OwnerID` = '" . $user["ID"] . "' AND `EtatVente` = 1;";
			list ($_, $erreur) = SQLquery($_DATABASE, $sql, $erreur);
			redirect('./?page=vente');
		}
		else
		{
			redirect('./?page=vente');
		}
	}
	else
	{
		redirect('./?page=accueil');
	}
?>

