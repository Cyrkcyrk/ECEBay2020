<?php 
	
	$erreur = "";
	$items = "";
	$TotalImmediat = 0.00;
	$TotalTout = 0.00;
	$NombreArticles = 0;
	
	if($logged)
	{
		$sql = "SELECT p.`ID` AS 'PanierID', p.`TypeAchat`, i.* FROM `panier` AS p JOIN (SELECT i.*, m.`Lien` FROM `item` AS i INNER JOIN `medias` AS m ON m.`ItemID` = i.`ID` WHERE m.`type` = 1 AND m.`Ordre` = 0) AS i ON i.`ID` = p.`ItemID` WHERE p.`OwnerID` = ". $user["ID"] ." ORDER BY `Date` DESC";
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
						"PanierID" => $row["PanierID"],
						"TypeAchat" => $row["TypeAchat"]
					));
					$NombreArticles += 1;
					if($row["TypeAchat"] == 0)
					{
						$TotalImmediat += $row["PrixVenteDirect"];
						$TotalTout = $row["PrixVenteDirect"];
					}
					
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
	<div class='row'>
		<div class='col-md-2'>
			<p>Article</p>
		</div>
		<div class='col-md-7'>
		</div>
		<div class='col-md-3 px-3'>
			<div class='float-right'>
				Prix
			</div>
		</div>
	</div>
	<?php
		if(!$items)
		{
			echo "Votre panier est vide. <br>";
		}
		else
		{
			forEach($items as $i)
			{
				//https://bootsnipp.com/snippets/XR0Dv
				if($i['TypeAchat'] == 0)
				{
					echo "	<div class='py-2'>\n";
					echo "		<div class='card'>\n";
					echo "			<div class='row '>\n";
					echo "				<div class='col-md-2'>\n";
					echo "					<img src='". $i["image"] ."' class='w-100'>\n";
					echo "				</div>\n";
					echo "				<div class='col-md-7'>\n";
					echo "					<div class='card-block px-3'>\n";
					echo "						<h4 class='card-title'><a href=?page=item&item='". $i["ID"] ."'>" . $i["Nom"] ."</a></h4>\n";
					echo "						<h4 class='card-title'><a href=?page=supprimerDuPanier&ID='". $i['PanierID'] ."'>" . "Supprimer" ."</a></h4>\n";
					echo "					</div>\n";
					echo "				</div>\n";
					echo "				<div class='col-md-3'>\n";
					echo "					<div class='card-block px-3 float-right'>\n";
					echo "						<h5>". $i["PrixVenteDirect"] ." € </h5>\n";
					echo "					</div>\n";
					echo "				</div>\n";
					echo "			</div>\n";
					echo "		</div>\n";
					echo "	</div>\n";
				}
			}
		}
	?>
	<div class='row'>
		<div class='col-md-9'>
		</div>
		<div class='col-md-3 px-3'>
			<div class='float-right'>
				<?php
					if($NombreArticles < 2)
					{
						echo "Total (". $NombreArticles ." article) : ";
					}
					else
					{
						echo "Total (". $NombreArticles ." articles) : ";
					}
					echo $TotalImmediat . "€";
				?>
			</div>
		</div>
	</div>
		

</div>
<?php include("./template/_bot.php"); ?>