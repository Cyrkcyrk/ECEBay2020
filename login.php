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
									$_SESSION["token"] = $token;
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
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" type="text/css" rel="stylesheet">
<style>
	#formulaire{
		float:left;
	}
	#erreur{
		float:left;
	}
	
	.showPassword{
		cursor: pointer;
		position: relative;
		top : 4px;
		right : 30px;
	}
	
</style>

<script>
	
	var hidden = true;
	
	function hideShowPassword()
	{
		document.getElementById("ShowPasswordButton").addEventListener('mousedown', e => {
			document.getElementById("password").type = "text";
			document.getElementById("eyeOpenPassword").style = "display:none;";
			document.getElementById("eyeClosePassword").style = "display:1;";
		});
		
		
		document.getElementById("ShowPasswordButton").addEventListener('mouseup', e => {
			document.getElementById("password").type = "password";
			document.getElementById("eyeOpenPassword").style = "display:1;";
			document.getElementById("eyeClosePassword").style = "display:none;";
		});
	}
	
</script>





<form action="./?page=login" method="post">
	<div id="identification">
		<div id="formulaire" class="form-row">
			<div class='form-group col-md-12'>
				<input class='form-control col-md-11' type='text' placeholder="Adresse email" name='mail' value ='<?php echo $mail; ?>'>
			</div>
			<div class='form-group col-md-12'>
				<table class="w-100">
					<tr>
						<td><input class='form-control oeilB' id='password' placeholder="Mot de passe" type='password' name='password'> </td>
						<td><span class='showPassword oeilC' id="ShowPasswordButton" onClick="hideShowPassword();">
							<i id="eyeOpenPassword"  class='fa fa-eye' aria-hidden='true'></i>
							<i id="eyeClosePassword" class='fa fa-eye-slash' aria-hidden='true' style="display:none"></i>
						</span></td>
					</tr>
				</table>
			</div>
			
			<button type="submit" class="btn btn-primary" value="Se connecter" name="connection">Valider</button>
		</div>
	<!--<div id="formulaire" >
			<table>
				<tr>
					<td>Adresse email:</td>
					<td><input type='text' name='mail' value ='<?php echo $mail; ?>'></td>
				</tr>
				<tr>
					<td>Mot de passe:</td>
					
					<td><input id='password' type='password' name='password'>
					<span class='showPassword' id="ShowPasswordButton" onClick="hideShowPassword();">
						<i id="eyeOpenPassword"  class='fa fa-eye' aria-hidden='true'></i>
						<i id="eyeClosePassword" class='fa fa-eye-slash' aria-hidden='true' style="display:none"></i>
					</span>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:center;"><input type="submit" value="Se connecter" name="connection"></td>
				<tr>
			</table>
			
		</div>-->
		
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