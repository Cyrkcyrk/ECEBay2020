<?php 
	$erreur = "";
	$offerID = isset($_GET["offerID"])? $_GET["offerID"] : "";
	
	$offer = "";
	$messages = "";
	$discutions = "";
	
	/*if($offerID == "")
	{
		$erreur .= "Une erreur est survenue avec l'ID de l'offre.";
	}*/
	
	if($erreur == "")
	{
		if($logged)
		{
			if($offerID != "")
			{
				$sql = "
					SELECT o.*, i.`OwnerID`, Buyer.`NomBuyer`, Buyer.`PrenomBuyer`, Owner.`NomOwner`, Owner.`PrenomOwner` 
					FROM `offres` AS o
					JOIN `item` as i
						on o.`ItemID` = i.`ID`
					JOIN (SELECT `ID` AS 'OwnerID', `Nom` AS 'NomOwner', `Prenom` AS 'PrenomOwner' FROM `utilisateur`) AS Owner
						ON Owner.`OwnerID` = i.`OwnerID`
					JOIN (SELECT `ID` AS 'BuyerID', `Nom` AS 'NomBuyer', `Prenom` AS 'PrenomBuyer' FROM `utilisateur`) AS Buyer
						ON Buyer.`BuyerID` = o.`BuyerID`
					WHERE o.`ID` = ". $offerID .";";
				
				$mysqli = new mysqli($_DATABASE["host"],$_DATABASE["user"],$_DATABASE["password"],$_DATABASE["BDD"]);
				mysqli_set_charset($mysqli, "utf8");
				
				if ($mysqli -> connect_errno) {
					$erreur .= "Failed to connect to MySQL: " . $mysqli -> connect_error;
				}
				if ($result = $mysqli -> query($sql)) {
					if (mysqli_num_rows($result) > 0) {
						
						$offer = mysqli_fetch_assoc($result);
					}
					else
					{
						$erreur .= "Cette offre n'existe pas";
					}
				}
				else
				{
					$erreur .= "Une erreur est survenue";
				}
				$result -> free_result();
				$mysqli -> close();
				

				$sql = "
						SELECT O.*, U.`Prenom`, U.`Nom` FROM `offremessage` AS O
						JOIN (SELECT `ID` AS 'UserID', `Nom` AS 'Nom', `Prenom` AS 'Prenom' FROM `utilisateur`) AS U
							ON U.`UserID` = O.`SenderID`
						WHERE `OffreID` = ". $offer["ID"] ."
						ORDER BY `NumeroNegociation` ASC";
				
				$mysqli = new mysqli($_DATABASE["host"],$_DATABASE["user"],$_DATABASE["password"],$_DATABASE["BDD"]);
				mysqli_set_charset($mysqli, "utf8");
				
				if ($mysqli -> connect_errno) {
					$erreur .= "Failed to connect to MySQL: " . $mysqli -> connect_error;
				}
				if ($result = $mysqli -> query($sql)) {
					if (mysqli_num_rows($result) > 0) {
						
						$messages = Array();
						while ($row = mysqli_fetch_assoc($result))
						{
							array_push($messages, Array(
								"ID" => $row["ID"],
								"Message" => $row["Message"],
								"Prix" => $row["Prix"],
								"Date" => $row["Date"],
								"NumeroNegociation" => $row["NumeroNegociation"],
								"SenderID" => $row["SenderID"],
								"SenderNom" => $row["Nom"],
								"SenderPrenom" => $row["Prenom"],
							));
						}
					}
					else
					{
						$message = False;
						$erreur .= "Cette offre n'existe pas";
					}
				}
				else
				{
					$erreur .= "Une erreur est survenue";
				}
			}
			
			
			
			$sql = "
			SELECT * FROM (
				SELECT
					o.`ID` 			AS 'OffreID',
					o.`ItemID` 		AS 'ItemID',
					i.`Nom` 		AS 'ItemNom',
					i.`Lien` 		AS 'ItemImage',
					(SELECT `offremessage`.`Message` 	FROM `offremessage` WHERE `offremessage`.`OffreID` = o.`ID` ORDER BY `Date` DESC LIMIT 1) AS 'LastMessage',
					(SELECT `offremessage`.`Prix` 		FROM `offremessage` WHERE `offremessage`.`OffreID` = o.`ID` ORDER BY `Date` DESC LIMIT 1) AS 'LastOffer',
					(SELECT `offremessage`.`Date` 		FROM `offremessage` WHERE `offremessage`.`OffreID` = o.`ID` ORDER BY `Date` DESC LIMIT 1) AS 'LastMessageDate',
					(SELECT `offremessage`.`SenderID` 	FROM `offremessage` WHERE `offremessage`.`OffreID` = o.`ID` ORDER BY `Date` DESC LIMIT 1) AS 'LastSenderID',
					Owner.*,
					Buyer.*
				FROM `offres` AS o
				LEFT JOIN (SELECT i.*, m.`Lien` FROM `item` AS i INNER JOIN `medias` AS m ON m.`ItemID` = i.`ID` WHERE m.`type` = 1 AND m.`Ordre` = 0) AS i
					ON o.`ItemID` = i.`ID`
				LEFT JOIN (SELECT `ID` AS 'OwnerID', `Nom` AS 'NomOwner', `Prenom` AS 'PrenomOwner' FROM `utilisateur`) AS Owner
					ON Owner.`OwnerID` = i.`OwnerID`
				LEFT JOIN (SELECT `ID` AS 'BuyerID', `Nom` AS 'NomBuyer', `Prenom` AS 'PrenomBuyer' FROM `utilisateur`) AS Buyer
					ON Buyer.`BuyerID` = o.`BuyerID`
				WHERE (Owner.`OwnerID` = ". $user["ID"] ." OR Buyer.`BuyerID` = ". $user["ID"] .")
				) AS T ORDER BY T.`LastMessageDate` DESC";
			
			$mysqli = new mysqli($_DATABASE["host"],$_DATABASE["user"],$_DATABASE["password"],$_DATABASE["BDD"]);
			mysqli_set_charset($mysqli, "utf8");
			
			if ($mysqli -> connect_errno) {
				$erreur .= "Failed to connect to MySQL: " . $mysqli -> connect_error;
			}
			if ($result = $mysqli -> query($sql)) {
				if (mysqli_num_rows($result) > 0) {
					
					$discutions = Array();
					while ($row = mysqli_fetch_assoc($result))
					{
						array_push($discutions, Array(
							"OffreID" => $row["OffreID"],
							"ItemID" => $row["ItemID"],
							"ItemNom" => $row["ItemNom"],
							"ItemImage" => $row["ItemImage"],
							"LastMessage" => $row["LastMessage"],
							"LastOffer" => $row["LastOffer"],
							"LastMessageDate" => $row["LastMessageDate"],
							"LastSenderID" => $row["LastSenderID"],
							"OwnerID" => $row["OwnerID"],
							"NomOwner" => $row["NomOwner"],
							"PrenomOwner" => $row["PrenomOwner"],
							"BuyerID" => $row["BuyerID"],
							"NomBuyer" => $row["NomBuyer"],
							"PrenomBuyer" => $row["PrenomBuyer"]
						));
					}
				}
				else
				{
					$discutions = False;
				}
			}
			else
			{
				$erreur .= "Une erreur est survenue";
			}
		}
		else
		{
			$erreur .= "Veuillez vous connecter";
		}
	}
	
	
	
	
?>
<?php include("./template/_top.php");?>
<!--Base de https://bootsnipp.com/snippets/1ea0N-->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" type="text/css" rel="stylesheet">
<link href="./css/messaging.css" type="text/css" rel="stylesheet">
<div class="messaging">
	<div class="inbox_msg">
		
			
		<div class="inbox_people">
			<div class="inbox_chat">
				<?php
					forEach($discutions as $d)
					{
						$_date = date("F j - G:i", $d["LastMessageDate"]);
						$_personne = "";
						if($user["ID"] == $d["BuyerID"])
							$_personne = $d["PrenomOwner"] . " " . $d["NomOwner"];
						else
							$_personne = $d["PrenomBuyer"] . " " . $d["NomBuyer"];
						
						$_activeDiscution = "";
						if($offerID != "" && $d["OffreID"] == $offerID)
							$_activeDiscution .= " active_chat";
						
						
						echo "
						<a href='./?page=offres&offerID=". $d["OffreID"] ."'>
							<div class='chat_list". $_activeDiscution ."'>
								<div class='chat_people'>
									<!--<a href='./?page=item&item=". $d['ItemID'] ."'><div class='chat_img'> <img src='". $d["ItemImage"] ."' alt='Image article'> </div></a>-->
									<div class='chat_img'> <img src='". $d["ItemImage"] ."' alt='Image article'> </div>
									<div class='chat_ib'>
										<h5>". $_personne ."<span class='chat_date'>". $_date ."</span></h5>
										<p><b>". $d["LastOffer"] ."€</b>: ". $d["LastMessage"] . "</p>
									</div>
								</div>
							</div>
						</a>\n";
					}
					
				?>
			</div>
		</div>
		
		<div class="mesgs">
			<div class="msg_history">
				<?php
					if($offerID != "")
					{
						forEach($messages as $m)
						{
							$_date = date("G:i   |   F j", $m["Date"]);
							if($user["ID"] == $m["SenderID"])
							{
								
								echo "
								<div class='outgoing_msg'>
									<div class='sent_msg'>
										<p><b>". $m["Prix"] ."€ </b><br>". $m["Message"] ."</p>
										<span class='time_date'>". $_date ."</span> 
									</div>
								</div>\n";
							}
							else
							{
								echo "
								<div class='incoming_msg'>
									<div class='received_msg'>
										<div class='received_withd_msg'>
											<p><b>". $m["Prix"] ."€ </b><br>". $m["Message"] ."</p>
											<span class='time_date'>". $_date ."</span> 
										</div>
									</div>
								</div>\n";
							}
						}
					}
					
				?>
				

			</div>
			<div class="type_msg">
				<div class='input_msg_write'>
					<?php
						$_offreID = "";
						if($offerID != "") {
							$_offreID = $offerID;
						} else {
							$_offreID = "";
						}
							echo "
							<form action='./?page=AjouterOffre' method='post'>
								<div class='row'>
									<div class='col-md-2'>
										<input type='number' step='0.01' class='write_msg' placeholder='Prix' name='prix'/>
									</div>
									<div class='col-md-10'>
										<input type='text' class='write_msg' placeholder='Type a message' name='message' />
										<input type='hidden' name='offerID' value='". $_offreID ."'>
										
										<button class='msg_send_btn' name='valider' value='valider' type='submit'><i class='fa fa-paper-plane-o' aria-hidden='true'></i></button>
									</div>
								</div>
							</form>\n";
					?>
					
					
				</div>
			</div>
		</div>
	</div>
</div>




<?php
	if($erreur != "")
	{
		echo $erreur;
	}
?>

<?php include("./template/_bot.php"); ?>


