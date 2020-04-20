<?php 
	
	$erreur = "";
	$items = "";
	$TotalImmediat = 0.00;
	$NombreImmediat = 0;
	
	
	
	$TotalTout = 0.00;
	$NombreArticles = 0;
	
	if($logged)
	{
		$sql = "
		SELECT 
			p.`ID` AS 'PanierID', 
			p.`TypeAchat`, 
			i.* 
		FROM `panier` AS p 
		JOIN (SELECT i.*, m.`Lien` FROM `item` AS i INNER JOIN `medias` AS m ON m.`ItemID` = i.`ID` WHERE m.`type` = 1 AND m.`Ordre` = 0) AS i 
			ON i.`ID` = p.`ItemID` 
		WHERE p.`OwnerID` = ". $user["ID"] ."
		AND i.`EtatVente` >= 0
		ORDER BY `Date` DESC";
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
						"image" => $_lien,
						"PanierID" => $row["PanierID"],
						"TypeAchat" => $row["TypeAchat"]
					));
					
					if($row["TypeAchat"] == 0 && $row["EtatVente"] == 1)
					{
						$NombreImmediat += 1;
						$NombreArticles += 1;
						
						$TotalImmediat += $row["PrixVenteDirect"];
						$TotalTout += $row["PrixVenteDirect"];
					}
					
				}
				
			}
			else
			{
				$items = false;
			}
			$result -> free_result();
			$mysqli -> close();
		}
		else
		{
			$erreur .= "Une erreur est survenue";
		}
		
		
		$sql = "
		SELECT 
			e.`Prix` AS 'EncherePrix', 
			e.`ID` AS 'EnchereID',
			CASE WHEN EXISTS (SELECT tmpE.`Prix` FROM `encheres` AS tmpE WHERE tmpE.`ItemID` =  e.`ItemID` ORDER BY tmpE.`Prix` DESC LIMIT 1)
				THEN (SELECT tmpE.`ID` FROM `encheres` AS tmpE WHERE tmpE.`ItemID` =  e.`ItemID` ORDER BY tmpE.`Prix` DESC LIMIT 1)
				ELSE 0
			END AS 'EncherePrixMaxID',
			i.*
		FROM `encheres` AS e 
		JOIN (
			SELECT 
				TMPi.*, 
				TMPm.`Lien`,
				CASE WHEN EXISTS (SELECT `Prix` FROM `encheres` WHERE  `encheres`.`ItemID` = TMPi.`ID` ORDER BY `Prix` DESC LIMIT 1,1)
					THEN (SELECT `Prix` FROM `encheres` WHERE  `encheres`.`ItemID` = TMPi.`ID` ORDER BY `Prix` DESC LIMIT 1,1) + 1
					WHEN EXISTS (SELECT `Prix` FROM `encheres` WHERE  `encheres`.`ItemID` = TMPi.`ID` ORDER BY `Prix` DESC LIMIT 1)
					THEN TMPi.`PrixDepart`+1
					ELSE TMPi.`PrixDepart`
				END AS 'PrixAPayer'
			FROM `item` AS TMPi
			INNER JOIN `medias` AS TMPm 
				ON TMPm.`ItemID` = TMPi.`ID` 
			WHERE TMPm.`type` = 1 
			AND TMPm.`Ordre` = 0
		) AS i
			ON i.`ID` = e.`ItemID`
		WHERE i.`EtatVente` > 0
		AND e.`BuyerID` = ". $user["ID"] . ";";
		
		$itemsEnchere = "";
		$TotalEnchere = 0.00;
		$NombreEncheres = 0;
		
		$mysqli = new mysqli($_DATABASE["host"],$_DATABASE["user"],$_DATABASE["password"],$_DATABASE["BDD"]);
		mysqli_set_charset($mysqli, "utf8");

		if ($mysqli -> connect_errno) {
			$erreur .= "Failed to connect to MySQL: " . $mysqli -> connect_error;
		}
		if ($result = $mysqli -> query($sql)) {
			if (mysqli_num_rows($result) > 0) {
				$itemsEnchere = Array();
				while ($row = mysqli_fetch_assoc($result))
				{
					if($row["Categorie"] == "ferraille") $_categorie = "Ferraille ou trésor";
					else if($row["Categorie"] == "musee") $_categorie = "Bon pour le musée";
					else if($row["Categorie"] == "VIP") $_categorie = "Accessoire VIP";
					
					if($row["EnchereID"] == $row["EncherePrixMaxID"])
						$EnchereValide = True;
					else
						$EnchereValide = False;
					
					array_push($itemsEnchere, Array(
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
						
						"EnchereValide" => $EnchereValide,
						"PrixAPayer" => $row["PrixAPayer"],
						"EnchereActuelle" => $row["EncherePrix"]
						
					));
					if($EnchereValide && $row["EtatVente"] == 1)
					{
						$NombreEncheres += 1;
						$NombreArticles += 1;
						
						$TotalEnchere += $row["PrixAPayer"];
						$TotalTout += $row["PrixAPayer"];
					}
				}
			}
			else
			{
				$itemsEnchere = false;
			}
			$result -> free_result();
			$mysqli -> close();
		}
		else
		{
			$erreur .= "Une erreur est survenue";
		}
	}
	else
	{
		redirect("./?page=login");
	}
	
?>
<?php include("./template/_top.php"); ?>

<div class="container">
	<h2>Achat Directs</h2>
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
				if($i['TypeAchat'] == 0 && $i["EtatVente"] == 1)
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
					echo "						<h7 class='card-title'><a href=?page=supprimerDuPanier&ID=". $i['PanierID'] .">" . "Supprimer du panier" ."</a></h7>\n";
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
						echo "Total (". $NombreImmediat ." articles) : ";
					}
					echo $TotalImmediat . "€";
					
					echo "<br><a href='./?page=validerPanier'>Valider et acheter</a>";
					
				?>
			</div>
		</div>
	</div>
	
	
	<hr>
	<h2>Encheres</h2>
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
		if(!$itemsEnchere)
		{
			echo "Vous n'avez pas d'encheres. <br>";
		}
		else
		{
			forEach($itemsEnchere as $i)
			{
				//https://bootsnipp.com/snippets/XR0Dv
				echo "	<div class='py-2'>\n";
				if($i["EnchereValide"] && $i["EtatVente"] == 1){
					echo "		<div class='card enchereValide' >\n";
				} else {
					echo "		<div class='card enchereInvalide'>\n";
				}
				echo "			<div class='row '>\n";
				echo "				<div class='col-md-2'>\n";
				echo "					<img src='". $i["image"] ."' class='w-100'>\n";
				echo "				</div>\n";
				echo "				<div class='col-md-7'>\n";
				echo "					<div class='card-block px-3'>\n";
				echo "						<h4 class='card-title'><a href=?page=item&item=". $i["ID"] .">" . $i["Nom"] ."</a></h4>\n";
				echo "					</div>\n";
				echo "				</div>\n";
				echo "				<div class='col-md-3'>\n";
				echo "					<div class='card-block px-3 float-right'>\n";
				
				if($i["EnchereValide"])
				{
					echo "						<p>Votre enchere maximum </p><h5>". $i["EnchereActuelle"] ." €</h5>\n";
					echo "						<p>Prix à payer </p><h5>". $i["PrixAPayer"] ." € </h5>\n";
				}
				else
				{
					echo "						<p>Votre enchere maximum</p><h5>". $i["EnchereActuelle"] ." €</h5>\n";
					echo "						<p>Surenchère à faire </p><h5>". $i["PrixAPayer"] ." € </h5>\n";
				}
				echo "					</div>\n";
				echo "				</div>\n";
				echo "			</div>\n";
				echo "		</div>\n";
				echo "	</div>\n";
			}
		}
	?>
	<div class='row'>
		<div class='col-md-9'>
		</div>
		<div class='col-md-3 px-3'>
			<div class='float-right'>
				<?php
					if($NombreEncheres < 2)
					{
						echo "Total (". $NombreEncheres ." article) : ";
					}
					else
					{
						echo "Total (". $NombreEncheres ." articles) : ";
					}
					echo $TotalEnchere . "€";
				?>
			</div>
		</div>
	</div>
	
	
	<?php
		if($erreur != "")
			echo "Erreur: ". $erreur;
	?>
	
	
	
	
	
	
	
	
	
	

</div>
<?php include("./template/_bot.php"); ?>