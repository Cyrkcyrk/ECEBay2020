<?php
	$erreur = ""; 
	$prix =  isset($_POST["prix"])? $_POST["prix"] :"";
	$message =  isset($_POST["message"])? $_POST["message"] :"";
	$offerID =  isset($_POST["offerID"])? $_POST["offerID"] :"";
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
			
			if($offerID == "") {
				$erreur .= "Une erreur à eu lieu avec l'ID de l'offre.";
			}
			
			if($erreur == "")
			{
				$sql = "
				SELECT o.*, i.`OwnerID` FROM `offres` AS o
				JOIN `item` as i
					ON o.`ItemID` = i.`ID`
				WHERE o.`ID` = ". $offerID ." AND (o.`BuyerID` = ". $user["ID"] ." OR i.`OwnerID` = ". $user["ID"] .");";
				
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
						
						// $sql = "SELECT COUNT(*) FROM (SELECT `ID` FROM `offremessage` WHERE `OffreID` = ". $Offre["ID"] .") AS T";
						
						$sql = "INSERT INTO `offremessage`(`OffreID`, `SenderID`, `Prix`, `NumeroNegociation`, `Message`, `Date`) VALUES (". $Offre["ID"] .", ". $user["ID"] .", ". $prix .", ". "(SELECT COUNT(*) FROM (SELECT `ID` FROM `offremessage` WHERE `OffreID` = ". $Offre["ID"] .") AS T)" .", '". $message ."', '". time() ."' )";
						
						list ($_, $erreur) = SQLquery($_DATABASE, $sql, $erreur);
						if($_)
						{
							$sql = "UPDATE `offres` SET `NbrOffre`=`NbrOffre`+1  WHERE `ID` = ". $Offre["ID"] .";";
							list ($_, $erreur) = SQLquery($_DATABASE, $sql, $erreur);
							if($_)
							{
								redirect("./?page=offres&offerID=" . $Offre["ID"]);
							}
						}
					}
					else
					{
						$erreur .= "Cette offre n'existe pas ou vous n'en faites pas partis.";
					}
				}
				
				
				
				
				// $sql = "INSERT INTO `adresse`(`OwnerID`, `Ligne1`, `Ligne2`, `Ville`, `CodePostal`, `Pays`, `Telephone`) VALUES ('" . $user["ID"] . "', '" . $ligne1 . "', '" . $ligne2 . "', '" . $ville . "', '" . $codePostal . "', '" . $pays . "', '" . $telephone . "')";
				// list ($_, $erreur) = SQLquery($_DATABASE, $sql, $erreur);
				// if($_)
					// redirect('./?page=panier');
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
<form action="./?page=AjouterOffre" method="post" >
	<div  id="identification">
		<div id='formulaire' class='form-row'>
			<div class='form-group col-md-12'>
				<input class='form-control' type='number' step='0.01' placeholder='Prix' value='<?php echo $prix;?>' name='prix'>
			</div>
			<div class='form-group col-md-12'>
				<textarea placeholder='Message' class='form-control' name='message'><?php echo $message;?></textarea>
			</div>
			<input type='hidden' name='offerID' value='<?php echo $offerID;?>'>
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