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
					if($_)
					redirect('./?page=account');
			}
		}
	}
	else
	{
		if($_)
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
				<?php echo "<input class='form-control' type='text' placeholder='Ligne 1' name='ligne1' value ='" . $ligne1 ."'>";?>	
			</div>
			<div class='form-group col-md-12'>
				<?php echo "<input class='form-control' type='text' placeholder='Ligne 2' name='ligne2' value ='" . $ligne2 ."'>";?>
			</div>
			<div class='form-group col-md-6'>
				<?php echo "<input class='form-control' type='text' placeholder='Ville' name='ville' value ='" . $ville ."'>";?>			
			</div>
			<div class='form-group col-md-4'>
				<?php echo "<input class='form-control' type='number' placeholder='Code Postal' name='codePostal' value ='" . $codePostal ."'>";?>			
			</div>
			<div class='form-group col-md-2'>
				<?php echo "<input class='form-control' type='text' placeholder='Pays ' name='pays' value ='" . $pays ."'>";?>
			</div>
			<div class='form-group col-md-12'>
				<?php echo "<input class='form-control' type='text' placeholder='Téléphone' name='telephone' value ='" . $telephone ."'>";?>
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