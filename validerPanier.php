<?php
	$erreur = "";
	$selectedCB =  blindage(isset($_POST["cb"])? str_replace('cb', '', $_POST["cb"]) :"");
	$selectedAD =  blindage(isset($_POST["adresse"])? str_replace('adresse', '', $_POST["adresse"]) :"");
	$valider =  isset($_POST["valider"])? $_POST["valider"] :"";
	
	$CB = false;
	
	if($valider != "")
	{
		if($selectedAD == "") {
		$erreur .= "Veuillez selectionner une adresse <br>";
		}
		if($selectedCB == "") {
			$erreur .= "Veuillez selectionner un moyen de paiement <br>";
		}

		
		if($erreur == "")
		{
			
			$sql = "
				SELECT * FROM `cartebancaire` AS CB
				JOIN `utilisateur` as U
					ON U.`ID` = CB.`OwnerID`
				WHERE CB.`ID` = ". $selectedCB ." AND U.`ID` = ". $user['ID'] .";";
			
			$mysqli = new mysqli($_DATABASE["host"],$_DATABASE["user"],$_DATABASE["password"],$_DATABASE["BDD"]);
			mysqli_set_charset($mysqli, "utf8");

			if ($mysqli -> connect_errno) {
				$erreur .= "Failed to connect to MySQL: " . $mysqli -> connect_error;
			}
			if ($result = $mysqli -> query($sql)) {
				if (mysqli_num_rows($result) > 0) {
					$CB = mysqli_fetch_assoc($result);
				}
				else
				{
					$CB = false;
				}
				$result -> free_result();
				$mysqli -> close();
			}
			else
			{
				$erreur .= "Une erreur est survenue <br>";
			}
		}
		
		if($CB)
		{
			$seuilPaiement = 0;
			switch ($CB["TypeCarte"])
			{
				case "Visa" : 
					$seuilPaiement = 1000.0;
					break;
				case "Mastercard":
					$seuilPaiement = 500.0;
					break;
				case "Paypal":
					$seuilPaiement = 100.0;
					break;
				case "American Express":
					$seuilPaiement = 10000.0;
					break;
			}
			
			$TotalAPayer = -1;
			
			$sql = "
				SELECT SUM(`PrixVenteDirect`) AS Total FROM `item` AS i
				JOIN `panier` AS p
					ON p.`ItemID` = i.`ID`
				WHERE 
					i.`EtatVente` = 1
				AND p.`OwnerID` = ". $user["ID"] .";";
			
			$mysqli = new mysqli($_DATABASE["host"],$_DATABASE["user"],$_DATABASE["password"],$_DATABASE["BDD"]);
			mysqli_set_charset($mysqli, "utf8");

			if ($mysqli -> connect_errno) {
				$erreur .= "Failed to connect to MySQL: " . $mysqli -> connect_error . "<br>";
			}
			if ($result = $mysqli -> query($sql)) {
				if (mysqli_num_rows($result) > 0) {
					$TotalAPayer = floatval (mysqli_fetch_assoc($result)["Total"]);
				}
				else
				{
					$TotalAPayer = false;
				}
				$result -> free_result();
				$mysqli -> close();
			}
			else
			{
				$erreur .= "Une erreur est survenue <br>";
			}
			
			
			if($TotalAPayer < $seuilPaiement)
			{
				
				$sql = "
				SELECT 
					i.* 
				FROM `panier` AS p 
				JOIN `item` AS i 
					ON i.`ID` = p.`ItemID` 
				WHERE i.`EtatVente` > 0
				AND p.`OwnerID` = ". $user["ID"] .";";
				
				
				$mysqli = new mysqli($_DATABASE["host"],$_DATABASE["user"],$_DATABASE["password"],$_DATABASE["BDD"]);
				mysqli_set_charset($mysqli, "utf8");

				if ($mysqli -> connect_errno) {
					$erreur .= "Failed to connect to MySQL: " . $mysqli -> connect_error . "<br>";
				}
				if ($result = $mysqli -> query($sql)) {
					if (mysqli_num_rows($result) > 0) {
						while($row = mysqli_fetch_assoc($result))
						{
							$sql = "UPDATE `offres` SET `IDOffreMessageAccepte` = -2 WHERE `ItemID` = ". $row["ID"] .";";
							list($_, $erreur) = SQLQuery($_DATABASE, $sql, $erreur);
							if(!$_)
								$erreur .= "Une erreur est survenue pendant la mise a jours des offres<br>";
							
						}
						
						
						$sql = "
							UPDATE `item` 
							INNER JOIN `panier`
								ON `item`.`ID` = `panier`.`ItemID` 
							SET `item`.`EtatVente` = 0
							WHERE `item`.`EtatVente` >= 0
							AND `panier`.`OwnerID` = ". $user["ID"] .";";
							
						list ($_, $erreur) = SQLQuery($_DATABASE, $sql, $erreur);
						if ($_)
						{
							redirect("./?page=confirmation");
						}
						else 
							$erreur .= "Une erreur est survenue pendant l'update des items achetés.<br>";
						
					}
					$result -> free_result();
					$mysqli -> close();
				}
				else
				{
					$erreur .= "Une erreur est survenue <br>";
				}
			}
			else
			{
				$seuilDepassement = $TotalAPayer - $seuilPaiement;
				$erreur .= "Votre carte bleue a été refusée (seuil de paiement dépassé de ". $seuilDepassement . "€ <br>";
			}
		}
		else
		{
			$erreur .= "Erreur avec la carte bleue selectionnée <br>";
		}
	}
?>














<?php 
	$items = "";
	
	$adresses = null;
	$cartesbancaires = null;
	
	$TotalImmediat = 0.00;
	$NombreImmediat = 0;
	
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
						
						$TotalImmediat += $row["PrixVenteDirect"];
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
		
		
		$sql = "SELECT * FROM `adresse` WHERE `OwnerID` = '" . $user["ID"] . "';";
		$mysqli = new mysqli($_DATABASE["host"],$_DATABASE["user"],$_DATABASE["password"],$_DATABASE["BDD"]);
		mysqli_set_charset($mysqli, "utf8");
		
		if ($mysqli -> connect_errno) {
			$erreur .= "Failed to connect to MySQL: " . $mysqli -> connect_error;
		}
		if ($result = $mysqli -> query($sql)) {
			if (mysqli_num_rows($result) > 0) {
				
				$adresses = Array();
				
				while ($row = mysqli_fetch_assoc($result))
				{
					array_push($adresses, Array(
						"ID" => $row["ID"],
						"Ligne1" => $row["Ligne1"],
						"Ligne2" => $row["Ligne2"],
						"Ville" => $row["Ville"],
						"CodePostal" => $row["CodePostal"],
						"Pays" => $row["Pays"],
						"Telephone" => $row["Telephone"]
					));
				}
				$result -> free_result();
				$mysqli -> close();
			}
			else
			{
				$adresses = false;
				$result -> free_result();
				$mysqli -> close();
			}
		}
		else
		{
			$erreur .= "Une erreur est survenue";
		}
		
		
		
		
		
		$sql = "SELECT * FROM `cartebancaire` WHERE `OwnerID` = '" . $user["ID"] . "';";
		$mysqli = new mysqli($_DATABASE["host"],$_DATABASE["user"],$_DATABASE["password"],$_DATABASE["BDD"]);
		mysqli_set_charset($mysqli, "utf8");
		
		if ($mysqli -> connect_errno) {
			$erreur .= "Failed to connect to MySQL: " . $mysqli -> connect_error;
		}
		if ($result = $mysqli -> query($sql)) {
			if (mysqli_num_rows($result) > 0) {
				
				$cartesbancaires = Array();
				while ($row = mysqli_fetch_assoc($result))
				{
					
					$CarteCensuree = str_repeat("*", (strlen($row["NumeroCarte"]) -4)) . substr($row["NumeroCarte"], -4);
					array_push($cartesbancaires, Array(
						"ID" => $row["ID"],
						"TypeCarte" => $row["TypeCarte"],
						"NumeroCarte" => $row["NumeroCarte"],
						"NumeroCarteCensuree" => $CarteCensuree,
						"NomAffiche" => $row["NomAffiche"],
						"DatePeremption" => $row["DatePeremption"],
						"Cryptogramme" => $row["Cryptogramme"]
					));
				}
				$result -> free_result();
				$mysqli -> close();
			}
			else
			{
				$cartesbancaires = false;
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
		redirect("./?page=login");
	}
	
?>
<?php include("./template/_top.php"); ?>

<div class="container">
	<h2>Articles</h2>
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
					echo "						<h7 class='card-title'><a href=?page=supprimerDuPanier&ID='". $i['PanierID'] ."'>" . "Supprimer du panier" ."</a></h7>\n";
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
		<div class='col-md-9'></div>
		<div class='col-md-3 px-3'>
			<div class='float-right'>
				<?php
					if($NombreImmediat < 2)
					{
						echo "Total (". $NombreImmediat ." article) : ";
					}
					else
					{
						echo "Total (". $NombreImmediat ." articles) : ";
					}
					echo $TotalImmediat . "€<hr>";
				?>
			</div>
		</div>
	</div>
	
	<hr>
	
	<form action="./?page=validerPanier" method="post">
		<h2>Adresse de livraison</h2>
	<?php
		if($adresses)
		{
			foreach($adresses as $ad)
			{
				echo "		<input type='radio' name='adresse' value='adresse". $ad["ID"] ."'>";
				echo "		<label for='adresse". $ad["ID"] ."'>". $ad["Ligne1"] ."</label><br>";
			}
			
			foreach($adresses as $ad)
			{
				echo "		<div id='divAdresse". $ad["ID"] ."' style='display:none;'>";
				echo "			<div id='divAdresse". $ad["ID"] ."' class='adresse card'>";
				echo "				<div class='card-body' >";
				echo "					" . $ad["Ligne1"] . "<br>";
				
				if($ad["Ligne2"] != "")
					echo "					". $ad["Ligne2"] . "<br>";
				
				echo "					". $ad["Ville"] . "<br>";
				echo "					". $ad["CodePostal"] . "<br>";
				echo "					". $ad["Pays"] . "<br>";
				echo "					". $ad["Telephone"] . "<br>";
				echo "				</div>";
				echo "			</div>";
				echo "		</div>";
			}
		}
		echo "		<a href='./?page=ajouterAdresse'>Ajouter une adresse</a>\n\n";
		
		
		echo "		<hr>";
		echo "		<h2>Moyens de paiements</h2>";
		if($cartesbancaires)
		{
			foreach($cartesbancaires as $cb)
			{
				echo "		<input type='radio' name='cb' value='cb". $cb["ID"] ."'>";
				echo "		<label for='cb". $cb["ID"] ."'>".$cb["TypeCarte"] . ": ". $cb["NumeroCarteCensuree"] ."</label><br>";
			}
			
			foreach($cartesbancaires as $cb)
			{
				echo "		<div id='divCB". $ad["ID"] ."' style='display:none;'>";
				echo "			<div class='cb card'>";
				echo "				<div class='card-body'>";
				echo "					" . $cb["TypeCarte"] . "<br>";
				echo "					" . $cb["NumeroCarteCensuree"] . "<br>";
				echo "					" . $cb["NomAffiche"] . "<br>";
				echo "					" . $cb["DatePeremption"] . "<br>";
				echo "				</div>";
				echo "			</div>";
				echo "		</div>";
			}
		}
		echo "<a href='./?page=ajouterCarteBancaire'>Ajouter une carte bancaire</a>";
		echo "<hr>";
		// echo "<div class='text-right'><input type='submit' value='Commander' name='valider'></div>";
	?>
		<div class='row'>
			<div class='col-md-9'></div>
			<div class='col-md-3 px-3'>
				<div class='float-right'>
					<?php
						if($NombreImmediat < 2)
						{
							echo "Total (". $NombreImmediat ." article) : ";
						}
						else
						{
							echo "Total (". $NombreImmediat ." articles) : ";
						}
						echo $TotalImmediat . "€<br>";
						echo "<div class='text-right'><input type='submit' value='Passer la commander' name='valider'>";
					?>
				</div>
			</div>
		</div>
	</form>
	
	
	<?php
		if($erreur != "")
		{
			echo "<div>Erreur: ". $erreur . "</div>";
		}
			
	?>

</div>
<?php include("./template/_bot.php"); ?>