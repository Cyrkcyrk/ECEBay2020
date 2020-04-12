<?php
	require("functions.php");

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
			list($_, $erreur) = SQLCheck($_DATABASE, $sql, $erreur);
			if(!$_) 
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
							list ($_, $erreur) = SQLquery($_DATABASE, $sql, $erreur);
							if($_)
							{
								setcookie("token", $token, time()+3600);
								redirect('./index');
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