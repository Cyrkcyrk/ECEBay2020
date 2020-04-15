<?php 
	
	$erreur = "";
	$items = "";
	
	if($logged)
	{
		$sql = "SELECT p.`ID` AS 'PanierID', i.* FROM `panier` AS p JOIN (SELECT i.*, m.`Lien` FROM `item` AS i INNER JOIN `medias` AS m ON m.`ItemID` = i.`ID` WHERE m.`type` = 1 AND m.`Ordre` = 0) AS i ON i.`ID` = p.`ItemID` WHERE p.`OwnerID` = ". $user["ID"] ." ORDER BY `Date` DESC";
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
				
			}
			else
			{
				$items = false;
				$erreur .= "Cet item n'existe pas<br>";
			}
			$result -> free_result();
			$mysqli -> close();
		}
		else
		{
			$erreur .= "Une erreur est survenue";
		}
		
		
		if($erreur != "")
			echo $erreur;
	}
	else
	{
		redirect("./?page=login");
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