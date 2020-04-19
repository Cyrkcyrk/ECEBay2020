<?php
	$erreur = ""; 
	
	$categorie = blindage(isset($_GET["cat"])? $_GET["cat"] : "");
	$type = blindage(isset($_GET["type"])? $_GET["type"] : "");
	
	
	if($logged)
	{
		// $sql = "SELECT * FROM `item` WHERE `OwnerID` = '" . $user["ID"] . "' ORDER BY `EtatVente` DESC, `dateMiseEnLigne` DESC;";
		// $sql = "SELECT i.*, m.`Lien` FROM `item` AS i LEFT JOIN `medias` AS m ON m.`ItemID` = i.`ID` WHERE `OwnerID` = '" . $user["ID"] . "' AND m.`Ordre` = 0 ORDER BY `EtatVente` DESC, `dateMiseEnLigne` DESC;";
		
		$sql = "
		SELECT i.*, 
		CASE WHEN EXISTS (SELECT m.`Lien` FROM `medias` AS m WHERE m.`ItemID` = i.`ID` AND m.`Ordre` = 0)
			THEN (SELECT m.`Lien` FROM `medias` AS m WHERE m.`ItemID` = i.`ID` AND m.`Ordre` = 0)
			ELSE './img/notfound.jpg'
		END AS `Lien`
		FROM `item` AS i 
		WHERE `OwnerID` = ". $user["ID"] ."
		ORDER BY `EtatVente` DESC, `dateMiseEnLigne` DESC;";
		
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
			if($items)
			{
				forEach($items as $i)
				{
					echo "	<div class='py-2'>\n";
					echo "		<div class='card'>\n";
					echo "			<div class='row '>\n";
					echo "				<div class='col-md-2'>\n";
					echo "					<img src='". $i["image"] ."' class='w-100'>\n";
					echo "				</div>\n";
					echo "				<div class='col-md-7'>\n";
					echo "					<div class='card-block px-3'>\n";
					echo "						<h4 class='card-title'><a href=?page=item&item=". $i["ID"] .">" . $i["Nom"] ."</a></h4>\n";
					echo "						<p class='card-text'>". $i["DescriptionQ"] ."</h4>\n";
					echo "						<p class='card-text'>". $i["DescriptionD"] ."</h4>\n";
					if($i["ModeVente"] == 1)
						echo "						<p class='card-text'>Fin de l'enchère le ". date("d-m-Y", strtotime("+7 days", $i["dateMiseEnLigne"])) ."</h4>\n";
					echo "					</div>\n";
					echo "				</div>\n";
					echo "				<div class='col-md-3'>\n";
					echo "					<div class='card-block px-3'>\n";
					echo "						<div class='text-right'>\n";
					if($i["VenteDirect"] == 1)
						echo "							<p class='card-text'>Prix vente Directe ". $i["PrixVenteDirect"] ." € </p>\n";
					if($i["ModeVente"] == 1)
						echo "							<p class='card-text'>Prix enchère ". $i["PrixDepart"] ." € </p>\n";
					else if($i["ModeVente"] == 2)
						echo "							<p class='card-text'>Prix offre ~". $i["PrixDepart"] ." € </p>\n";
					echo "						</div>\n";
					echo "					</div>\n";
					echo "				</div>\n";
					echo "			</div>\n";
					echo "			<div class='text-right'>\n";
					echo "				<a href='./?page=supprimerItem&ID=". $i["ID"] ."&pp=admin' class='card-link'>Supprimer item</a>\n";
					echo "			</div>\n";
					echo "		</div>\n";
					echo "	</div>\n";
				}
			}
			else
			{
				echo "		Vous ne vendez rien pour l'instant <br>";
			}
		?>
		<div><a href='./?page=ajouterItem'>Vendre un item</a></div>
	</div>

</div>
<?php include("./template/_bot.php"); ?>








