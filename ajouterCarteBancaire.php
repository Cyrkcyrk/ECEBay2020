<?php
	$erreur = ""; 
	$Numero =  isset($_POST["Numero"])? $_POST["Numero"] :"";
	$Nom =  isset($_POST["Nom"])? $_POST["Nom"] :"";
	$Date =  isset($_POST["Date"])? $_POST["Date"] :"";
	$Cryptogramme =  isset($_POST["Cryptogramme"])? $_POST["Cryptogramme"] :"";
	$valider =  isset($_POST["valider"])? $_POST["valider"] :"";
	
	if($logged)
	{
		if($valider != "")
		{
			if($Numero == "") {
				$erreur .= "Numero incomplete <br>";
			} 
			if($Nom == "") {
				$erreur .= "Nom incomplet <br>";
			} 
			if($Date == "") {
				$erreur .= "Date incomplet <br>";
			} 
			if($Cryptogramme == "") {
				$erreur .= "Cryptogramme incomplet <br>";
			} 
			if($erreur == "")
			{
				// $sql = "INSERT INTO `adresse`(`OwnerID`, `Ligne1`, `Ligne2`, `Ville`, `CodePostal`, `Pays`, `Telephone`) VALUES ('" . $user["ID"] . "', '" . $ligne1 . "', '" . $ligne2 . "', '" . $ville . "', '" . $codePostal . "', '" . $pays . "', '" . $telephone . "')";
				
				
				
				$sql = "INSERT INTO `cartebancaire`(`OwnerID`, `TypeCarte`, `NumeroCarte`, `NomAffiche`, `DatePeremption`, `Cryptogramme`) VALUES (" . $user["ID"] . ", " . '"Visa"' . ", '" . $Numero . "', '" . $Nom . "', '" . $Date . "', '" . $Cryptogramme . "');";
				list ($_, $erreur) = SQLquery($_DATABASE, $sql, $erreur);
				
				if($_)
					redirect('./?page=account');
			}
		}
	}
	else
	{
		redirect('./?page=login');
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

<form action="./?page=ajouterCarteBancaire" method="post">
	<div id="identification">

			<div class="form-row">
				<div class='form-group col-md-10'>
					<?php echo "<input class='form-control' type='number' placeholder='N° de la carte' name='Numero' value ='" . $Numero ."'>";?>	
				</div>
				<div class='form-group col-md-10'>
					<?php echo "<input class='form-control' type='text' placeholder='Nom affiché sur la carte' name='Nom' value ='" . $Nom ."'>";?>
				</div>
				<div id="erreur" class="Fl-R">
				<?php if($erreur != "")
					{
						echo $erreur;
					}
				?>
			</div>
			</div>

			<div class="form-row">
				<div class='form-group col-md-2'>
					<?php echo "<input class='form-control' type='text' placeholder='Date d&apos;expiration' name='Date' value ='" . $Date ."'>";?>			
				</div>
				<div class='form-group col-md-2'>
					<?php echo "<input class='form-control' type='number' placeholder='Cryptogramme' name='Cryptogramme' value ='" . $Cryptogramme ."'>";?>		
				</div>
			</div>
			<div class="form-row">
				
				<div class="form-check form-check-inline">
					<label class="form-check-label">Type de carte: &ensp;</label>
					<input class="form-check-input" type="radio" name="typeCarte" id="Visa" value="Visa">
					<label class="form-check-label" for="Visa">Visa</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="typeCarte" id="Mastercard" value="Mastercard">
					<label class="form-check-label" for="Mastercard">Mastercard</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="typeCarte" id="American_Express" value="American Express">
					<label class="form-check-label" for="American-Express">American Express</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="typeCarte" id="Paypal" value="Paypal">
					<label class="form-check-label" for="Paypal">Paypal</label>
				</div>
			</div>
			<br>

			<button type="submit" class="btn btn-primary" value="Valider" name="valider">Valider</button>	
		
	</div>
	
</form>
<?php include("./template/_bot.php"); ?>