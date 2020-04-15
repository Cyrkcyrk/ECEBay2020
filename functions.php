<?php 

	//https://stackoverflow.com/questions/13640109/how-to-prevent-browser-cache-for-php-site
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");

	define ("SECRET", "QQQh7tX36r346B66f8g794c6y3x7FPH5ha2w2ViDQ7LjrLwuDb449hXe7FX6UX7vZUeC5mhQ3CRnBrQfKm2rXh66QkwJCNb2SEzG88NiuV5h4Tf9C58T8w7z8rvvj4E3");
	
	$imported = true;
	$_DATABASE = Array(
		"host" => "51.77.145.11",
		"user" => "ecebay",
		"password" => "SV8qIjR9TvhNIFve",
		"BDD" => "ecebay"
	);
	
	$_INFO = Array (
		"secret" => SECRET
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
			$tokenExpiration = time()-3600;
			
			$sql = "SELECT * FROM `utilisateur` INNER JOIN `logintoken` ON `utilisateur`.`ID`= `logintoken`.`UserID` WHERE `logintoken`.`Token` = '" . $token . "' AND `logintoken`.`CreationDate` > '" . $tokenExpiration . "';";
					
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
	
	function tokenGenerator () {
		function RandomString()
		{
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$randstring = '';
			for ($i = 0; $i < 10; $i++) {
				$randstring = $characters[rand(0, strlen($characters)-1)];
			}
			return $randstring;
		}
		return hash_hmac('md5', RandomString() , time() . SECRET);
	}
?>