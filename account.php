<?php
	$erreur = ""; 
	$logged = false;
	$user = null;
	
	$token = isset($_COOKIE["token"])? $_COOKIE["token"] :"";
	list($logged , $user, $erreur) = userLogged($_DATABASE, $token);
	
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
		
		
		
		
		
		
		
		
		
		
		
		echo "Bienvenue, " . $user['Prenom'] . " " . $user['Nom'] . " :D <br>";
		echo "<a href='./?page=logout&_=" . $token ."'>Se deconnecter</a><br>";
		
		echo "<h2> Vos adresse(s)</h2><hr>";
		if(!$adresses)
		{
			echo "Vous n'avez pas encore renseigné d'adresses <br>";
		}
		else
		{
			foreach($adresses as $ad)
			{
				$string_adresse = '';
				$string_adresse .= $ad["Ligne1"] . "<br>";
				
				if($ad["Ligne2"] != "")
					$string_adresse .= $ad["Ligne2"] . "<br>";
				
				$string_adresse .= $ad["Ville"] . "<br>";
				$string_adresse .= $ad["CodePostal"] . "<br>";
				$string_adresse .= $ad["Pays"] . "<br>";
				$string_adresse .= $ad["Telephone"] . "<hr>";
				
				echo $string_adresse;
			}
		}
		echo "<a href='./?page=ajouterAdresse'>Ajouter une adresse</a><br>";
	}
	else
	{
		redirect('./login');
	}
?>

