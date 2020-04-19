<?php
	$erreur = ""; 
	
	$categorie = blindage(isset($_GET["cat"])? $_GET["cat"] : "");
	$type = blindage(isset($_GET["type"])? $_GET["type"] : "");
	$search = blindage(isset($_GET["search"])? $_GET["search"] : "");
	
	if(in_array($categorie, Array("ferraille", "musee", "VIP"), TRUE) || in_array($type, Array("encheres", "offre", "directe"), TRUE) || $search)
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

			$sql = "
			SELECT i.*, 
			CASE WHEN EXISTS (SELECT m.`Lien` FROM `medias` AS m WHERE m.`ItemID` = i.`ID` AND m.`Ordre` = 0 AND m.`type` = 1 )
				THEN (SELECT m.`Lien` FROM `medias` AS m WHERE m.`ItemID` = i.`ID` AND m.`Ordre` = 0 AND m.`type` = 1 )
				ELSE './img/notfound.jpg'
			END AS `Lien`
			FROM `item` AS i 
			WHERE i.`Categorie` = '". $categorie ."' 
			AND i.`EtatVente` = 1 
			ORDER BY i.`dateMiseEnLigne` DESC;";
		}
		else if ($search != "")
		{
			$searchQuestion =  isset($_POST["searchQuestion"])? strtolower($_POST["searchQuestion"]) :"";
			
			$nomPage = "Résultats pour : " . $searchQuestion;
			$sql = "
			SELECT i.*, 
			CASE WHEN EXISTS (SELECT m.`Lien` FROM `medias` AS m WHERE m.`ItemID` = i.`ID` AND m.`Ordre` = 0 AND m.`type` = 1 )
				THEN (SELECT m.`Lien` FROM `medias` AS m WHERE m.`ItemID` = i.`ID` AND m.`Ordre` = 0 AND m.`type` = 1 )
				ELSE './img/notfound.jpg'
			END AS `Lien`
			FROM `item` AS i 
			WHERE 
			   (LOWER(i.`Nom`) LIKE '%". $searchQuestion ."%'
			OR	LOWER(i.`DescriptionQualites`) LIKE '%". $searchQuestion ."%'
			OR	LOWER(i.`DescriptionDefauts`) LIKE '%". $searchQuestion ."%');";
			
			
			
		}
		else {
			switch ($type)
			{
				case "offre":
					$nomPage = "Meilleur offre";
					
					$sql = "
					SELECT i.*, 
					CASE WHEN EXISTS (SELECT m.`Lien` FROM `medias` AS m WHERE m.`ItemID` = i.`ID` AND m.`Ordre` = 0 AND m.`type` = 1 )
						THEN (SELECT m.`Lien` FROM `medias` AS m WHERE m.`ItemID` = i.`ID` AND m.`Ordre` = 0 AND m.`type` = 1 )
						ELSE './img/notfound.jpg'
					END AS `Lien`
					FROM `item` AS i 
					WHERE i.`ModeVente` = 2 
					AND i.`EtatVente` = 1 
					ORDER BY i.`dateMiseEnLigne` DESC;";
					
					
					break;
				case "encheres":
					$nomPage = "Vente aux enchères";
					$sql = "
					SELECT i.*, 
					CASE WHEN EXISTS (SELECT m.`Lien` FROM `medias` AS m WHERE m.`ItemID` = i.`ID` AND m.`Ordre` = 0 AND m.`type` = 1 )
						THEN (SELECT m.`Lien` FROM `medias` AS m WHERE m.`ItemID` = i.`ID` AND m.`Ordre` = 0 AND m.`type` = 1 )
						ELSE './img/notfound.jpg'
					END AS `Lien`
					FROM `item` AS i 
					WHERE i.`ModeVente` = 1
					AND i.`EtatVente` = 1 
					ORDER BY i.`dateMiseEnLigne` DESC;";
					break;
				case "directe":
					$nomPage = "Achetez le maintenant";
					
					$sql = "
					SELECT i.*, 
					CASE WHEN EXISTS (SELECT m.`Lien` FROM `medias` AS m WHERE m.`ItemID` = i.`ID` AND m.`Ordre` = 0 AND m.`type` = 1 )
						THEN (SELECT m.`Lien` FROM `medias` AS m WHERE m.`ItemID` = i.`ID` AND m.`Ordre` = 0 AND m.`type` = 1 )
						ELSE './img/notfound.jpg'
					END AS `Lien`
					FROM `item` AS i 
					i.`VenteDirect` = 1
					AND i.`EtatVente` = 1 
					ORDER BY i.`dateMiseEnLigne` DESC;";
					break;
			}
		}
		
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
	<h2><?php echo $nomPage;?></h2>
	<div class="row">
		<?php
			if($items && count($items) > 0)
			{
				forEach($items as $i)
				{
					echo '		<div class="col-lg-4 col-md-6 mb-4">' ."\n";
					echo '			<div class="card h-100">'."\n";
					echo '				<a href="?page=item&item='. $i["ID"] .'"><img class="card-img-top" src="'. $i["image"] .'" alt=""></a>'."\n";
					echo '				<div class="h-100">&ensp;</div>'."\n";
					echo '				<div class="card-body">'."\n";
					echo '					<h4 class="card-title">'."\n";
					echo '						<a href="?page=item&item='. $i["ID"] .'">'. $i["Nom"] .'</a>'."\n";
					echo '					</h4>'."\n";
					if($type=="encheres")
						echo '					<h5>Début des enchères à&nbsp;'. $i["PrixDepart"] .'€</h5>'."\n";
						echo '<p>Fin de l&apos;enchère le&nbsp'.date("d-m-Y", strtotime("+7 days", $i["dateMiseEnLigne"]))."</p>\n";
					if($type=="offre")
						echo '					<h5>Prix de départ&nbsp;'. $i["PrixDepart"] .'€</h5>'."\n";
					if($type=="directe")
						echo '					<h5>Prix d&apos;achat&nbsp;'. $i["PrixVenteDirect"] .'€</h5>'."\n";
					if($type=="")
					{
						if($i["ModeVente"]=="0")
							echo '					<h5>Prix d&apos;achat&nbsp;'. $i["PrixVenteDirect"] .'€</h5>'."\n";
						if($i["ModeVente"]=="1")
						{
							if($i["PrixVenteDirect"]>"0")
								echo '					<h5>Prix d&apos;achat à&nbsp;'. $i["PrixVenteDirect"] .'€</h5>'."\n";
							echo '					<h5>Début des enchères à&nbsp;'. $i["PrixDepart"] .'€</h5>'."\n";
							echo '<p>Fin de l&apos;enchère le&nbsp'.date("d-m-Y", strtotime("+7 days", $i["dateMiseEnLigne"]))."</p>\n";

						}
						if($i["ModeVente"]=="2")
						{
							if($i["PrixVenteDirect"]>"0")
								echo '					<h5>Prix d&apos;achat à&nbsp;'. $i["PrixVenteDirect"] .'€</h5>'."\n";
							echo '					<h5>Prix de départ&nbsp;'. $i["PrixDepart"] .'€</h5>'."\n";

						}
					}
					echo '					<p class="card-text">'. $i["DescriptionQ"] .'</p>'."\n";
					echo '				</div>'."\n";
					echo '			</div>'."\n";
					echo '		</div>'."\n";
				}
			}
			else
			{
				echo "Aucun résultat a afficher";
			}
		?>

	</div>

</div>
<?php include("./template/_bot.php"); ?>