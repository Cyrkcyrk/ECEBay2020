<?php
	$erreur = ""; 
	$ID =  blindage(isset($_POST["ID"])? $_POST["ID"] :"");
	$Mail =  blindage(isset($_POST["mail"])? $_POST["mail"] :"");
	$Role =  blindage(isset($_POST["role"])? $_POST["role"] :"");
	$valider =  isset($_POST["valider"])? $_POST["valider"] :"";
	
	$UserIDGET =  blindage(isset($_GET["ID"])? $_GET["ID"] :"");
	$UserSelected = "";
	
	if($logged && $user["TypeCompte"] == 3)
	{
		if($UserIDGET != "")
		{
			$sql = "SELECT * FROM `utilisateur` WHERE `ID` = '". $UserIDGET ."';";
			$mysqli = new mysqli($_DATABASE["host"], $_DATABASE["user"], $_DATABASE["password"], $_DATABASE["BDD"]);
			mysqli_set_charset($mysqli, "utf8");
			
			if ($mysqli -> connect_errno) {
				$erreur .= "Failed to connect to MySQL: " . $mysqli -> connect_error;
			}
			if ($result = $mysqli -> query($sql)) {
				if (mysqli_num_rows($result) > 0) {
					$UserSelected = mysqli_fetch_assoc($result);
				}
				else
				{
					$UserSelected = false;
				}
				$result -> free_result();
				$mysqli -> close();
			}
		}
		
		if($valider != "")
		{
			if($ID == "" && $Mail == "") {
				$erreur .= "Précisez au moins un mail ou un ID <br>";
			} 
			if($Role == "") {
				$erreur .= "Role incomplet <br>";
			} 
			
			$_typeCompte = 0;
			switch ($Role)
			{
				case "Acheteur":
					$_typeCompte = 1;
					break;
				case "Vendeur":
					$_typeCompte = 2;
					break;
				case "Administrateur":
					$_typeCompte = 3;
					break;
				default :
					$erreur .= "Le role est invalide";
					$Role = "";
			}
			
			if($erreur == "")
			{	
				$sql = "";
				
				echo $ID . " - " . $Mail;
				
				if($ID != "" && $Mail != "")
					$sql = "UPDATE `utilisateur` SET `TypeCompte`= ". $_typeCompte ." WHERE `ID` = '". $ID  ."' AND `Mail` = '". $Mail ."';";
				else if($ID == "" && $Mail != "")
					$sql = "UPDATE `utilisateur` SET `TypeCompte`= ". $_typeCompte ." WHERE `Mail` = '". $Mail ."';";
				else if($ID != "" && $Mail == "")
					$sql = "UPDATE `utilisateur` SET `TypeCompte`= ". $_typeCompte ." WHERE `ID` = '". $ID ."';";
				
				list($_, $erreur) = SQLquery($_DATABASE, $sql, $erreur);
				if($_)
					redirect("./?page=adminUsers");
			}
		}
		
	}
	
?>
<?php include("./template/_top.php"); ?>

<form action="./?page=changerStatusUtilisateur" method="post" >
	<div  id="identification">
		<div id="formulaire" class="form-row">
			<div class='form-group col-md-1'>
				<?php 
				if($UserSelected) {
					echo "<input class='form-control' type='text' disabled value='". $UserSelected["ID"] ."'>";
					echo "<input type='hidden' name='ID'  value='". $UserSelected["ID"] ."'>";
				} else {
					echo "<input class='form-control' type='text' placeholder='ID' name='ID' value =''>";
				}
				?>	
			</div>
			<div class='form-group col-md-11'>
				<?php 
				if($UserSelected) {
					echo "<input class='form-control' type='text' disabled value='". $UserSelected["Mail"] ."'>";
					echo "<input type='hidden' name='mail' value='". $UserSelected["Mail"] ."'>";
				} else {
					echo "<input class='form-control' type='text' placeholder='Adresse Email' name='mail' value =''>";
				}
				?>
			</div>
			<div class='form-group col-md-12'>
				<div class="form-row">
					<div class="form-check form-check-inline">
						<label class="form-check-label">Role: &ensp;</label>
						<input class="form-check-input" type="radio" name="role" <?php if($UserSelected && $UserSelected["TypeCompte"] == 1) echo " checked " ?> id="Acheteur" value="Acheteur">
						<label class="form-check-label" for="Acheteur">Acheteur</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="role" <?php if($UserSelected && $UserSelected["TypeCompte"] == 2) echo " checked " ?> id="Vendeur" value="Vendeur">
						<label class="form-check-label" for="Vendeur">Vendeur</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="role" <?php if($UserSelected && $UserSelected["TypeCompte"] == 3) echo " checked " ?> id="Administrateur" value="Administrateur">
						<label class="form-check-label" for="Administrateur">Administrateur</label>
					</div>
				</div>
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