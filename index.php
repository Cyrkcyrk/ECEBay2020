<?php
	require("functions.php");
	$erreur = ""; 
	
	$logged = false;
	$user = null;
	
	
	$token = isset($_COOKIE["token"])? $_COOKIE["token"] :"";
	
	/*if($token != "")
	{
		$sql = "SELECT * FROM `utilisateur` INNER JOIN `logintoken` ON `utilisateur`.`ID`= `logintoken`.`UserID` WHERE `logintoken`.`Token` = '" . $token . "';";
				
		$mysqli = new mysqli($_DATABASE["host"],$_DATABASE["user"],$_DATABASE["password"],$_DATABASE["BDD"]);
		mysqli_set_charset($mysqli, "utf8");
		
		if ($mysqli -> connect_errno) {
			$error .= "Failed to connect to MySQL: " . $mysqli -> connect_error;
		}
		if ($result = $mysqli -> query($sql)) {
			if (mysqli_num_rows($result) > 0) {
				
				$row = mysqli_fetch_assoc($result);
				$logged = true;
				
				$user = Array (
					"Nom" => $row["Nom"],
					"Prenom" => $row["Prenom"]
				);
			} 
			$result -> free_result();
			$mysqli -> close();
		}
		else
		{
			$erreur .= "Une erreur est survenue";
		}
	}
	*/
	list($logged , $user, $erreur) = userLogged($_DATABASE, $token);
?>


<?php
	if($logged)
	{
		echo "Bienvenu, " . $user['Prenom'] . " " . $user['Nom'] . " :D <br>";
		echo "<a href='./logout.php?_=" . $token ."'>Se deconnecter</a><br>";
	}
	else
	{
		echo "<a href='./register'>Creer un compte</a><br>";
		echo "<a href='./login'>se connecter</a>";
	}
?>

