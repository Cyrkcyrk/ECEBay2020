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
			$sql = "SELECT i.*, m.`Lien` FROM `item` AS i INNER JOIN `medias` AS m ON m.`ItemID` = i.`ID` WHERE i.`Categorie` = '". $categorie ."' AND i.`EtatVente` = 1 AND m.`type` = 1 AND m.`Ordre` = 0 ORDER BY i.`dateMiseEnLigne` DESC;";
		}
		else {
			switch ($type)
			{
				case "offre":
					$nomPage = "Meilleur offre";
					$sql = "SELECT i.*, m.`Lien` FROM `item` AS i INNER JOIN `medias` AS m ON m.`ItemID` = i.`ID` WHERE i.`ModeVente` = 2 AND i.`EtatVente` = 1 AND m.`type` = 1 AND m.`Ordre` = 0 ORDER BY i.`dateMiseEnLigne` DESC;";
					break;
				case "encheres":
					$nomPage = "Vente aux enchères";
					$sql = "SELECT i.*, m.`Lien` FROM `item` AS i INNER JOIN `medias` AS m ON m.`ItemID` = i.`ID` WHERE i.`ModeVente` = 1 AND i.`EtatVente` = 1 AND m.`type` = 1 AND m.`Ordre` = 0 ORDER BY i.`dateMiseEnLigne` DESC;";
					break;
				case "directe":
					$nomPage = "Achetez le maintenant";
					$sql = "SELECT i.*, m.`Lien` FROM `item` AS i INNER JOIN `medias` AS m ON m.`ItemID` = i.`ID` WHERE i.`VenteDirect` = 1 AND i.`EtatVente` = 1 AND m.`type` = 1 AND m.`Ordre` = 0 ORDER BY i.`dateMiseEnLigne` DESC;";
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
						"dateMiseEnLigne" => $row["dateMiseEnLigne"],
						"image" => $row["Lien"],
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
	else
	{
		redirect('./?page=accueil');
	}
?>

<?php include("./template/_top.php"); ?>


<div class="container">
	<div class="row">
		<?php
			forEach($items as $i)
			{
				echo '		<div class="col-lg-4 col-md-6 mb-4">' ."\n";
				echo '			<div class="card h-100">'."\n";
				echo '				<a href="?page=item&item='. $i["ID"] .'"><img class="card-img-top" src="'. $i["image"] .'" alt=""></a>'."\n";
				echo '				<div class="card-body">'."\n";
				echo '					<h4 class="card-title">'."\n";
				echo '						<a href="?page=item&item='. $i["ID"] .'">'. $i["Nom"] .'</a>'."\n";
				echo '					</h4>'."\n";
				echo '					<h5>'. $i["PrixDepart"] .'</h5>'."\n";
				echo '					<p class="card-text">'. $i["DescriptionQ"] .'</p>'."\n";
				echo '				</div>'."\n";
				echo '			</div>'."\n";
				echo '		</div>'."\n";
			}
		?>

	</div>

</div>
<?php include("./template/_bot.php"); ?>