<?php
	$itemID = isset($_GET["item"])? $_GET["item"] : "";
	$erreur = "";
	
	$item = "";
	$images = "";
	
	
	if($itemID != "")
	{
		// $sql = "SELECT i.*, o.`Nom` AS 'OwnerNom', o.`Prenom` AS 'OwnerPrenom' FROM `item` AS i JOIN `utilisateur` AS o ON o.`ID` = i.`OwnerID` WHERE i.`ID` = ". $itemID .";";
		$sql = "SELECT * FROM ( SELECT i.*, CASE WHEN EXISTS (SELECT `Prix` FROM `encheres` AS e WHERE e.`ItemID` = i.`ID` ORDER BY `Prix` DESC LIMIT 1,1) THEN (SELECT `Prix` FROM `encheres` AS e WHERE e.`ItemID` = i.`ID` ORDER BY `Prix` DESC LIMIT 1,1) + 1 WHEN EXISTS (SELECT `Prix` FROM `encheres` AS e WHERE e.`ItemID` = i.`ID` ORDER BY `Prix` DESC LIMIT 1) THEN  i.`PrixDepart` + 1 ELSE i.`PrixDepart` END AS 'PrixEnchereMax', o.`Nom` AS 'OwnerNom', o.`Prenom` AS 'OwnerPrenom' FROM `item` AS i LEFT JOIN `encheres` AS e ON e.`ItemID` = i.`ID` JOIN `utilisateur` AS o ON o.`ID` = i.`OwnerID` WHERE i.`ID` = ". $itemID ." AND i.`EtatVente` = 1 ) AS R ORDER BY R.`PrixEnchereMax` DESC LIMIT 1 ;";
		
		$mysqli = new mysqli($_DATABASE["host"],$_DATABASE["user"],$_DATABASE["password"],$_DATABASE["BDD"]);
		mysqli_set_charset($mysqli, "utf8");

		if ($mysqli -> connect_errno) {
			$erreur .= "Failed to connect to MySQL: " . $mysqli -> connect_error;
		}
		if ($result = $mysqli -> query($sql)) {
			if (mysqli_num_rows($result) > 0) {
				
				$row = mysqli_fetch_assoc($result);
				
				if($row["Categorie"] == "ferraille") $_categorie = "Ferraille ou trésor";
				else if($row["Categorie"] == "musee") $_categorie = "Bon pour le musée";
				else if($row["Categorie"] == "VIP") $_categorie = "Accessoire VIP";
				
				$item =  Array(
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
					"OwnerID" => $row["OwnerID"],
					"OwnerNom" => $row["OwnerNom"],
					"OwnerPrenom" => $row["OwnerPrenom"],
					"PrixEnchereMax" => $row["PrixEnchereMax"]
				);
				
				$result -> free_result();
				$mysqli -> close();
			}
			else
			{
				$item = false;
				$erreur .= "Cet item n'existe pas <br>";
				$result -> free_result();
				$mysqli -> close();
			}
		}
		else
		{
			$erreur .= "Une erreur est survenue";
		}
		
		
		
		
		$sql = "SELECT * FROM `medias` WHERE `ItemID` = ". $itemID ." ORDER BY `Ordre` ASC;";
		$mysqli = new mysqli($_DATABASE["host"],$_DATABASE["user"],$_DATABASE["password"],$_DATABASE["BDD"]);
		mysqli_set_charset($mysqli, "utf8");

		if ($mysqli -> connect_errno) {
			$erreur .= "Failed to connect to MySQL: " . $mysqli -> connect_error;
		}
		if ($result = $mysqli -> query($sql)) {
			if (mysqli_num_rows($result) > 0) {
				
				$images = Array();
				while ($row = mysqli_fetch_assoc($result))
				{
					array_push($images, Array(
						"ID" => $row["ID"],
						"Lien" => $row["Lien"],
						"type" => $row["type"],
						"ordre" => $row["Ordre"],
					));
				}
				$result -> free_result();
				$mysqli -> close();
			}
			else
			{
				$images = false;
				$result -> free_result();
				$mysqli -> close();
			}
		}
		else
		{
			$erreur .= "Une erreur est survenue";
		}
	}
?>



<?php include("./template/_top.php"); ?>






<div class="row">
	<div class="col-lg-4 col-md-5 mb-4">
		<div class="card h-100">
			<div class="preview-pic tab-content">
				<?php
					if($images)
					{
						forEach($images as $i)
						{
							if($i["ordre"] == 0)
								echo "				<div class='tab-pane active' id='pic-". $i["ordre"] ."'><img src='".$i["Lien"]."' /></div>";
							else
								echo "				<div class='tab-pane' id='pic-". $i["ordre"] ."'><img src='".$i["Lien"]."' /></div>";
							
						}
					}
				?>
			</div>
						<ul class="preview-thumbnail nav nav-tabs">
				<?php
					if($images)
					{
						forEach($images as $i)
						{
							if($i["ordre"] == 0)
								echo "				<li class='active'><a data-target='#pic-". $i["ordre"] ."' data-toggle='tab'><img src='". $i["Lien"] ."' /></a></li>\n";
							else
								echo "				<li><a data-target='#pic-". $i["ordre"] ."' data-toggle='tab'><img src='". $i["Lien"] ."' /></a></li>\n";
						}
					}
				?>
			</ul>

		</div>
	</div>
	<div class="col-lg-5 col-md-5 mb-5">
		<div class="card-body">
			
			<?php
				if($item)
				{
					echo "			<h4 class='card-title'>". $item["Nom"] ."</h4>\n";
					echo "			<p>Vendu par <a href=./?page=user&user='". $item["OwnerID"] ."'>". $item["OwnerPrenom"] ." ". $item["OwnerNom"] ."</a></p>\n";
					echo "			<p class='card-text'>". $item["DescriptionQ"] ."</p>\n";
					echo "			<p class='card-text'>". $item["DescriptionD"] ."</p>\n";
				}
			?>
		</div>
	</div>
	<div class="col-lg-3 col-md-2 mb-3">
		<div class="card-body float-right d-none d-sm-block">
			<?php
				
				function venteDirecte($item) {
					echo "			<p>Achetez le maintenant pour " . $item["PrixVenteDirect"] . "€ </p>\n";
					echo "			<a href='./?page=ajouterAuPanier&item=". $item["ID"] ."'>Ajouter au panier</a><br>\n";
				}
				
				if($item["EtatVente"] != 1)
				{
					echo "			<p>Cet objet n'est plus disponible à la vente</p>\n";
				}
				else
				{
					if($item["ModeVente"] == 0)
					{
						venteDirecte($item);
					}
					
					
					else if($item["ModeVente"] == 1)
					{
						echo "			<p>Encherissez pour " . $item ["PrixEnchereMax"] . "€ </p>\n";
						echo "			<form action='./?page=ajouterEnchere' method='post'>\n";
						echo "				<input type='number' name='Enchere' step='0.01' min='". $item ["PrixEnchereMax"] ."'>\n";
						echo "				<input type='hidden' name='ID' value='". $item ["ID"] ."'>\n";
						echo "				<input type='submit' value='Soumettre' name='valider'>\n";
						echo "			</form>\n";
						echo "			<hr>";
						if($item["VenteDirect"])
						{
							venteDirecte($item);
						}
					}
					
					else if($item["ModeVente"] == 2)
					{
						echo "			<p>Faite une offre dans les " . $item["PrixDepart"] . "€ </p>\n";
						
						echo "			<form action='./?page=nouvelleOffre' method='post' >\n";
						echo "				<div id='formulaire' class='form-row'>\n";
						echo "					<div class='form-group col-md-12'>\n";
						echo "						<input class='form-control' type='number' step='0.01' placeholder='Prix' name='prix'>\n";
						echo "					</div>\n";
						echo "					<div class='form-group col-md-12'>\n";
						echo "						<textarea placeholder='Message' class='form-control' name='message'></textarea>\n";
						echo "					</div>\n";
						echo "					<input type='hidden' name='itemID' value='". $item ["ID"] ."'>\n";
						echo "					<button type='submit' class='btn btn-primary' value='Envoyer l'offre' name='valider'>Valider</button>	\n";
						echo "				</div>\n";
						echo "			</form>\n";
						echo "			<hr>";
						
						if($item["VenteDirect"])
						{
							venteDirecte($item);
						}
					}
					
				}
			?>
		</div>
		<div class="card-body d-sm-none">
			<?php
				
				if($item["EtatVente"] != 1)
				{
					echo "			<p>Cet objet n'est plus disponible à la vente</p>\n";
				}
				else
				{
					if($item["ModeVente"] == 0)
					{
						venteDirecte($item);
					}
					
					
					else if($item["ModeVente"] == 1)
					{
						echo "			<p>Encherissez pour " . $item ["PrixEnchereMax"] . "€ </p>\n";
						echo "			<form action='./?page=ajouterEnchere' method='post'>\n";
						echo "				<input type='number' name='Enchere' step='0.01' min='". $item ["PrixEnchereMax"] ."'>\n";
						echo "				<input type='hidden' name='ID' value='". $item ["ID"] ."'>\n";
						echo "				<input type='submit' value='Soumettre' name='valider'>\n";
						echo "			</form>\n";
						if($item["VenteDirect"])
						{
							venteDirecte($item);
						}
					}
					
					else if($item["ModeVente"] == 2)
					{
						echo "			<p>Faite une offre dans les " . $item["PrixDepart"] . "€ </p>\n";
						if($item["VenteDirect"])
						{
							venteDirecte($item);
						}
					}
					
				}
			?>
		</div>
	</div>
</div>
<?php include("./template/_bot.php"); ?>