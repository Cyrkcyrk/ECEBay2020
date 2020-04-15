	<div id="header" class="header">
		<div class="logo">
			<a href="./?page=accueil">
				<img src="void-02.jpg" width="100" height="60">
			</a>
		</div>
		<div class="text-center" id="site-search">
			<input type="search" class="inline" name="q" aria-label="Search through site content">
			<button>Rechercher</button>
			<div class="Compte">
				
				<?php
					if($logged)
					{
						echo "Bonjour " . $user['Prenom'] . "<br>";
						// echo "<a href='./?page=account'>Gerer mon compte</a><br>";
						echo "<a href='./?page=panier'>Mon panier</a>";
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