<div class="container">
	<?php
		echo "	<h2>Boutique de ". $selectedUser["Prenom"] ." ". $selectedUser["Nom"] ."</h2>";
		echo "	<div class='row'>";
		
			if($items)
			{
				forEach($items as $i)
				{
					
					echo '		<div class="col-lg-4 col-md-6 mb-4">' ."\n";
					echo '			<div class="card h-100">'."\n";
					echo '				<a href="?page=item&item='. $i["ID"] .'"><img class="card-img-top" src="'. $i["image"] .'" alt=""></a>'."\n";
					echo '				<div class="h-100">&ensp;</div>'."\n";
					echo '				<div class="card-body align-items-end">'."\n";
					echo '					<h4 class="card-title">'."\n";
					echo '						<a href="?page=item&item='. $i["ID"] .'">'. $i["Nom"] .'</a>'."\n";
					echo '					</h4>'."\n";
					if($i["ModeVente"]=="0")
						echo '					<h5>Prix d&apos;achat&nbsp;'. $i["PrixVenteDirect"] .'€</h5>'."\n";
					if($i["ModeVente"]=="1")
					{
						if($i["PrixVenteDirect"]>"0")
							echo '					<h5>Prix d&apos;achat à&nbsp;'. $i["PrixVenteDirect"] .'€</h5>'."\n";
						echo '					<h5>Début des enchères à&nbsp;'. $i["PrixDepart"] .'€</h5>'."\n";

					}
					if($i["ModeVente"]=="2")
					{
						if($i["PrixVenteDirect"]>"0")
							echo '					<h5>Prix d&apos;achat à&nbsp;'. $i["PrixVenteDirect"] .'€</h5>'."\n";
						echo '					<h5>Prix de départ&nbsp;'. $i["PrixDepart"] .'€</h5>'."\n";
						echo '<p>Fin de l&apos;enchère le&nbsp'.date("d-m-Y", strtotime("+7 days", $i["dateMiseEnLigne"]))."</p>\n";

					}
					
					echo '					<p class="card-text">'. $i["DescriptionQ"] .'</p>'."\n";
					echo '				</div>'."\n";
					echo '			</div>'."\n";
					echo '		</div>'."\n";
				}
			}
			else
			{
				echo "		Ce vendeur n'a aucun items disponibles pour le moment.<br>";
			}
		
		echo "</div>";
	?>	
</div>

<?php include("./template/_bot.php"); ?>





