<?php
	$erreur = ""; 

	if($logged && $user["TypeCompte"] == 3)
	{
		$sql = "SELECT * FROM `utilisateur` WHERE `TypeCompte` >= 2 ORDER BY `TypeCompte` DESC;";
		
		$vendeurs = Array();
		$mysqli = new mysqli($_DATABASE["host"],$_DATABASE["user"],$_DATABASE["password"],$_DATABASE["BDD"]);
		mysqli_set_charset($mysqli, "utf8");
		
		if ($mysqli -> connect_errno) {
			$erreur .= "Failed to connect to MySQL: " . $mysqli -> connect_error;
		}
		if ($result = $mysqli -> query($sql)) {
			if (mysqli_num_rows($result) > 0) {
				
				while ($row = mysqli_fetch_assoc($result))
				{
					$_typeCompte = "";
					switch ($row["TypeCompte"]) {
						case 2:
							$_typeCompte = "Vendeur";
							break;
						case 3:
							$_typeCompte = "Administrateur";
							break;
						default:
							$_typeCompte = "Inconnus - " . $row["TypeCompte"];
							break;
					}
					
					array_push($vendeurs, Array(
						"ID" => $row["ID"],
						"Nom" => $row["Nom"],
						"Prenom" => $row["Prenom"],
						"Mail" => $row["Mail"],
						"TypeCompte" => $row["TypeCompte"],
						"TypeCompteNom" => $_typeCompte
					));
				}
				$result -> free_result();
				$mysqli -> close();
			}
			else
			{
				$vendeurs = false;
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

<?php 
include("./template/_top.php");
	
	foreach($vendeurs as $vd)
	{
		echo "	<div class='adresse card'>";
		echo "		<div class='card-body'>";
		echo "			". $vd["Mail"] 		. "<br>";
		echo "			". $vd["Prenom"] . " " . $vd["Nom"] . "<br>";
		echo "			". $vd["TypeCompteNom"] 	. "<br>";
		echo "			<a href='?page=changerStatusUtilisateur&ID=". $vd["ID"] ."'>Changer status utilisateur</a>";
		echo "		</div>";
		echo "	</div>\n";
	}
	echo "<a href='?page=changerStatusUtilisateur'>Ajouter un utilisateur</a>";
	
include("./template/_bot.php"); ?>