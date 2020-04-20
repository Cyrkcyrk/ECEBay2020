<?php
	$erreur = ""; 
	
	$mail =  blindage(isset($_POST["mail"])? $_POST["mail"] :"");
	$password =  blindage(isset($_POST["password"])? $_POST["password"] :"");
	$passwordCheck =  blindage(isset($_POST["passwordCheck"])? $_POST["passwordCheck"] :"");
	$nom =  blindage(isset($_POST["nom"])? $_POST["nom"] :"");
	$prenom =  blindage(isset($_POST["prenom"])? $_POST["prenom"] :"");
	$type = blindage(isset($_POST["type"])? $_POST["type"] :"");
	$inscription =  isset($_POST["inscription"])? $_POST["inscription"] :"";
	
	
	if(!$logged)
	{
		if($inscription != "")
		{
			if($mail == "") {
				$erreur .= "mail incomplet <br>";
			}
			else if (!preg_match("/[a-zA-Z.0-9]+@[a-zA-Z.0-9]+\.[a-zA-Z]+/", $mail)) {
				$erreur .= "mauvais format d'entree <br>";
			} 
			if($password == "") {
				$erreur .= "password incomplet <br>";
			}
			if($passwordCheck == "") {
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
			if($password != $passwordCheck)
			{
				$erreur .= "password: " . $password . " - check: " . $passwordCheck . "<br>";
				$erreur .= "Les mots de passe ne correspondent pas.<br>";
			}
			
			
			
			if($erreur == "")
			{
				if ($type == "vendeur") {
					$type = 2;
				}
				else {
					$type = 1;
				}
				
				// $password = password_hash ($password , PASSWORD_BCRYPT);
				$passwordHash = hash_hmac('md5', $password, $_INFO["secret"]);
				
				
				$sql = "SELECT * FROM `utilisateur` WHERE `Mail` = '" . $mail . "';";
				list ($_, $erreur) = SQLCheck($_DATABASE, $sql, $erreur);
				if($_) 
				{
					$erreur .= "Cet email est deja enregistré.";
				}
				else
				{
					$sql = "INSERT INTO `utilisateur`(`Mail`, `MotDePasse`, `Nom`, `Prenom`, `TypeCompte`) VALUES ('" . $mail . "', '" . $passwordHash . "', '" . $nom . "', '" . $prenom . "', " . $type . ")";
					list ($_, $erreur) = SQLquery($_DATABASE, $sql, $erreur);
					if($_)
					{
						redirect('./?page=accueil');
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
	
	function hideShowPassword() {
		document.getElementById("ShowPasswordButton").addEventListener('mousedown', e => {
			document.getElementById("password").type = "text";
			document.getElementById("passwordCheck").type = "text";
			document.getElementById("eyeOpenPassword").style = "display:none;";
			document.getElementById("eyeClosePassword").style = "display:1;";
		});
		
		
		document.getElementById("ShowPasswordButton").addEventListener('mouseup', e => {
			document.getElementById("password").type = "password";
			document.getElementById("passwordCheck").type = "password";
			document.getElementById("eyeOpenPassword").style = "display:1;";
			document.getElementById("eyeClosePassword").style = "display:none;";
		});
	}
	
	function updateMDPpattern()
	{
		if(document.getElementById("passwordCheck").value !=  document.getElementById("password").value)
		{
			document.getElementById("incorrectpassword").innerHTML = "Les mots de passe ne sont pas identiques.";
			return false;
		}
		else
		{
			document.getElementById("incorrectpassword").innerHTML = "";
			return true;
		}
	}
	
	function isValid()
	{
		if(	updateMDPpattern() 
			&& document.getElementById("mail").value != ""
			&& document.getElementById("password").value != ""
			&& document.getElementById("passwordCheck").value != ""
			&& document.getElementById("nom").value != ""
			&& document.getElementById("prenom").value != ""
			&& (document.getElementById("radioVendeur").checked
				|| document.getElementById("radioAcheteur").checked )
			&& document.getElementById("conditions").checked)
		{
			document.getElementById("valider").disabled = false;
		}
		else
		{
			document.getElementById("valider").disabled = true;
		}
	}
	
	
</script>




<form action="./?page=register" method="post">
	<div id="identification">
		<div id="formulaire" class="form-row">
			<div class="form-row">
			<div class='form-group col-md-11'>
				<input class='form-control' type='text' placeholder='Adresse email' required id="mail" name='mail' value="<?php echo $mail;?>" onChange="isValid();" oninput="isValid();">
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
			<div class='form-group col-md-11'>
				<input class='form-control' type='password' placeholder='Vérification du mot de passe' id="passwordCheck" required name='passwordCheck' onChange="isValid();" oninput="isValid();">		
			</div>
			<div class='form-group col-md-11'>
				<input class='form-control' type='text' placeholder='Nom' required name='nom' id="nom" value="<?php echo $nom;?>" onChange="isValid();" oninput="isValid();">
			</div>
			<div class='form-group col-md-11'>
				<input class='form-control' type='text' placeholder='Prenom' required name='prenom' id="prenom" value="<?php echo $prenom;?>" onChange="isValid();" oninput="isValid();">
			</div>
			
			<div class="form-check form-check-inline col-md-11">
				<label class="form-check-label">Type de compte: &ensp;</label>
			</div>
			<div class="form-check form-check-inline col-md-11">
				<input class="form-check-input" type="radio" id="radioVendeur" value="vendeur" <?php if ($type == "vendeur") echo "checked"; ?> onChange="isValid();" oninput="isValid();">
				<label class="form-check-label" for="vendeur">Vendeur</label>
			</div>
			<div class="form-check form-check-inline col-md-11">
				<input class="form-check-input" type="radio" id="radioAcheteur" value="acheteur" <?php if ($type == "acheteur") echo "checked"; ?> onChange="isValid();" oninput="isValid();">
				<label class="form-check-label" for="acheteur">Acheteur</label>
			</div>
			<div class='form-group col-md-11'>
				<input type="checkbox" required value="conditions" id="conditions" name="conditions" onChange="isValid();" oninput="isValid();" > J'ai lu et j'accepte les <a href="#">conditions générales d'utilisation</a>
			</div>
			
			<div class='form-group col-md-11'>
				<button type="submit" class="btn btn-primary" value="S'inscrire" disabled id="valider" name="inscription">Valider</button>
			</div>
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