<?php
	$erreur = "";
	$confirmation = "";
	$offerID = blindage(isset($_POST["offerID"])? $_POST["offerID"] : "");
	$valider = blindage(isset($_POST["valider"])? $_POST["valider"] : "");
	
	$offre = false;
	
	if($offerID == "")
		$erreur .= "Probleme avec l'ID de l'offre. <br>";
	if($valider == "")
		$erreur .= "Ni accepter ni refuser de selectionné. <br>";
	
	if($erreur == "")
	{
		if($valider == "accepter")
		{
			$sql = "
			SELECT
				o.`ID` 						AS 'OffreID',
				o.`ItemID` 					AS 'ItemID',
				o.`IDOffreMessageAccepte` 	AS 'IDOffreMessageAccepte',
				i.`EtatVente` 				AS 'ItemEtatVente',
				(SELECT `offremessage`.`ID` 	FROM `offremessage` WHERE `offremessage`.`OffreID` = o.`ID` ORDER BY `Date` DESC LIMIT 1) AS 'LastID',
				(SELECT `offremessage`.`Prix` 		FROM `offremessage` WHERE `offremessage`.`OffreID` = o.`ID` ORDER BY `Date` DESC LIMIT 1) AS 'LastPrix',
				(SELECT `offremessage`.`SenderID` 		FROM `offremessage` WHERE `offremessage`.`OffreID` = o.`ID` ORDER BY `Date` DESC LIMIT 1) AS 'LastSenderID',
				Owner.*,
				Buyer.*
			FROM `offres` AS o
			LEFT JOIN `item` AS i
				ON o.`ItemID` = i.`ID`
			LEFT JOIN (SELECT `ID` AS 'OwnerID' FROM `utilisateur`) AS Owner
				ON Owner.`OwnerID` = i.`OwnerID`
			LEFT JOIN (SELECT `ID` AS 'BuyerID' FROM `utilisateur`) AS Buyer
				ON Buyer.`BuyerID` = o.`BuyerID`
			WHERE (Owner.`OwnerID` = ". $user['ID'] ." OR Buyer.`BuyerID` = ". $user['ID'] .") 
			AND o.`ID` = ". $offerID .";";
			
			
			$mysqli = new mysqli($_DATABASE["host"],$_DATABASE["user"],$_DATABASE["password"],$_DATABASE["BDD"]);
			mysqli_set_charset($mysqli, "utf8");
			
			if ($mysqli -> connect_errno) {
				$erreur .= "Failed to connect to MySQL: " . $mysqli -> connect_error;
			}
			if ($result = $mysqli -> query($sql)) {
				if (mysqli_num_rows($result) > 0) {
					$offre = mysqli_fetch_assoc($result);
				}
				else
				{
					$offre = false;
				}
			}
			$result -> free_result();
			$mysqli -> close();
			
			
			if($offre)
			{
				if($offre["LastSenderID"] == $user["ID"] )
				{
					$erreur .= "Vous ne pouvez pas valider votre propre offre.";
				}
				else
				{
					$sql = "UPDATE `offres` SET `IDOffreMessageAccepte` = ". $offre["LastID"] ." WHERE `ID` = ". $offre["OffreID"] .";";
					list($_, $erreur) = SQLQuery($_DATABASE, $sql, $erreur);
					if($_)
					{
						$sql = "UPDATE `offres` SET `IDOffreMessageAccepte` = -2 WHERE `ItemID` = ". $offre["ItemID"] ." AND `ID` != ". $offre["OffreID"] .";";
						list($_, $erreur) = SQLQuery($_DATABASE, $sql, $erreur);
						if($_)
						{
							$sql = "UPDATE `item` SET `EtatVente`= 0  WHERE `ID` = ". $offre["ItemID"] .";";
							list($_, $erreur) = SQLQuery($_DATABASE, $sql, $erreur);
							if($_)
							{
								$confirmation .= "<br><b>OFFRE VALIDEE</b><hr>";
								redirect("./?page=offres&offerID=" . $offre["OffreID"] );
							}
							else 
							{
								$erreur .= "Erreur pendant l'update de l'item <br>";
							}
						}
						else 
						{
							$erreur .= "Probleme pendant l'update des offres des autres utilisateurs <br>";
						}
					}
					else 
					{
						$erreur .= "Probleme pendant l'update de mon offre personnelle. <br>";
					}
				}
			}
		}
		
		else if($valider == "refuser")
		{
			
			$sql = "
			SELECT
				o.`ID` 						AS 'OffreID',
				o.`ItemID` 					AS 'ItemID',
				o.`IDOffreMessageAccepte` 	AS 'IDOffreMessageAccepte',
				i.`EtatVente` 				AS 'ItemEtatVente',
				(SELECT `offremessage`.`ID` 	FROM `offremessage` WHERE `offremessage`.`OffreID` = o.`ID` ORDER BY `Date` DESC LIMIT 1) AS 'LastID',
				(SELECT `offremessage`.`Prix` 		FROM `offremessage` WHERE `offremessage`.`OffreID` = o.`ID` ORDER BY `Date` DESC LIMIT 1) AS 'LastPrix',
				(SELECT `offremessage`.`SenderID` 		FROM `offremessage` WHERE `offremessage`.`OffreID` = o.`ID` ORDER BY `Date` DESC LIMIT 1) AS 'LastSenderID',
				Owner.*,
				Buyer.*
			FROM `offres` AS o
			LEFT JOIN `item` AS i
				ON o.`ItemID` = i.`ID`
			LEFT JOIN (SELECT `ID` AS 'OwnerID' FROM `utilisateur`) AS Owner
				ON Owner.`OwnerID` = i.`OwnerID`
			LEFT JOIN (SELECT `ID` AS 'BuyerID' FROM `utilisateur`) AS Buyer
				ON Buyer.`BuyerID` = o.`BuyerID`
			WHERE (Owner.`OwnerID` = ". $user['ID'] ." OR Buyer.`BuyerID` = ". $user['ID'] .") 
			AND o.`ID` = ". $offerID .";";
			
			
			$mysqli = new mysqli($_DATABASE["host"],$_DATABASE["user"],$_DATABASE["password"],$_DATABASE["BDD"]);
			mysqli_set_charset($mysqli, "utf8");
			
			if ($mysqli -> connect_errno) {
				$erreur .= "Failed to connect to MySQL: " . $mysqli -> connect_error;
			}
			if ($result = $mysqli -> query($sql)) {
				if (mysqli_num_rows($result) > 0) {
					$offre = mysqli_fetch_assoc($result);
				}
				else
				{
					$offre = false;
				}
			}
			$result -> free_result();
			$mysqli -> close();
			
			
			if($offre)
			{
				if($offre["LastSenderID"] == $user["ID"] )
				{
					$erreur .= "Vous ne pouvez pas refuser votre propre offre.";
				}
				else
				{
					$sql = "UPDATE `offres` SET `IDOffreMessageAccepte` = -2 WHERE `ID` = ". $offre["OffreID"] .";";
					list($_, $erreur) = SQLQuery($_DATABASE, $sql, $erreur);
					if($_)
					{
						$confirmation .= "<b>OFFRE REFUSEE</b><hr>;";
						redirect("./?page=offres&offerID=" . $offre["OffreID"] );
					}
					else 
					{
						$erreur .= "Probleme pendant l'update de mon offre personnelle. <br>";
					}
				}
			}
		}
	}
?>

<?php 
	include("./template/_top.php");
	
	if($confirmation != "")
		echo "Erreur: " . $confirmation;
	
	if($erreur != "")
		echo "Erreur: " . $erreur;
	
	include("./template/_bot.php");?>