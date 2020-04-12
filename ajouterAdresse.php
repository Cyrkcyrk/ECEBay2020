<?php
	$erreur = ""; 
	$ligne1 =  isset($_POST["ligne1"])? $_POST["ligne1"] :"";
	$ligne2 =  isset($_POST["ligne2"])? $_POST["ligne2"] :"";
	$ville =  isset($_POST["ville"])? $_POST["ville"] :"";
	$codePostal =  isset($_POST["codePostal"])? $_POST["codePostal"] :"";
	$pays =  isset($_POST["pays"])? $_POST["pays"] :"";
	$telephone =  isset($_POST["telephone"])? $_POST["telephone"] :"";
	$valider =  isset($_POST["valider"])? $_POST["valider"] :"";
	
	if($logged)
	{
		if($valider != "")
		{
			if($ligne1 == "") {
				$erreur .= "ligne1 incomplete <br>";
			} 
			if($ville == "") {
				$erreur .= "ville incomplet <br>";
			} 
			if($codePostal == "") {
				$erreur .= "codePostal incomplet <br>";
			} 
			if($pays == "") {
				$erreur .= "pays incomplet <br>";
			} 
			if($telephone == "") {
				$erreur .= "telephone incomplet <br>";
			} 
			
			if($erreur == "")
			{
				$sql = "INSERT INTO `adresse`(`OwnerID`, `Ligne1`, `Ligne2`, `Ville`, `CodePostal`, `Pays`, `Telephone`) VALUES ('" . $user["ID"] . "', '" . $ligne1 . "', '" . $ligne2 . "', '" . $ville . "', '" . $codePostal . "', '" . $pays . "', '" . $telephone . "')";
				list ($_, $erreur) = SQLquery($_DATABASE, $sql, $erreur);
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

<form action="./?page=ajouterAdresse" method="post">
	<div id="identification">
		<div id="formulaire">
			<table>
				<tr>
					<td>Ligne 1:</td>
					<td> <?php echo "<input type='text' name='ligne1' value ='" . $ligne1 ."'>";?></td>
				</tr>
				<tr>
					<td>Ligne 2:</td>
					<td> <?php echo "<input type='text' name='ligne2' value ='" . $ligne2 ."'>";?></td>
				</tr>
				<tr>
					<td>Ville:</td>
					<td><?php echo "<input type='text' name='ville' value ='" . $ville ."'>";?></td>
				</tr>
				<tr>
					<td>Code Postal:</td>
					<td><?php echo "<input type='text' name='codePostal' value ='" . $codePostal ."'>";?></td>
				</tr>
				<tr>
					<td>Pays:</td>
					<td><?php echo "<input type='text' name='pays' value ='" . $pays ."'>";?></td>
				</tr>
				<tr>
					<td>Telephone:</td>
					<td><?php echo "<input type='text' name='telephone' value ='" . $telephone ."'>";?></td>
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