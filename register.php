<?php
	$_DATABASE = Array(
		"host" => "localhost",
		"user" => "root",
		"password" => "",
		"BDD" => "ecebay"
	);
	
	
	$mail =  isset($_POST["mail"])? $_POST["mail"] :"";
	$password =  isset($_POST["password"])? $_POST["password"] :"";
	$nom =  isset($_POST["nom"])? $_POST["nom"] :"";
	$prenom =  isset($_POST["prenom"])? $_POST["prenom"] :"";
	$type =  isset($_POST["type"])? $_POST["type"] :"";
	$inscription =  isset($_POST["inscription"])? $_POST["inscription"] :"";
	$erreur = "";
	
	if($inscription != "")
	{
		if($mail == "") {
			$erreur .= "mail incomplet <br>";
		} 
		if($password == "") {
			$erreur .= "password incomplet <br>";
		} 
		if($nom == "") {
			$erreur .= "nom incomplet <br>";
		} 
		if($prenom == "") {
			$erreur .= "prenom incomplet <br>";
		} 
		if($type == "") {
			$erreur .= "type incomplet <br>";
		} 
		
		if($erreur == "")
		{
			if ($type = "vendeur") {
				$type = 1;
			}
			else {
				$type = 0;
			}
			
			$password = password_hash ($password , PASSWORD_BCRYPT);
			
			
			/*$sql = "SELECT * FROM `Utilisateur` WHERE `Mail` = '" . $mail . "';";
			$mysqli_temp = new mysqli($_DATABASE["host"],$_DATABASE["user"],$_DATABASE["password"],$_DATABASE["BDD"]);
			mysqli_set_charset($mysqli_temp, "utf8");
			
			if ($mysqli_temp -> connect_errno) {
				$erreur .= "Failed to connect to MySQL: " . $mysqli_temp -> connect_error;
				
			}
			if ($result = $mysqli_temp -> query($sql)) {
				if (mysqli_num_rows($result) > 0) {
					$erreur .= "Cet email est deja enregistré.";
				} 
				else
				{
					$mysqli_temp -> close();
					$sql = "INSERT INTO `Utilisateur`(`Mail`, `MotDePasse`, `Nom`, `Prenom`, `TypeCompte`) VALUES ('" . $mail . "', '" . $password . "', '" . $nom . "', '" . $prenom . "', " . $type . ")";
					if(SQLquery($sql, $erreur))
					{
						setcookie (string $name [, string $value = "")
						
						//https://www.rapidtables.com/web/dev/php-redirect.html
						header("Location: ./index.php", true, 301);
						exit();
					}
				}
			}*/
			
			$sql = "SELECT * FROM `Utilisateur` WHERE `Mail` = '" . $mail . "';";
			if(SQLCheck($_DATABASE, $sql, $erreur)) 
			{
				$erreur .= "Cet email est deja enregistré.";
			}
			else
			{
				$sql = "INSERT INTO `Utilisateur`(`Mail`, `MotDePasse`, `Nom`, `Prenom`, `TypeCompte`) VALUES ('" . $mail . "', '" . $password . "', '" . $nom . "', '" . $prenom . "', " . $type . ")";
				if(SQLquery($_DATABASE, $sql, $erreur))
				{
					//https://www.rapidtables.com/web/dev/php-redirect.html
					header("Location: ./index.php", true, 301);
					exit();
				}
			}
		}
		
	}
	
	
	
	function SQLquery($_DATABASE, $sql, $error)
	{
		$mysqli = new mysqli($_DATABASE["host"],$_DATABASE["user"],$_DATABASE["password"],$_DATABASE["BDD"]);
		mysqli_set_charset($mysqli, "utf8");
		
		if ($mysqli -> connect_errno) {
			$error .= "Failed to connect to MySQL: " . $mysqli -> connect_error;
			return false;
		}
		if ($mysqli->query($sql) === TRUE) {
				return true;
			} 
			else {
				$error .= "Error: " . $sql . "<br>" . $mysqli -> error . "<br>";
				return false;
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
				return true;
			} 
			else
			{
				$result -> free_result();
				$mysqli -> close();
				return false;
			}
		}
	}
?>

<style>
	#formulaire{
		float:left;
	}
	#erreur{
		float:left;
	}
</style>

<form action="register.php" method="post">
	<div id="identification">
		<div id="formulaire">
			<table>
				<tr>
					<td>Adresse email:</td>
					<td> <?php echo "<input type='text' name='mail' value ='" . $mail ."'>";?></td>
				</tr>
				<tr>
					<td>Mot de passe:</td>
					
					<td><?php echo "<input type='password' name='password'>";?></td>
				</tr>
				<tr>
					<td>Nom:</td>
					<td><?php echo "<input type='text' name='nom' value ='" . $nom ."'>";?></td>
				</tr>
				<tr>
					<td>Prenom:</td>
					<td><?php echo "<input type='text' name='prenom' value ='" . $prenom ."'>";?></td>
				</tr>
				
				<tr>
					<td> Type de compte: </td>
					<td>
						<input type="radio" name="type" value="vendeur"> 
						<label for="vendeur">Vendeur</label><br>
						
						<input type="radio" name="type" value="acheteur"> 
						<label for="acheteur">Acheteur</label><br>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:center;"><input type="submit" value="S'inscrire" name="inscription"></td>
				<tr>
			</table>
		</div>
		<div id="erreur">
			<?php if($erreur != "")
				{
					echo $erreur;
				}
			?>
		</div>
	</div>
</form>