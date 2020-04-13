<?php
	$erreur = ""; 
	
	$categorie = isset($_GET["cat"])? $_GET["cat"] : "";
	$type = isset($_GET["type"])? $_GET["type"] : "";
	
	if(in_array($categorie, Array("ferraille", "musee", "VIP"), TRUE) || in_array($type, Array("encheres", "offre", "directe"), TRUE))
	{
		$sql = "";
		if($categorie != "") {
			switch ($categorie)
			{
				case "ferraille":
					$nomPage = "Ferraille ou trésor";
					break;
				case "musee":
					$nomPage = "Bon pour le musée";
					break;
				case "VIP":
					$nomPage = "Accessoire VIP";
					break;
			}
			$sql = "SELECT * FROM `item` WHERE `Categorie` = '". $categorie ."' AND `EtatVente` = 1 ORDER BY `dateMiseEnLigne` DESC;";
		}
		else {
			switch ($type)
			{
				case "offre":
					$nomPage = "Meilleur offre";
					$sql = "SELECT * FROM `item` WHERE `ModeVente` = 2 AND `EtatVente` = 1 ORDER BY `dateMiseEnLigne` DESC;";
					break;
				case "encheres":
					$nomPage = "Vente aux enchères";
					$sql = "SELECT * FROM `item` WHERE `ModeVente` = 1 AND `EtatVente` = 1 ORDER BY `dateMiseEnLigne` DESC;";
					break;
				case "directe":
					$nomPage = "Achetez le maintenant";
					$sql = "SELECT * FROM `item` WHERE `VenteDirect` = 1 AND `EtatVente` = 1 ORDER BY `dateMiseEnLigne` DESC;";
					break;
			}
		}
		
		
		$items = null;
		$mysqli = new mysqli($_DATABASE["host"],$_DATABASE["user"],$_DATABASE["password"],$_DATABASE["BDD"]);
		mysqli_set_charset($mysqli, "utf8");
		
		if ($mysqli -> connect_errno) {
			$erreur .= "Failed to connect to MySQL: " . $mysqli -> connect_error;
		}
		if ($result = $mysqli -> query($sql)) {
			if (mysqli_num_rows($result) > 0) {
				
				$items = Array();
				$_compteur = 0;
				while ($row = mysqli_fetch_assoc($result))
				{
					if($row["Categorie"] == "ferraille") $_categorie = "Ferraille ou trésor";
					else if($row["Categorie"] == "musee") $_categorie = "Bon pour le musée";
					else if($row["Categorie"] == "VIP") $_categorie = "Accessoire VIP";
					
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
						"dateMiseEnLigne" => $row["dateMiseEnLigne"]
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
		
		echo "<div id='items'>";
		echo "<h2> ". $nomPage . "</h2>";
		if(!$items)
		{
			echo "Aucun item en vente dans cette catégorie <br>";
		}
		else
		{
			foreach($items as $item)
			{
				$image = "";
				$sql = "SELECT * FROM `medias` WHERE `ItemID` = ". $item["ID"] . " AND `type` = 1 ORDER BY `Ordre` ASC LIMIT 1;";
				
				$mysqli = new mysqli($_DATABASE["host"],$_DATABASE["user"],$_DATABASE["password"],$_DATABASE["BDD"]);
				mysqli_set_charset($mysqli, "utf8");
				
				if ($mysqli -> connect_errno) {
					$erreur .= "Failed to connect to MySQL: " . $mysqli -> connect_error;
				}
				if ($result = $mysqli -> query($sql)) {
					if (mysqli_num_rows($result) > 0) {
						
						$image = mysqli_fetch_assoc($result)["Lien"];

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
				
				
				
				
				$string_items = '';
				$string_items .= "<div class='item'>";
				if($image != "")
				{
					$string_items .= "\n<div class='image'>";
					$string_items .= "<img src='". $image . "'>";
					$string_items .= "</div>";
				}
				$string_items .= "\n<div class='description'>";
				$string_items .= "Nom : " . $item["Nom"] . "<br>";
				$string_items .= "Points positifs : " . $item["DescriptionQ"] . "<br>";
				$string_items .= "Points négatifs : " . $item["DescriptionD"] . "<br>";
				$string_items .= "Catégorie : " . $item["Categorie"] . "<br>";
				
				if($item["VenteDirect"] == 1)
					$string_items .= "Vente directe pour " . $item["PrixVenteDirect"] . "€.<br>";
				if($item["ModeVente"] == 1)
					$string_items .= "Enchère commencant à " . $item["PrixDepart"] . "€.<br>";
				else if($item["ModeVente"] == 2)
					$string_items .= "Offre commencant à " . $item["PrixDepart"] . "€.<br>";
				
				$string_items .= "</div>\n</div>";
				
				echo $string_items;
			}
		}
		echo "</div>";
		
	}
	else
	{
		redirect('./?page=accueil');
	}
?>

