<?php include("./template/_top.php"); ?>
<?php
	$erreur = ""; 
	
	if($logged)
	{
		$adresses = null;
		
		$sql = "SELECT * FROM `adresse` WHERE `OwnerID` = '" . $user["ID"] . "';";
		$mysqli = new mysqli($_DATABASE["host"],$_DATABASE["user"],$_DATABASE["password"],$_DATABASE["BDD"]);
		mysqli_set_charset($mysqli, "utf8");
		
		if ($mysqli -> connect_errno) {
			$erreur .= "Failed to connect to MySQL: " . $mysqli -> connect_error;
		}
		if ($result = $mysqli -> query($sql)) {
			if (mysqli_num_rows($result) > 0) {
				
				$adresses = Array();
				$_compteur = 0;
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
		
		 
		$cartesbancaires = null;
		$sql = "SELECT * FROM `cartebancaire` WHERE `OwnerID` = '" . $user["ID"] . "';";
		$mysqli = new mysqli($_DATABASE["host"],$_DATABASE["user"],$_DATABASE["password"],$_DATABASE["BDD"]);
		mysqli_set_charset($mysqli, "utf8");
		
		if ($mysqli -> connect_errno) {
			$erreur .= "Failed to connect to MySQL: " . $mysqli -> connect_error;
		}
		if ($result = $mysqli -> query($sql)) {
			if (mysqli_num_rows($result) > 0) {
				
				$cartesbancaires = Array();
				$_compteur = 0;
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
		
		
		
		

		
		echo "Bienvenue, " . $user['Prenom'] . " " . $user['Nom'] . " :D <br>";
		echo "<a href='./?page=logout&_=" . $token ."'>Se deconnecter</a><br>";
		
		echo "<div id='adresses'>";
		echo "<h2> Vos adresse(s)</h2>";
		if(!$adresses)
		{
			echo "Vous n'avez pas encore renseigné d'adresses <br>";
		}
		else
		{
			foreach($adresses as $ad)
			{
				$string_adresse = '';
				$string_adresse .= "<div class='adresse'>";
				$string_adresse .= $ad["Ligne1"] . "<br>";
				
				if($ad["Ligne2"] != "")
					$string_adresse .= $ad["Ligne2"] . "<br>";
				
				$string_adresse .= $ad["Ville"] . "<br>";
				$string_adresse .= $ad["CodePostal"] . "<br>";
				$string_adresse .= $ad["Pays"] . "<br>";
				$string_adresse .= $ad["Telephone"] . "<br>";
				$string_adresse .= "<a href='?page=supprimerAdresse&ID=" . $ad["ID"] . "'>Supprimer cette adresse </a></div>\n";
				
				echo $string_adresse;
			}
		}
		echo "<a href='./?page=ajouterAdresse'>Ajouter une adresse</a><hr>";
		echo "</div>";
		
		
		
		
		
		echo "<br><div id='cartesbancaires'>";
		echo "<h2> Vos moyens de paiements</h2>";
		if(!$cartesbancaires)
		{
			echo "Vous n'avez pas encore de moyen de paiement <br>";
		}
		else
		{
			foreach($cartesbancaires as $cb)
			{
				$string_cb = '';
				$string_cb .= "<div class='cb'>";
				$string_cb .= $cb["NumeroCarteCensuree"] . "<br>";
				
				$string_cb .= $cb["NomAffiche"] . "<br>";
				$string_cb .= $cb["DatePeremption"] . "<br>";
				// $string_cb .= "***" . "<br>";
				$string_cb .= "<a href='?page=supprimerCarteBancaire&ID=" . $cb["ID"] . "'>Supprimer cette carte bancaire </a></div>\n";
				
				echo $string_cb;
			}
			
		}
		echo "<a href='./?page=ajouterCarteBancaire'>Ajouter une carte bancaire</a><hr>";
		echo "</div>";
	}
	else
	{
		redirect('./?page=login');
	}
?>

<?php include("./template/_bot.php"); ?>