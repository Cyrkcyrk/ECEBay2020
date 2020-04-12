<?php 
	$_DATABASE = Array(
		"host" => "localhost",
		"user" => "root",
		"password" => "",
		"BDD" => "ecebay"
	);
	
	$_INFO = Array (
		"secret" => "QQQh7tX36r346B66f8g794c6y3x7FPH5ha2w2ViDQ7LjrLwuDb449hXe7FX6UX7vZUeC5mhQ3CRnBrQfKm2rXh66QkwJCNb2SEzG88NiuV5h4Tf9C58T8w7z8rvvj4E3"
	);
		
	function SQLquery($_DATABASE, $sql, $error)
	{
		$mysqli = new mysqli($_DATABASE["host"],$_DATABASE["user"],$_DATABASE["password"],$_DATABASE["BDD"]);
		mysqli_set_charset($mysqli, "utf8");
		
		if ($mysqli -> connect_errno) {
			$error .= "Failed to connect to MySQL: " . $mysqli -> connect_error;
			return false;
		}
		if ($mysqli->query($sql) === TRUE) {
				return Array(true, "");
			} 
			else {
				$error .= "Error: " . $sql . "<br>" . $mysqli -> error . "<br>";
				return Array(false, $error);
			}
		$mysqli -> close();
	}

	function SQLCheck ($_DATABASE, $sql, $error)
	{
		$mysqli = new mysqli($_DATABASE["host"],$_DATABASE["user"],$_DATABASE["password"],$_DATABASE["BDD"]);
		mysqli_set_charset($mysqli, "utf8");
		
		if ($mysqli -> connect_errno) {
			$error .= "Failed to connect to MySQL: " . $mysqli -> connect_error;
			
		}
		if ($result = $mysqli -> query($sql)) {
			if (mysqli_num_rows($result) > 0) {
				$result -> free_result();
				$mysqli -> close();
				return Array (true, "");
			} 
			else
			{
				$result -> free_result();
				$mysqli -> close();
				return Array (false, $error);
			}
		}
	}
	
	function userLogged ($_DATABASE, $token)
	{
		$error = "";
		if($token != "")
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
						"ID" => $row["ID"],
						"Nom" => $row["Nom"],
						"Prenom" => $row["Prenom"],
						"Mail" => $row["Mail"],
						"TypeCompte" => $row["TypeCompte"],
						"StyleFavoris" => $row["StyleFavoris"],
					);
					$result -> free_result();
					$mysqli -> close();
					return Array(true, $user, "");
				}
				else
				{
					$result -> free_result();
					$mysqli -> close();
					return Array(false, "", $error);
				}
			}
			else
			{
				$erreur .= "Une erreur est survenue";
				return Array(false, "", $error);
			}
		}
	}
	
	//https://www.rapidtables.com/web/dev/php-redirect.html
	function redirect($URL)
	{
		header("Location: ". $URL, true, 301);
		exit();
	}
?>