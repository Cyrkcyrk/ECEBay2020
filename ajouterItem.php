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

$images =  isset($_POST["images"])? $_POST["images"] :"";
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

			if($modeVente == "directe") 
			{
				$_modeDeVente = 0;
				$_tmpPrixDepart = 0;
			}
			else if($modeVente == "encheres") $_modeDeVente = 1;
			else if($modeVente == "offre") $_modeDeVente = 2;

			$dateMiseEnLigne = time();

			$sql = "INSERT INTO `item`(`OwnerID`, `Nom`, `DescriptionQualites`, `DescriptionDefauts`, `Categorie`, `EtatVente`, `ModeVente`, `PrixDepart`, `VenteDirect`, `PrixVenteDirect`, `dateMiseEnLigne`) VALUES ('". $user["ID"] ."', '". $Nom ."', '". $DescriptionQ ."', '". $DescriptionD ."', '". $categorie ."', ". 1 .", ". $_modeDeVente .", '". $_tmpPrixDepart ."', '". $_venteDirecte ."', '". $_tmpPrixDirect ."', '". $dateMiseEnLigne ."')";
			list ($_, $erreur) = SQLquery($_DATABASE, $sql, $erreur);
			if($_)

				$sql = "SELECT * FROM `item` WHERE `OwnerID` = '" . $user["ID"] . "' AND `Nom` = '" . $Nom . "' AND `dateMiseEnLigne` = '" . $dateMiseEnLigne . "';";
			$mysqli = new mysqli($_DATABASE["host"],$_DATABASE["user"],$_DATABASE["password"],$_DATABASE["BDD"]);
			mysqli_set_charset($mysqli, "utf8");

			if ($mysqli -> connect_errno) {
				$erreur .= "Failed to connect to MySQL: " . $mysqli -> connect_error;
			}
			if ($result = $mysqli -> query($sql)) {
				if (mysqli_num_rows($result) > 0) {

					$itemID = mysqli_fetch_assoc($result)["ID"];
					$cheminAcces = "./uploads/". $user["ID"] . "/";
					$sql = "INSERT INTO `medias`(`ItemID`, `Lien`, `type`, `Ordre`) VALUES ";

					$compteur = 0;
					forEach($images as $img)
					{
						if($compteur != 0)
							$sql .= ", ";

						$sql .= "('". $itemID . "', '". $cheminAcces . $img . "', 1, ". $compteur . ")" ;
						$compteur = $compteur+1;
					}
					$sql .= ";";

					$result -> free_result();
					$mysqli -> close();

					list ($_, $erreur) = SQLquery($_DATABASE, $sql, $erreur);
					redirect('./?page=vente&SQL=' . $sql);
				}
				else
				{
					$items = false;
					$result -> free_result();
					$mysqli -> close();
				}
			}
			else
			{
				$erreur .= "Une erreur est survenue";
			}



					/*forEach($images as $img)
					{
						echo $img . "<br>\n";
					}*/
					//redirect('./?page=vente&SQL=' . $sql);
				}
			}
		}
		else
		{
			redirect('./?page=login');
		}
		?>

		<?php include("./template/_top.php"); ?>

<!--
http://www.expertphp.in/article/php-upload-multiple-file-using-dropzone-js-with-drag-and-drop-features
https://www.dropzonejs.com
-->
<link href="http://demo.expertphp.in/css/dropzone.css" rel="stylesheet">
<script src="http://demo.expertphp.in/js/dropzone.js"></script>

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
	
	
	Dropzone.options.myDropzone = {
		init: function() {
			this.on("addedfile", function(file) {
				// alert(file.name)
			});
		},
		//https://stackoverflow.com/questions/23332464/how-to-return-new-filename-to-dropzone-after-upload-is-complete-as-hidden-form-i/27143904
		accept: function(file, done) 
		{
			var re = /(?:\.([^.]+))?$/;
			var ext = re.exec(file.name)[1];
			ext = ext.toUpperCase();
			if ( ext == "JPG" || ext == "JPEG" || ext == "PNG" ||  ext == "GIF" ||  ext == "BMP") 
			{
				done();
			}else { 
				done("Please select only supported picture files."); 
			}
		},
		success: function( file, response ){
			obj = JSON.parse(response);
			 // alert(obj.filename);
			 
			 /*var _img = document.createElement("img");
			 _img.src = "./uploads/" + <?php echo "'" . $user["ID"] . "'";?> + "/" + obj.filename;
			 
			 document.getElementById("images").appendChild(_img);
			 document.getElementById("images").appendChild(document.createElement("br"));
			 dynamicHeigh()
			 setTimeout(dynamicHeigh(), 1000);
			 */
			 
			 var _input = document.createElement("input");
			 _input.type = "hidden";
			 _input.value = obj.filename;
			 _input.name = "images[]";
			 document.getElementById("form").appendChild(_input);
			 
			 
			}
		};

	</script>
	<form action="upload.php" class="dropzone" id="my-dropzone"></form>
	<div class="content2">
		<div id="images"></div>


		<div id="formulaire">
			<form action="./?page=ajouterItem" id="form" method="post">
				<div class="form-row">
					<div class='form-group col-md-10'>
						<?php echo "<input class='form-control' type='text' placeholder='Titre de l&apos;offre' name='Nom' value ='" . $Nom ."'>";?>	
					</div>
					<div class='form-group col-md-10'>
						<?php echo "<input class='form-control' rows='3' type='text' placeholder='Description des qualitées' name='DescriptionQ' value ='" . $DescriptionQ ."'>";?>
					</div>
					<div class='form-group col-md-10'>
						<?php echo "<input class='form-control' rows='3' type='text' placeholder='Description des défauts' name='DescriptionD' value ='" . $DescriptionD ."'>";?>
					</div>
					<div id="erreur" class="Fl-R">
					</div>
				</div>
				<div class="form-row">

					<div class="form-check form-check-inline">
						<label class="form-check-label">Catégorie: &ensp;</label>
						<input class="form-check-input" type="radio" name="categorie" value="ferraille" <?php if($categorie == "ferraille") echo "checked";?>> 
						<label class="form-check-label" for="ferraille">Ferraille ou trésor</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="categorie" value="musee" <?php if($categorie == "musee") echo "checked";?>> 
						<label class="form-check-label" for="musee">Bon pour le musée</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="categorie" value="VIP" <?php if($categorie == "VIP") echo "checked";?>> 
						<label class="form-check-label" for="VIP">Accessoire VIP</label>
					</div>
				</div>
				<br>
				<div class="form-row">
					<div class="form-check form-check-inline">
						<label class="form-check-label">Mode de vente: &ensp;</label>
						<input class="form-check-input" type="radio" id="venteRadioEncheres" onChange="EnchereOffre();" name="modeVente" value="encheres" <?php if($modeVente == "encheres") echo "checked";?>> 
						<label class="form-check-label" for="encheres">Enchères</label>
					</div>
					<div class="form-check form-check-inline" >
						<input class="form-check-input" type="radio" id="venteRadioOffre" onChange="EnchereOffre();"name="modeVente" value="offre" <?php if($modeVente == "offre") echo "checked";?>> 
						<label class="form-check-label" for="offre">Meilleur Offre</label>
					</div>
					<div class="form-check form-check-inline" >
						<input class="form-check-input" type="radio" id="venteRadioDirecte" onChange="VenteDirecte();"name="modeVente" value="directe" <?php if($modeVente == "directe") echo "checked";?>> 
						<label class="form-check-label" for="directe">Vente directe</label>
					</div>

				</div>
				<br>
				<div class="form-check form-check-inline" id="spanVenteDirecteCheckbox"<?php if($modeVente != "directe") echo "style='display:1'";?>>
					<input class="form-check-input" type="checkbox" onclick="changeCheckboxVenteDirecte();" id="venteCheckboxDirecte" name="checkboxVenteDirecte" value="checkboxVenteDirecte"<?php if($checkboxVenteDirecte == "checkboxVenteDirecte") echo "checked";?>>
					<label class="form-check-label" for="directe">Proposer aussi en vente directe</label>
				</div>
				<br>
				
				<div class="form-check form-check-inline" id="trPrixDepart" <?php if($modeVente == "directe") echo "style='display:none'"?>>
					<?php echo "<input type='number' step='0.01' name='prixDepart' class='form-control' placeholder='Prix de départ' value ='" . $prixDepart ."'>";?>
				</div>

				<div class="form-check form-check-inline" id="trPrixVenteDirecte" <?php if($modeVente != "directe" && $checkboxVenteDirecte != "checkboxVenteDirecte") echo "style='display:none'"?>>
					<?php echo "<input type='number' class='form-control' placeholder='Prix vente directe' step='0.01' name='prixDirect' value ='" . $prixDirect ."'>";?>
				</div>
				<br>
				<br>
				<button type="submit" class="btn btn-primary" value="Valider" name="valider">Valider</button>	
			</form>
		</div>
		<div id="erreur">
			<?php if($erreur != "")
			{
				echo $erreur;
			}
			?>
		</div>
	</div>
	<?php include("./template/_bot.php"); ?>
