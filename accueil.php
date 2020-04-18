<?php
	$items = null;
	
	$sql = "SELECT i.*, m.`Lien` FROM `item` AS i INNER JOIN `medias` AS m ON m.`ItemID` = i.`ID` JOIN (SELECT `ID` FROM `item` ORDER BY `dateMiseEnLigne` DESC LIMIT 30) AS d ON d.`ID` = i.`ID` WHERE i.`EtatVente` = 1 AND m.`type` = 1 AND m.`Ordre` = 0 ORDER BY RAND() LIMIT 6";
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
?>
<?php include("./template/_top.php"); ?>

<div class="container">
	<div id="carouselExampleIndicators" class="carousel slide my-4" data-ride="carousel">
		<ol class="carousel-indicators">
			<li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
			<li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
			<li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
		</ol>
		<div class="carousel-inner" role="listbox">
			<div class="carousel-item active">
				<a href="./?page=categories&cat=ferraille"><img class="d-block img-fluid mx-auto" src="./img/Ferraille.png" alt="Ferraille ou trésor"></a>
			</div>
			<div class="carousel-item">
				<a href="./?page=categories&cat=musee"><img class="d-block img-fluid mx-auto" src="./img/musee.png" alt="Bon pour le musée"></a>
			</div>
			<div class="carousel-item">
				<a href="./?page=categories&cat=VIP"><img class="d-block img-fluid mx-auto" src="./img/VIP.png" alt="Accessoires VIP"></a>
			</div>
		</div>
		<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
			<span class="sr-only">Previous</span>
		</a>
		<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
			<span class="carousel-control-next-icon" aria-hidden="true"></span>
			<span class="sr-only">Next</span>
		</a>
	</div>
	
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