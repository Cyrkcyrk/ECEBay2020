<?php
	$_DATABASE = Array(
		"host" => "localhost",
		"user" => "root",
		"password" => "",
		"BDD" => "ecebay"
	);
	
	$_INFO = Array (
		"secret" => "MonCodeSecret"
	);
	
	
	$mail =  isset($_POST["mail"])? $_POST["mail"] :"";
	$password =  isset($_POST["password"])? $_POST["password"] :"";
	$connection =  isset($_POST["connection"])? $_POST["connection"] :"";
	$erreur = "";
	
	if($connection != "")
	{
		if($mail == "") {
			$erreur .= "mail incomplet <br>";
		} 
		if($password == "") {
			$erreur .= "password incomplet <br>";
		} 
		
		if($erreur == "")
		{
			$sql = "SELECT * FROM `Utilisateur` WHERE `Mail` = '" . $mail . "';";
			if(!SQLCheck($_DATABASE, $sql, $erreur)) 
			{
				$erreur .= "Ce compte n'existe pas. <br>" . $sql . "<hr>";
			}
			else
			{
				$sql = "SELECT * FROM `Utilisateur` WHERE `Mail` = '" . $mail . "' LIMIT 1;";
				
				$mysqli = new mysqli($_DATABASE["host"],$_DATABASE["user"],$_DATABASE["password"],$_DATABASE["BDD"]);
				mysqli_set_charset($mysqli, "utf8");
				
				if ($mysqli -> connect_errno) {
					$error .= "Failed to connect to MySQL: " . $mysqli -> connect_error;
				}
				if ($result = $mysqli -> query($sql)) {
					if (mysqli_num_rows($result) > 0) {
						
						$row = mysqli_fetch_assoc($result);
						
						// echo $row["ID"]. " - " . $row["Mail"]. " - " . $row["MotDePasse"]. " - " . $row["Nom"]. " - " . $row["Prenom"]. " - " . $row["TypeCompte"]. " - " . $row["FondFavoris"] . "<hr>";
						
						$passwordHash = hash_hmac('md5', $password, $_INFO["secret"]);
						if($row["MotDePasse"] == $passwordHash)
						{
							$token = hash_hmac('md5', $passwordHash , time() . $_INFO["secret"]);
							
							$sql = "INSERT INTO `logintoken`(`Token`, `UserID`) VALUES ('" . $token . "', '" . $row["ID"] . "')";
							if(SQLquery($_DATABASE, $sql, $error))
							{
								setcookie("token", $token, time()+3600);
								header("Location: ./index.php", true, 301);
								exit();
							}
							else
							{
								$erreur .= "Une erreur est survenue dans la création du token de connexion.";
							}
							
						}
						else
						{
							$erreur .= "Mot de passe incorrect";
						}
						$result -> free_result();
						$mysqli -> close();
					} 
					else
					{
						$result -> free_result();
						$mysqli -> close();
						$erreur .= "Cet email n'existe pas.";
					}
				}
				else
				{
					$erreur .= "Une erreur est survenue";
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

<form action="login.php" method="post">
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
					<td colspan="2" style="text-align:center;"><input type="submit" value="Se connecter" name="connection"></td>
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