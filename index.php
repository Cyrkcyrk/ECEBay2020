<?php
	require("functions.php");
	
	$logged = false;
	$user = null;
	
	$page = isset($_GET["page"])? $_GET["page"] : "accueil";
	
	$token = isset($_COOKIE["token"])? $_COOKIE["token"] :"";
	list($logged , $user, $erreur) = userLogged($_DATABASE, $token);
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script> 
	
	<link href="css/style1.css" rel="stylesheet" type="text/css" />
</head>

<body>
	<div id="header" class="header">
		<div class="logo">
			<a href="./?page=accueil">
				<img src="void-02.jpg" width="100" height="60">
			</a>
		</div>
		<div class="text-center" id="site-search">
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
	<div id="menu" class="menu">
		<nav>
			<ul>
				<li class="deroul"><p>Catégories &ensp;</p>
					<ul class="sous">
						<li><a href="./?page=categories&cat=ferraille">Ferraille ou trésor</a></li>
						<li><a href="./?page=categories&cat=musee">Bon pour le musée</a></li>
						<li><a href="./?page=categories&cat=VIP">Accessoire VIP</a></li>
					</ul>
				</li>
				<li class="deroul"><p>Achat &ensp;</p>
					<ul class="sous">
						<li><a href="./?page=categories&type=encheres">Enchères</a></li>
						<li><a href="./?page=categories&type=directe">Achetez-le Maintenant</a></li>
						<li><a href="./?page=categories&type=offre">Meilleure Offre</a></li>
					</ul>
				</li>
				<li><a href='./?page=vente'>Vendre</a></li>
				<li><a href='./?page=account'>Mon compte</a></li>
				<li>Admin</li>
			</ul>
		</nav>
		
		
		
	</div>
	<div id="content" class="container-fluid h-100 content">
		<?php include("./" . $page . ".php"); ?>
	</div>
	<div id="footer" class="footer">
		ECEbay © all right reserved 2020
	</div>
</body>
</html>

<!-- 
Sources:
https://developer.mozilla.org/ 
!>