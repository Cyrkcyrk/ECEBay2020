<?php
	$erreur = ""; 
	$prix =  isset($_POST["prix"])? $_POST["prix"] :"";
	$message =  isset($_POST["message"])? $_POST["message"] :"";
	$valider =  isset($_POST["valider"])? $_POST["valider"] :"";
	
	if($logged)
	{
		if($valider != "")
		{
			if($prix == "") {
				$erreur .= "Veuillez indiquer un prix.<br>";
			} 
			else if($prix < 0) {
				$erreur .= "Veuillez indiquer un prix au dessus de 0 €.<br>";
			}
			
			if($erreur == "")
			{
				$sql = "SELECT * FROM `Offres` WHERE `ItemID` = ".  ." AND `BuyerID` = ".  .";"
				
				$mysqli = new mysqli($_DATABASE["host"],$_DATABASE["user"],$_DATABASE["password"],$_DATABASE["BDD"]);
				mysqli_set_charset($mysqli, "utf8");
				
				if ($mysqli -> connect_errno) {
					$erreur .= "Failed to connect to MySQL: " . $mysqli -> connect_error;
				}
				if ($result = $mysqli -> query($sql)) {
					if (mysqli_num_rows($result) > 0) {
						
						$Offre = mysqli_fetch_assoc($result);
						$result -> free_result();
						$mysqli -> close();
					}
					else
					{
						
					}
				
				
				
				
				// $sql = "INSERT INTO `adresse`(`OwnerID`, `Ligne1`, `Ligne2`, `Ville`, `CodePostal`, `Pays`, `Telephone`) VALUES ('" . $user["ID"] . "', '" . $ligne1 . "', '" . $ligne2 . "', '" . $ville . "', '" . $codePostal . "', '" . $pays . "', '" . $telephone . "')";
				list ($_, $erreur) = SQLquery($_DATABASE, $sql, $erreur);
				if($_)
					redirect('./?page=panier');
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
<form action="./?page=ajouterOffre" method="post" >
	<div  id="identification">
		<div id='formulaire' class='form-row'>
			<div class='form-group col-md-12'>
				<input class='form-control' type='number' step='0.01' placeholder='Prix' value='<?php echo $prix;?>' name='prix'>
			</div>
			<div class='form-group col-md-12'>
				<textarea placeholder='Message' class='form-control' name='message'><?php echo $message;?></textarea>
			</div>
			<input type='hidden' name='ID' value='". $item ["ID"] ."'>
			<button type='submit' class='btn btn-primary' value='Envoyer l'offre' name='valider'>Valider</button>
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