<?php
	$erreur = ""; 
	
	$mail =  isset($_POST["mail"])? $_POST["mail"] :"";
	$password =  isset($_POST["password"])? $_POST["password"] :"";
	$nom =  isset($_POST["nom"])? $_POST["nom"] :"";
	$prenom =  isset($_POST["prenom"])? $_POST["prenom"] :"";
	$type =  isset($_POST["type"])? $_POST["type"] :"";
	$inscription =  isset($_POST["inscription"])? $_POST["inscription"] :"";
	
	
	if(!$logged)
	{
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
				
				// $password = password_hash ($password , PASSWORD_BCRYPT);
				$passwordHash = hash_hmac('md5', $password, $_INFO["secret"]);
				
				
				$sql = "SELECT * FROM `Utilisateur` WHERE `Mail` = '" . $mail . "';";
				list ($_, $erreur) = SQLCheck($_DATABASE, $sql, $erreur);
				if($_) 
				{
					$erreur .= "Cet email est deja enregistré.";
				}
				else
				{
					$sql = "INSERT INTO `Utilisateur`(`Mail`, `MotDePasse`, `Nom`, `Prenom`, `TypeCompte`) VALUES ('" . $mail . "', '" . $passwordHash . "', '" . $nom . "', '" . $prenom . "', " . $type . ")";
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

<style>
	#formulaire{
		float:left;
	}
	#erreur{
		float:left;
	}
</style>

<form action="./?page=register" method="post">
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