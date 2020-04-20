<?php
	$erreur = ""; 
	$ligne1 =  blindage(isset($_POST["ligne1"])? $_POST["ligne1"] :"");
	$ligne2 =  blindage(isset($_POST["ligne2"])? $_POST["ligne2"] :"");
	$ville =  blindage(isset($_POST["ville"])? $_POST["ville"] :"");
	$codePostal =  blindage(isset($_POST["codePostal"])? $_POST["codePostal"] :"");
	$pays =  blindage(isset($_POST["pays"])? $_POST["pays"] :"");
	$telephone =  blindage(isset($_POST["telephone"])? $_POST["telephone"] :"");
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
<form action="./?page=ajouterAdresse" method="post" >
	<div  id="identification">
		<div id="formulaire" class="form-row">
			<div class='form-group col-md-12'>
				<input class='form-control' type='text' placeholder='Ligne 1' name='ligne1' value ='<?php echo  $ligne1; ?>'>
			</div>
			<div class='form-group col-md-12'>
				<input class='form-control' type='text' placeholder='Ligne 2' name='ligne2' value ='<?php echo $ligne2; ?>'>
			</div>
			<div class='form-group col-md-6'>
				<input class='form-control' type='text' placeholder='Ville' name='ville' value ='<?php echo $ville; ?>'>			
			</div>
			<div class='form-group col-md-4'>
				<input class='form-control' type='number' placeholder='Code Postal' name='codePostal' value ='<?php echo $codePostal; ?>'>			
			</div>
			<div class='form-group col-md-2'>
				<input class='form-control' type='text' placeholder='Pays ' name='pays' value ='<?php echo $pays; ?>'>
			</div>
			<div class='form-group col-md-12'>
				<input class='form-control' type='text' placeholder='Téléphone' name='telephone' value ='<?php echo $telephone ?>'>";
			</div>
			<button type="submit" class="btn btn-primary" value="Valider" name="valider">Valider</button>	
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