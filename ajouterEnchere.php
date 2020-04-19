<?php

$erreur = ""; 
	$Enchere =  blindage(isset($_POST["Enchere"])? $_POST["Enchere"] :"");
	$ItemID =  blindage(isset($_POST["ID"])? $_POST["ID"] :"");
	$valider =  isset($_POST["valider"])? $_POST["valider"] :"";
	
	if($logged)
	{
		if($valider != "")
		{
			if($Enchere == "") {
				$erreur .= "Enchere incomplete <br>";
			} 
			if($ItemID == "") {
				$erreur .= "ID incomplet <br>";
			} 
			if($erreur == "")
			{
				
				// $sql = "SELECT i.*, CASE WHEN EXISTS (SELECT `Prix` FROM `encheres` AS e WHERE e.`ItemID` = i.`ID`ORDER BY `Prix` DESC LIMIT 1) THEN e.`Prix` ELSE i.`PrixDepart` END AS 'PrixEnchereMax' FROM `item` AS i LEFT JOIN `encheres` AS e ON e.`ItemID` = i.`ID` WHERE i.`ID` = ". $ItemID ." AND i.`EtatVente` = 1 AND i.`ModeVente` = 1 ";
				
				$sql = "
				SELECT * FROM ( 
					SELECT 
						i.*, 
						CASE WHEN EXISTS (SELECT `Prix` FROM `encheres` AS e WHERE e.`ItemID` = i.`ID` ORDER BY `Prix` DESC LIMIT 1,1) 
							THEN (SELECT `Prix` FROM `encheres` AS e WHERE e.`ItemID` = i.`ID` ORDER BY `Prix` DESC LIMIT 1,1) + 1 
						WHEN EXISTS (SELECT `Prix` FROM `encheres` AS e WHERE e.`ItemID` = i.`ID` ORDER BY `Prix` DESC LIMIT 1) 
							THEN i.`PrixDepart` + 1 
						ELSE i.`PrixDepart` 
						END AS 'PrixEnchereMax', 
						o.`Nom` AS 'OwnerNom', 
						o.`Prenom` AS 'OwnerPrenom' 
					FROM `item` AS i 
					LEFT JOIN `encheres` AS e 
						ON e.`ItemID` = i.`ID` 
					JOIN `utilisateur` 
						AS o ON o.`ID` = i.`OwnerID` 
					WHERE i.`ID` = ". $ItemID ." 
					AND i.`EtatVente` = 1  
					AND i.`ModeVente` = 1 
				) AS R 
				ORDER BY R.`PrixEnchereMax` 
				DESC LIMIT 1;";
				
				$mysqli = new mysqli($_DATABASE["host"],$_DATABASE["user"],$_DATABASE["password"],$_DATABASE["BDD"]);
				mysqli_set_charset($mysqli, "utf8");
				
				if ($mysqli -> connect_errno) {
					$erreur .= "Failed to connect to MySQL: " . $mysqli -> connect_error;
				}
				if ($result = $mysqli -> query($sql)) {
					if (mysqli_num_rows($result) > 0) {
						
						$item = mysqli_fetch_assoc($result);
						$result -> free_result();
						$mysqli -> close();
						
						
						if($row["OwnerID"] == $user["ID"])
						{
							$erreur .= "Vous ne pouvez pas acheter un item que vous vendez.";
						} else {
							if($item["PrixEnchereMax"] > $Enchere) {
								$erreur .= "Vous ne pouvez pas enchérir à moins de ". $item["PrixEnchereMax"] ."€.";
							} else {
								$sql = "SELECT * FROM `encheres` WHERE `ItemID` = ". $ItemID ." AND `BuyerID` = ". $user["ID"] .";";
								
								list ($_, $erreur) = SQLcheck($_DATABASE, $sql, $erreur);
								if($_)
								{
									$sql = "UPDATE `encheres` SET `Prix`= ". $Enchere ." WHERE `ItemID` = ". $ItemID ." AND `BuyerID` = ". $user["ID"] .";";
									list ($_, $erreur) = SQLquery($_DATABASE, $sql, $erreur);
									if($_)
									{
										redirect("./?page=panier");
									}
								}
								else
								{
									$sql = "INSERT INTO `encheres`(`ItemID`, `BuyerID`, `Prix`) VALUES (". $ItemID . ", ". $user["ID"] . ", ". $Enchere .")";
									list ($_, $erreur) = SQLquery($_DATABASE, $sql, $erreur);
									if($_)
									{
										redirect("./?page=panier");
									}
								}

							}
						}
					}
					else
					{
						$erreur .= "Cet item n'existe pas, n'est plus a vendre, ou n'est pas disponible à l'enchère.";
						$result -> free_result();
						$mysqli -> close();
					}
				}
				else
				{
					$erreur .= "Une erreur est survenue";
				}
			}
			
		}
	}
	else
	{
		redirect('./?page=login');
	}
	
	
	
	include("./template/_top.php");
	echo '<div id="erreur">';
	if($erreur != "")
	{
		echo $erreur;
	}
	echo '</div>';

	include("./template/_bot.php"); 
	
?>




