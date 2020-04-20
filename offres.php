<?php 
	$erreur = "";
	$offerID = blindage(isset($_GET["offerID"])? $_GET["offerID"] : "");
	
	// $offer = false;
	$messages = false;
	$discutions = false;
	$offre = false;
	
	
	if($erreur == "")
	{
		if($logged)
		{	
			if($offerID != "")
			{	
				$sql = "
					SELECT o.`ID`
					FROM `offres` AS o
					JOIN `item` as i
						on o.`ItemID` = i.`ID`
					WHERE o.`ID` = ". $offerID ." AND (o.`BuyerID` = '". $user["ID"] ."' OR i.`OwnerID` = '". $user["ID"] ."');";
				list($_, $erreur) = SQLCheck($_DATABASE, $sql, $erreur);
				if($_)
				{
				
					$sql = "
							SELECT O.*, U.`Prenom`, U.`Nom` FROM `offremessage` AS O
							JOIN (SELECT `ID` AS 'UserID', `Nom` AS 'Nom', `Prenom` AS 'Prenom' FROM `utilisateur`) AS U
								ON U.`UserID` = O.`SenderID`
							WHERE `OffreID` = ". $offerID ."
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
				else
				{
					$message = False;
					$erreur .= "Vous ne pouvez pas voir cette offre car vous n'en etes pas acteur.";
				}
			}
			
			
			
			$sql = "
			SELECT * FROM (
				SELECT
					o.`ID` 						AS 'OffreID',
					o.`ItemID` 					AS 'ItemID',
					o.`IDOffreMessageAccepte` 	AS 'IDOffreMessageAccepte',
					i.`Nom` 					AS 'ItemNom',
					i.`Lien` 					AS 'ItemImage',
					i.`EtatVente` 				AS 'ItemEtatVente',
					(SELECT `offremessage`.`Message` 	FROM `offremessage` WHERE `offremessage`.`OffreID` = o.`ID` ORDER BY `Date` DESC LIMIT 1) AS 'LastMessage',
					(SELECT `offremessage`.`Prix` 		FROM `offremessage` WHERE `offremessage`.`OffreID` = o.`ID` ORDER BY `Date` DESC LIMIT 1) AS 'LastOffer',
					(SELECT `offremessage`.`Date` 		FROM `offremessage` WHERE `offremessage`.`OffreID` = o.`ID` ORDER BY `Date` DESC LIMIT 1) AS 'LastMessageDate',
					(SELECT `offremessage`.`SenderID` 	FROM `offremessage` WHERE `offremessage`.`OffreID` = o.`ID` ORDER BY `Date` DESC LIMIT 1) AS 'LastSenderID',
					Owner.*,
					Buyer.*
				FROM `offres` AS o
				LEFT JOIN (
					SELECT 
						i.*, 
						CASE WHEN EXISTS (SELECT m.`Lien` FROM `medias` AS m WHERE m.`ItemID` = i.`ID` AND m.`Ordre` = 0 AND m.`type` = 1 )
							THEN (SELECT m.`Lien` FROM `medias` AS m WHERE m.`ItemID` = i.`ID` AND m.`Ordre` = 0 AND m.`type` = 1 )
							ELSE './img/notfound.jpg'
						END AS `Lien`
					FROM `item` AS i ) AS i
					ON o.`ItemID` = i.`ID`
				LEFT JOIN (SELECT `ID` AS 'OwnerID', `Nom` AS 'NomOwner', `Prenom` AS 'PrenomOwner' FROM `utilisateur`) AS Owner
					ON Owner.`OwnerID` = i.`OwnerID`
				LEFT JOIN (SELECT `ID` AS 'BuyerID', `Nom` AS 'NomBuyer', `Prenom` AS 'PrenomBuyer' FROM `utilisateur`) AS Buyer
					ON Buyer.`BuyerID` = o.`BuyerID`
				WHERE (Owner.`OwnerID` = ". $user["ID"] ." OR Buyer.`BuyerID` = ". $user["ID"] .")
				) AS T ORDER BY T.`LastMessageDate` DESC";
			
			// echo $sql;
			
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
						if($row["OffreID"] == $offerID)
						{
							$offre = $row;
						}
						array_push($discutions, Array(
							"OffreID" => $row["OffreID"],
							"ItemID" => $row["ItemID"],
							"IDOffreMessageAccepte" => $row["IDOffreMessageAccepte"],
							"ItemNom" => $row["ItemNom"],
							"ItemImage" => $row["ItemImage"],
							"ItemEtatVente" => $row["ItemEtatVente"],
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
				$result -> free_result();
				$mysqli -> close();
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
					if($discutions)
					{
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
							
							$endedOffer = "";
							if($d["IDOffreMessageAccepte"] > -1)
							{
								$endedOffer = 'enchereValide';
							}
							else if($d["IDOffreMessageAccepte"] == -2)
							{
								$endedOffer = 'enchereInvalide';
							}
							else if($d["ItemEtatVente"] == -1)
							{
								$endedOffer = 'enchereInvalide';
								// $d["ItemImage"] = "./img/notfound.jpg";
							}

							echo "
							<a href='./?page=offres&offerID=". $d["OffreID"] ."'>
								<div class='chat_list ". $_activeDiscution ."'>
									<div class='". $endedOffer ."'>
										<div class='chat_people'>
											<div class='chat_img'> <img src='". $d["ItemImage"] ."' alt='Image article'> </div>
											<div class='chat_ib'>
												<h5>". $_personne . " - " . $d["ItemNom"] . " <span class='chat_date'>". $_date ."</span></h5>
												<p><b>". $d["LastOffer"] ."€</b>: ". $d["LastMessage"] . "</p>
											</div>
										</div>
									</div>
								</div>
							</a>\n";
						}
					}
					else
					{
						echo "Aucune offre à afficher";
					}
				?>
			</div>
		</div>
		
		<div class="mesgs">
			<div class="msg_history">
				<?php
					if($offerID != "" && $messages)
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
						
						// echo json_encode($offre);
						if($offre["IDOffreMessageAccepte"] == -1)
						{
							// <button class='btn btn-primary' name='valider' value='valider' type='submit'>Accepter offre</button>
							if($offre["LastSenderID"] != $user["ID"])
							{
								echo "
								<div class='row'>
									<div class='col-md-1'>
										<form action='./?page=statusOffre' method='post'>
											<input type='hidden' name='offerID' value='". $_offreID ."'>
											<div class='row'>
												<div class='col-md-6'>
													
													<div class='buttonAcceptReject'>
														<button class='msg_send_btn_accept' name='valider' value='accepter' type='submit'><i class='fa fa-check' aria-hidden='true'></i></button>
														<span class='buttonAcceptRejectText'>Accepter l'offre</span>
													</div> 
													
												</div>
												<div class='col-md-6'>
													<div class='buttonAcceptReject'>
														<button class='msg_send_btn_reject' name='valider' value='refuser' type='submit'><i class='fa fa-times' aria-hidden='true'></i></button>
														<span class='buttonAcceptRejectText'>Refuser l'offre</span>
													</div> 
													
												</div>
											</div>
										</form>
									</div>
									<div class='col-md-11'>
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
										</form>
									</div>
								</div>\n";
							}
							else
							{
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
							}
						}
						else if ($offre["IDOffreMessageAccepte"] > -1)
						{
							echo "
							<div class='enchereValide text-center' style='height : 57px;'>
								Offre acceptée.
							</div>";
						}
						else if ($offre["IDOffreMessageAccepte"] == -2)
						{
							echo "
							<div class='enchereInvalide text-center' style='height : 57px;'>
								Offre refusée.
							</div>";
						}
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


