<?php
	$erreur = ""; 
	
	$selectedUserID = blindage(isset($_GET["user"])? $_GET["user"] : "");
	$selectedUser = null;
	$items = null;
	
	$sql = "SELECT * FROM `utilisateur` WHERE `ID` = ". $selectedUserID .";";
	
	$items = null;
	$mysqli = new mysqli($_DATABASE["host"],$_DATABASE["user"],$_DATABASE["password"],$_DATABASE["BDD"]);
	mysqli_set_charset($mysqli, "utf8");
	
	if ($mysqli -> connect_errno) {
		$erreur .= "Failed to connect to MySQL: " . $mysqli -> connect_error;
	}
	if ($result = $mysqli -> query($sql)) {
		$selectedUser = mysqli_fetch_assoc($result);
		
		if($selectedUser["TypeCompte"] > 1)
		{
			$sql = "
			SELECT i.*, 
			CASE WHEN EXISTS (SELECT m.`Lien` FROM `medias` AS m WHERE m.`ItemID` = i.`ID` AND m.`Ordre` = 0 AND m.`type` = 1 )
				THEN (SELECT m.`Lien` FROM `medias` AS m WHERE m.`ItemID` = i.`ID` AND m.`Ordre` = 0 AND m.`type` = 1 )
				ELSE './img/notfound.jpg'
			END AS `Lien`
			FROM `item` AS i 
			WHERE i.`OwnerID` = ". $selectedUserID ."
			AND i.`EtatVente` = 1
			ORDER BY `EtatVente` DESC, `dateMiseEnLigne` DESC;";
			
			
			$mysqli = new mysqli($_DATABASE["host"],$_DATABASE["user"],$_DATABASE["password"],$_DATABASE["BDD"]);
			mysqli_set_charset($mysqli, "utf8");
			
			if ($mysqli -> connect_errno) {
				$erreur .= "Failed to connect to MySQL: " . $mysqli -> connect_error;
			}
			if ($result = $mysqli -> query($sql)) {
				if (mysqli_num_rows($result) > 0) {
					
					$items = Array();
					while ($row = mysqli_fetch_assoc($result))
					{
						if($row["Categorie"] == "ferraille") $_categorie = "Ferraille ou trésor";
						else if($row["Categorie"] == "musee") $_categorie = "Bon pour le musée";
						else if($row["Categorie"] == "VIP") $_categorie = "Accessoire VIP";
						
						
						$_lien = "";
						if(file_exists($row["Lien"]))
							$_lien = $row["Lien"];
						else
							$_lien = "./img/notfound.jpg";
						
						array_push($items, Array(
							"ID" => $row["ID"],
							"Nom" => $row["Nom"],
							"DescriptionQ" => $row["DescriptionQualites"],
							"DescriptionD" => $row["DescriptionDefauts"],
							"Categorie" => $_categorie,
							"EtatVente" => $row["EtatVente"],
							"ModeVente" => $row["ModeVente"],
							"PrixDepart" => $row["PrixDepart"],
							"VenteDirect" => $row["VenteDirect"],
							"PrixVenteDirect" => $row["PrixVenteDirect"],
							"dateMiseEnLigne" => $row["dateMiseEnLigne"],
							"image" => $_lien
						));
					}
					$result -> free_result();
					$mysqli -> close();
				}
				else
				{
					$items = false;
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
	else
	{
		$erreur .= "Une erreur est survenue";
	}
?>

<?php 
	include("./template/_top.php");

	if($selectedUser && $selectedUser["TypeCompte"] > 1)
	{
		if($selectedUser["StyleFavoris"] == 0)
			include("./vendeurStyle1.php"); 
		else
			include("./vendeurStyle2.php"); 
	}
	else
	{
		echo "Cet utilisateur n'est pas vendeur.";
	}

	
	
	

?>








