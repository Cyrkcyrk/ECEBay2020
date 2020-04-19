<?php
	$erreur = ""; 
	$mail =  blindage(isset($_POST["mail"])? $_POST["mail"] :"");
	$password =  blindage(isset($_POST["password"])? $_POST["password"] :"");
	$connection =  isset($_POST["connection"])? $_POST["connection"] :"";
	
	if(!$logged)
	{
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
				$sql = "SELECT * FROM `utilisateur` WHERE `Mail` = '" . $mail . "';";
				list($_, $erreur) = SQLCheck($_DATABASE, $sql, $erreur);
				if(!$_) 
				{
					$erreur .= "Ce compte n'existe pas. <br>";
				}
				else
				{
					$sql = "SELECT * FROM `utilisateur` WHERE `Mail` = '" . $mail . "' LIMIT 1;";
					
					$mysqli = new mysqli($_DATABASE["host"],$_DATABASE["user"],$_DATABASE["password"],$_DATABASE["BDD"]);
					mysqli_set_charset($mysqli, "utf8");
					
					if ($mysqli -> connect_errno) {
						$erreur .= "Failed to connect to MySQL: " . $mysqli -> connect_error;
					}
					if ($result = $mysqli -> query($sql)) {
						if (mysqli_num_rows($result) > 0) {
							
							$row = mysqli_fetch_assoc($result);
							
							
							$passwordHash = hash_hmac('md5', $password, $_INFO["secret"]);
							if($row["MotDePasse"] == $passwordHash)
							{
								$token = hash_hmac('md5', $passwordHash , time() . $_INFO["secret"]);
								
								$sql = "INSERT INTO `logintoken`(`Token`, `UserID`, `CreationDate`) VALUES ('" . $token . "', '" . $row["ID"] . "', '" . time() . "')";
								list ($_, $erreur) = SQLquery($_DATABASE, $sql, $erreur);
								if($_)
								{
									setcookie("token", $token, time()+3600);
									redirect('./?page=accueil');
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
	}
	else
	{
		redirect('./?page=accueil');
	}
?>

<?php include("./template/_top.php"); ?>

<style>
	#formulaire{
		float:left;
	}
	#erreur{
		float:left;
	}
</style>

<form action="./?page=login" method="post">
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
<?php include("./template/_bot.php"); ?>