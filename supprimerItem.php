<?php
	$erreur = ""; 
	
	$ID = isset($_GET["ID"])? $_GET["ID"] : "";
	$PrecedentPage = isset($_GET["pp"])? $_GET["pp"] : "";
	if($logged)
	{
		if($ID != "")
		{
			if($user["TypeCompte"] == 3)
				$sql = "UPDATE `item` SET `EtatVente`=-1 WHERE `ID` = " . $ID . ";";
			else
				$sql = "UPDATE `item` SET `EtatVente`=-1 WHERE WHERE `ID` = " . $ID . " AND `OwnerID` = '" . $user["ID"] . "' AND `EtatVente` = 1;";
			list ($_, $erreur) = SQLquery($_DATABASE, $sql, $erreur);
			
			
			if($PrecedentPage != "") redirect('./?page=' . $PrecedentPage);
			else redirect('./?page=vente');
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

