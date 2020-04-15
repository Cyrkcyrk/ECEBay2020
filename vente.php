<?php
	$erreur = ""; 
	
	$categorie = isset($_GET["cat"])? $_GET["cat"] : "";
	$type = isset($_GET["type"])? $_GET["type"] : "";
	
	
	if($logged)
	{
		// $sql = "SELECT * FROM `item` WHERE `OwnerID` = '" . $user["ID"] . "' ORDER BY `EtatVente` DESC, `dateMiseEnLigne` DESC;";
		$sql = "SELECT i.*, m.`Lien` FROM `item` AS i INNER JOIN `medias` AS m ON m.`ItemID` = i.`ID` WHERE `OwnerID` = '" . $user["ID"] . "' ORDER BY `EtatVente` DESC, `dateMiseEnLigne` DESC;";
		
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




<div class="container">
	<div class="row">
		<?php
			if($items)
			{
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
			}
			else
			{
				echo "		Vous ne vendez rien pour l'instant <br>";
				echo $sql;
			}
		?>
		<div><a href='./?page=ajouterItem'>Vendre un item</a></div>
	</div>

</div>








