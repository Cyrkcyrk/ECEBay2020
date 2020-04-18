<div id="header" class="header">
	<div class='row'>
		<div class='logo col-md-3'>
			<a href="./?page=accueil">
				<img src="./img/logo.png" height="60" alt="logo">
			</a>
		</div>
		<div class="col-md-6 d-none d-lg-block">
			<div class="search-bar text-center">
				<form action='./?page=categories&search=1' method='post'>
					<input type="search" class="inline" name="searchQuestion" aria-label="Rechercher sur le site">
					<button type="sumbit" name="valider" value="valider">Rechercher</button>
				</form>
			</div>
		</div>
		<div class="col-md-3">
			<div class="Compte float-right ">
				<?php
					if($logged)
					{
						echo "Bonjour " . $user['Prenom'] . "<br>";
						// echo "<a href='./?page=account'>Gerer mon compte</a><br>";
						echo "<a href='./?page=panier'>Mon panier</a><br>";
						echo "<a href='./?page=offres'>Mes offres</a>";
					}
					else
					{
						echo "<a href='./?page=login'>Connexion</a> ou <a href='./?page=register'>Creer un compte</a> <br>";
						// echo "<a href='./?page=Panier.html'>Votre panier</a>";
					}
				?>
			</div>
		</div>
	</div>
</div>