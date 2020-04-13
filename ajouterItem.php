<?php
	$erreur = ""; 
	$Nom =  isset($_POST["Nom"])? $_POST["Nom"] :"";
	$DescriptionQ =  isset($_POST["DescriptionQ"])? $_POST["DescriptionQ"] :"";
	$DescriptionD =  isset($_POST["DescriptionD"])? $_POST["DescriptionD"] :"";
	$categorie =  isset($_POST["categorie"])? $_POST["categorie"] :"";
	
	$modeVente =  isset($_POST["modeVente"])? $_POST["modeVente"] :"";
	$checkboxVenteDirecte =  isset($_POST["checkboxVenteDirecte"])? $_POST["checkboxVenteDirecte"] :"";
	$prixDepart =  isset($_POST["prixDepart"])? $_POST["prixDepart"] :"";
	$prixDirect =  isset($_POST["prixDirect"])? $_POST["prixDirect"] :"";
	$valider =  isset($_POST["valider"])? $_POST["valider"] :"";
	
	if($logged)
	{
		if($valider != "")
		{
			if($Nom == "") {
				$erreur .= "Nom incomplete <br>";
			} 
			if($DescriptionQ == "") {
				$erreur .= "DescriptionQ incomplet <br>";
			} 
			if($DescriptionD == "") {
				$erreur .= "DescriptionD incomplet <br>";
			} 
			if($categorie == "") {
				$erreur .= "categorie incomplet <br>";
			}
			if($modeVente == "") {
				$erreur .= "Veuillez indiquer un mode de vente svp. <br>";
			}
			if($modeVente == "offre" || $modeVente == "encheres") {
				if($prixDepart == "")
					$erreur .= "Veuillez indiquer un prix de départ svp.<br>";
				else if($prixDepart <= 0)
					$erreur .= "Veuillez indiquer un prix de départ supérieur à 0.<br>";
			}
			if($modeVente == "directe" || $checkboxVenteDirecte == "checkboxVenteDirecte") {
				if($prixDirect == "")
					$erreur .= "Veuillez indiquer un prix de vente directe svp.<br>";
				else if($prixDirect <= 0)
					$erreur .= "Veuillez indiquer un prix de vente directe supérieur à 0.<br>";
			}
			
			
			
			if($erreur == "")
			{
				$_tmpPrixDirect = $prixDirect;
				$_tmpPrixDepart = $prixDepart;
				if($checkboxVenteDirecte != "" || $modeVente == "directe")
				{
					$_venteDirecte = 1;
				}
				else
				{
					$_venteDirecte = 0;
					$_tmpPrixDirect = 0;
				}
				
				if($modeVente == "directe") $_modeDeVente = 0;
				else if($modeVente == "encheres") $_modeDeVente = 1;
				else if($modeVente == "offre") $_modeDeVente = 2;
				
				echo $modeVente;
				
				$sql = "INSERT INTO `item`(`OwnerID`, `Nom`, `DescriptionQualites`, `DescriptionDefauts`, `Categorie`, `EtatVente`, `ModeVente`, `PrixDepart`, `VenteDirect`, `PrixVenteDirect`, `dateMiseEnLigne`) VALUES ('". $user["ID"] ."', '". $Nom ."', '". $DescriptionQ ."', '". $DescriptionD ."', '". $categorie ."', ". 1 .", ". $_modeDeVente .", '". $_tmpPrixDepart ."', '". $_venteDirecte ."', '". $_tmpPrixDirect ."', '". time() ."')";
				list ($_, $erreur) = SQLquery($_DATABASE, $sql, $erreur);
				redirect('./?page=vente');
			}
		}
	}
	else
	{
		redirect('./?page=login');
	}
?>

<style>
	#formulaire{
		float:left;
	}
	#erreur{
		float:left;
	}
</style>



<script>
	function EnchereOffre() {
		document.getElementById('spanVenteDirecteCheckbox').style = 'display:1;';
		document.getElementById('trPrixDepart').style = 'display:1;';
		if(document.getElementById('venteCheckboxDirecte').checked)
		{
			document.getElementById('trPrixVenteDirecte').style = 'display:1;';
		}
		else
		{
			document.getElementById('trPrixVenteDirecte').style = 'display:none;';
		}
	}

	function VenteDirecte () {
		document.getElementById('spanVenteDirecteCheckbox').style = 'display:none;';
		document.getElementById('venteCheckboxDirecte').checked  = false;
		document.getElementById('trPrixVenteDirecte').style = 'display:1;';
		document.getElementById('trPrixDepart').style = 'display:none;';
		
	}
	
	function changeCheckboxVenteDirecte () {
		if(document.getElementById('venteCheckboxDirecte').checked)
		{
			document.getElementById('trPrixVenteDirecte').style = 'display:1;';
		}
		else
		{
			document.getElementById('trPrixVenteDirecte').style = 'display:none;';
		}
	}
	
</script>


<form action="./?page=ajouterItem" method="post">
	<div id="identification">
		<div id="formulaire">
			<table>
				<tr>
					<td>Nom:</td>
					<td> <?php echo "<input type='text' name='Nom' value ='" . $Nom ."'>";?></td>
				</tr>
				<tr>
					<td>Description des qualitées:</td>
					<td> <?php echo "<textarea name='DescriptionQ' >" . $DescriptionQ ."</textarea>";?></td>
				</tr>
				<tr>
					<td>Description des défauts:</td>
					<td> <?php echo "<textarea name='DescriptionD' >" . $DescriptionD ."</textarea>";?></td>
				</tr>
				<tr>
					<td>Catégorie:</td>
					<td>
						<input type="radio" name="categorie" value="ferraille" <?php if($categorie == "ferraille") echo "checked";?>> 
						<label for="ferraille">Ferraille ou trésor</label>
						
						<input type="radio" name="categorie" value="musee" <?php if($categorie == "musee") echo "checked";?>> 
						<label for="musee">Bon pour le musée</label>
						
						<input type="radio" name="categorie" value="VIP" <?php if($categorie == "VIP") echo "checked";?>> 
						<label for="VIP">Accessoire VIP</label>
					</td>
				</tr>
				<tr>
					<td>Mode de vente:</td>
					
					<td>
						<input type="radio" id="venteRadioEncheres" onChange="EnchereOffre();" name="modeVente" value="encheres" <?php if($modeVente == "encheres") echo "checked";?>> 
						<label for="encheres">Enchères</label>
						
						<input type="radio" id="venteRadioOffre" onChange="EnchereOffre();"name="modeVente" value="offre" <?php if($modeVente == "offre") echo "checked";?>> 
						<label for="offre">Meilleur Offre</label>
						
						<input type="radio" id="venteRadioDirecte" onChange="VenteDirecte();"name="modeVente" value="directe" <?php if($modeVente == "directe") echo "checked";?>> 
						<label for="directe">Vente directe</label>
						
						<span id="spanVenteDirecteCheckbox" <?php if($modeVente == "directe") echo "style='display:none'"?>>
							<br>
							<input type="checkbox" onclick="changeCheckboxVenteDirecte();" id="venteCheckboxDirecte" name="checkboxVenteDirecte" value="checkboxVenteDirecte" <?php if($checkboxVenteDirecte == "checkboxVenteDirecte") echo "checked";?>> 
							<label for="directe">Proposer aussi en vente directe</label>
						</span>
						
					</td>
				</tr>
				<tr id="trPrixVenteDirecte" <?php if($modeVente != "directe" && $checkboxVenteDirecte != "checkboxVenteDirecte") echo "style='display:none'"?>>
					<td>Prix vente directe:</td>
					<td><?php echo "<input type='number' step='0.01' name='prixDirect' value ='" . $prixDirect ."'>";?></td>
				</tr>
				<tr id="trPrixDepart" <?php if($modeVente == "directe") echo "style='display:none'"?>>
					<td>Prix de départ:</td>
					<td><?php echo "<input type='number' step='0.01' name='prixDepart' value ='" . $prixDepart ."'>";?></td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:center;"><input type="submit" value="Valider" name="valider"></td>
				<tr>
			</table>
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

