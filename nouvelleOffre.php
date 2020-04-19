<?php
	$erreur = ""; 
	$prix =  blindage(isset($_POST["prix"])? $_POST["prix"] :"");
	$message =  blindage(isset($_POST["message"])? $_POST["message"] :"");
	$ItemID = blindage(isset($_POST["itemID"])? $_POST["itemID"] :"");
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
			if($ItemID == "") {
				$erreur .= "Un problème à eu lieu avec l'item ID.<br>";
			} 
			
			if($erreur == "")
			{
				
				$sql = "SELECT * FROM `offres` WHERE `ItemID` = ". $ItemID ." AND `BuyerID` = ". $user["ID"] .";";
				
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
						
						$erreur .= "Une offre existe déjà à votre nom<br>";
						redirect("./?page=offres&offerID=" . $Offre["ID"]);
					}
					else
					{
						
						$sql = "SELECT * FROM `item` WHERE `ID` = ". $ItemID .";";
						$mysqli = new mysqli($_DATABASE["host"],$_DATABASE["user"],$_DATABASE["password"],$_DATABASE["BDD"]);
						mysqli_set_charset($mysqli, "utf8");
						
						if ($mysqli -> connect_errno) {
							$erreur .= "Failed to connect to MySQL: " . $mysqli -> connect_error;
						}
						if ($result = $mysqli -> query($sql)) {
							if (mysqli_num_rows($result) > 0) {
								
								$item = mysqli_fetch_assoc($result);
								$result -> free_result();
								$mysqli -> close();
								
								if($item["ModeVente"] == 2)
								{
									$sql = "INSERT INTO `offres`(`ItemID`, `BuyerID`) VALUES (". $ItemID .", ". $user["ID"] .");";
									list($_, $erreur) = SQLquery($_DATABASE, $sql, $erreur);
									if($_)
									{
										$sql = "SELECT * FROM `offres` WHERE `ItemID` = ". $ItemID ." AND `BuyerID` = ". $user["ID"] .";";
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
												
												$sql = "INSERT INTO `offremessage` (`OffreID`, `SenderID`, `Prix`, `NumeroNegociation`, `Message`, `Date`) VALUES (". $Offre["ID"] .", ". $user["ID"] .", ". $prix .", ". 0 .", '". $message ."', '". time() ."' )";
												
												list($_, $erreur) = SQLquery($_DATABASE, $sql, $erreur);
												if($_)
													redirect("./?page=offres&offerID=" . $Offre["ID"]);
												else
													$erreur .= "Une erreur s'est produite lors de l'ajout de l'offre";
											}
											else
											{
												$erreur .= "Une erreur s'est produite lors de l'ajout de l'offre";
											}
										}
									}
									else
									{
										$erreur .= "Une erreur s'est produite lors de la création de l'offre";
									}
								}
								else
								{
									$erreur .= "Vous ne pouvez pas proposer d'offre pour cet item. <br>";
								}
							}
							else
							{
								$erreur .= "L'item n'existe pas. <br>";
							}
						}
					}
				}
				else
				{
					$erreur .= "Une erreur s'est produite avec la requete : " . $sql;
				}
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

<form action="./?page=nouvelleOffre" method="post" >
	<div  id="identification">
		<div id='formulaire' class='form-row'>
			<div class='form-group col-md-12'>
				<input class='form-control' type='number' step='0.01' placeholder='Prix' value='<?php echo $prix;?>' name='prix'>
			</div>
			<div class='form-group col-md-12'>
				<textarea placeholder='Message' class='form-control' name='message'><?php echo $message;?></textarea>
			</div>
			<input type='hidden' name='itemID' value='<?php echo $ItemID;?>'>
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