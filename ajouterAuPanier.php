<?php 
	$erreur = "";
	$itemID = blindage(isset($_GET["item"])? $_GET["item"] : "");
	if($itemID != "")
	{
		if($logged)
		{
			$sql = "SELECT * FROM `item` WHERE `ID` = ". $itemID .";";
			$mysqli = new mysqli($_DATABASE["host"],$_DATABASE["user"],$_DATABASE["password"],$_DATABASE["BDD"]);
			mysqli_set_charset($mysqli, "utf8");

			if ($mysqli -> connect_errno) {
				$erreur .= "Failed to connect to MySQL: " . $mysqli -> connect_error;
			}
			if ($result = $mysqli -> query($sql)) {
				if (mysqli_num_rows($result) > 0) {
					$row = mysqli_fetch_assoc($result);
					$result -> free_result();
					$mysqli -> close();
					
					$sql = "SELECT * FROM `panier` WHERE `ItemID` = ". $itemID ." AND `OwnerID`= ". $user["ID"] ." AND `TypeAchat` =". 0 .";";
					list ($_, $erreur) = SQLCheck($_DATABASE, $sql, $erreur);
					if(!$_)
					{
						if($row["EtatVente"] == 1)
						{
							if($row["OwnerID"] != $user["ID"])
							{
								$sql = "INSERT INTO `panier`(`ItemID`, `OwnerID`, `Date`, `TypeAchat`) VALUES (". $itemID .", ". $user["ID"] .", '". time() ."', ". 0 .");";
								list ($_, $erreur) = SQLquery($_DATABASE, $sql, $erreur);
								if($_)
								{
									redirect("./?page=panier");
								}
							}
							else
							{
								$erreur .= "Vous ne pouvez pas acheter un item que vous vendez.";
							}
						}
						else
						{
							$erreur .= "Cet item n'est plus a vendre";
						}
					}
					else
					{
						redirect("./?page=panier");
					}
				}
				else
				{
					$erreur .= "Cet item n'existe pas<br>";
				}
			}
			else
			{
				$erreur .= "Une erreur est survenue";
			}
		}
		else
		{
			redirect("./?page=item&item=" . $itemID);
		}
	}
	else
	{
		redirect("./?page=accueil");
	}
	


include("./template/_top.php");

if($erreur != "")
	echo $erreur;

include("./template/_bot.php"); ?>