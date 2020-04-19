<?php
	$erreur = ""; 

	if($logged && $user["TypeCompte"] == 3)
	{
		$sql = "
				SELECT i.*, 
				CASE WHEN EXISTS (SELECT m.`Lien` FROM `medias` AS m WHERE m.`ItemID` = i.`ID` AND m.`Ordre` = 0 AND m.`type` = 1 )
					THEN (SELECT m.`Lien` FROM `medias` AS m WHERE m.`ItemID` = i.`ID` AND m.`Ordre` = 0 AND m.`type` = 1 )
					ELSE './img/notfound.jpg'
				END AS `Lien`
				FROM `item` AS i 
				ORDER BY i.`EtatVente` DESC, i.`dateMiseEnLigne` DESC;";
		
		
		$items = Array();
		$mysqli = new mysqli($_DATABASE["host"],$_DATABASE["user"],$_DATABASE["password"],$_DATABASE["BDD"]);
		mysqli_set_charset($mysqli, "utf8");
		
		if ($mysqli -> connect_errno) {
			$erreur .= "Failed to connect to MySQL: " . $mysqli -> connect_error;
		}
		if ($result = $mysqli -> query($sql)) {
			if (mysqli_num_rows($result) > 0) {
				
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
	else
	{
		redirect('./?page=accueil');
	}
?>

<?php include("./template/_top.php"); ?>


<div class="container">
	<h2>Administrer les items du site</h2>
	<?php
		if(!$items)
		{
			echo "Il n'y a aucun items. <br>";
		}
		else
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
	?>

	

</div>
</div>
<?php include("./template/_bot.php"); ?>