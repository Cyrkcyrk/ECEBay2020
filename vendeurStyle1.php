<div class="container">
	<?php
		echo "	<h2>Boutique de ". $selectedUser["Prenom"] ." ". $selectedUser["Nom"] ."</h2>";
		echo "	<div class='row'>";
		if($items)
		{
			forEach($items as $i)
			{
				echo "	<div class='py-2'>\n";
				echo "		<div class='card'>\n";
				echo "			<div class='row '>\n";
				echo "				<div class='col-md-2'>\n";
				echo "					<img src='". $i["image"] ."' class='w-100'>\n";
				echo "				</div>\n";
				echo "				<div class='col-md-7'>\n";
				echo "					<div class='card-block px-3'>\n";
				echo "						<h4 class='card-title'><a href=?page=item&item=". $i["ID"] .">" . $i["Nom"] ."</a></h4>\n";
				echo "						<p class='card-text'>". $i["DescriptionQ"] ."</h4>\n";
				echo "						<p class='card-text'>". $i["DescriptionD"] ."</h4>\n";
				if($i["ModeVente"] == 1)
					echo "						<p class='card-text'>Fin de l'enchère le ". date("d-m-Y", strtotime("+7 days", $i["dateMiseEnLigne"])) ."</h4>\n";
				echo "					</div>\n";
				echo "				</div>\n";
				echo "				<div class='col-md-3'>\n";
				echo "					<div class='card-block px-3'>\n";
				echo "						<div class='text-right'>\n";
				if($i["VenteDirect"] == 1)
					echo "							<p class='card-text'>Prix vente Directe ". $i["PrixVenteDirect"] ." € </p>\n";
				if($i["ModeVente"] == 1)
					echo "							<p class='card-text'>Prix enchère ". $i["PrixDepart"] ." € </p>\n";
				else if($i["ModeVente"] == 2)
					echo "							<p class='card-text'>Prix offre ~". $i["PrixDepart"] ." € </p>\n";
				echo "						</div>\n";
				echo "					</div>\n";
				echo "				</div>\n";
				echo "			</div>\n";
				echo "			<div class='text-right'>\n";
				echo "				<a href='./?page=supprimerItem&ID=". $i["ID"] ."' class='card-link'>Supprimer item</a>\n";
				echo "			</div>\n";
				echo "		</div>\n";
				echo "	</div>\n";
			}
		}
		else 
		{
			echo "		Ce vendeur n'a aucun items disponibles pour le moment.<br>";
		}
	?>
</div>