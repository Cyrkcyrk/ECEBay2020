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
		<div id="formulaire">
			<table>
				<tr>
					<td>Numéro de la carte:</td>
					<td> <?php echo "<input type='number' name='Numero' value ='" . $Numero ."'>";?></td>
				</tr>
				<tr>
					<td>Nom affiché sur la carte:</td>
					<td> <?php echo "<input type='text' name='Nom' value ='" . $Nom ."'>";?></td>
				</tr>
				<tr>
					<td>Date de peremption:</td>
					<td><?php echo "<input type='text' name='Date' value ='" . $Date ."'>";?></td>
				</tr>
				<tr>
					<td>Cryptogramme:</td>
					<td><?php echo "<input type='number' name='Cryptogramme' value ='" . $Cryptogramme ."'>";?></td>
				</tr>

				<tr>
					<td colspan="2" style="text-align:center;"><input type="submit" value="Valider" name="valider"></td>
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