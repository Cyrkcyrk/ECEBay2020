<?php
	$erreur = ""; 
	$prix =  isset($_POST["prix"])? $_POST["prix"] :"";
	$message =  isset($_POST["message"])? $_POST["message"] :"";
	
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
<form action="./?page=ajouterOffre" method="post" >
	<div  id="identification">
		<div id="formulaire" class="form-row">
			<div class='form-group col-md-12'>
				<?php echo "<input class='form-control' type='number' step='0.01' placeholder='Prix' name='prix' value ='" . $prix ."'>";?>	
			</div>
			<div class='form-group col-md-12'>
				<?php echo "<textarea placeholder='Message' class='form-control' name='message'>" . $message ."'</textarea>";?>
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