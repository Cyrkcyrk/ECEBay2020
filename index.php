<?php
	require("functions.php");
	
	$logged = false;
	$user = null;
	
	$page = isset($_GET["page"])? $_GET["page"] : "accueil";
	
	$token = isset($_COOKIE["token"])? $_COOKIE["token"] :"";
	list($logged , $user, $erreur) = userLogged($_DATABASE, $token);
	
	//https://stackoverflow.com/questions/13640109/how-to-prevent-browser-cache-for-php-site
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
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
	<div class="header">
		<div class="logo">
			<a href="./?page=accueil">
				<img src="void-02.jpg" width="100" height="60">
			</a>
		</div>
		<div class="text-center">
			<input type="text" name="recherche_text" >
			<input type="button" name="recherche">
			<div class="Compte">
				
				<?php
					if($logged)
					{
						echo "Bonjour " . $user['Prenom'] . "<br>";
						// echo "<a href='./?page=account'>Gerer mon compte</a><br>";
						echo "<a href='Panier.html'>Mon panier</a>";
					}
					else
					{
						echo "<a href='./?page=login'>Connexion</a> ou <a href='./?page=register'>Creer un compte</a> <br>";
						echo "<a href='./?page=Panier.html'>Votre panier</a>";
					}
				?>
				
				
			</div>
		</div>
	</div>
	<div class="menu">
		<nav>
			<ul>
				<li class="deroul"><p>Catégories &ensp;</p>
					<ul class="sous">
						<li><a href="#">Ferraille ou trésor</a></li>
						<li><a href="#">Bon pour le musée</a></li>
						<li><a href="#">Accessoire VIP</a></li>
					</ul>
				</li>
				<li class="deroul"><p>Achat &ensp;</p>
					<ul class="sous">
						<li><a href="#">Enchères</a></li>
						<li><a href="#">Achetez-le Maintenant</a></li>
						<li><a href="#">Meilleure Offre</a></li>
					</ul>
				</li>
				<li><a href='./?page=vente'>Vendre</a></li>
				<li><a href='./?page=account'>Mon compte</a></li>
				<li>Admin</li>
			</ul>
		</nav>
	</div>
	
	
	<div class="content">
		<?php include("./" . $page . ".php"); ?>
	</div>
	
	
	<div class="footer">
		ECEbay © all right reserved 2020-2020
	</div>
</body>
</html>

<!-- 
Sources:
https://developer.mozilla.org/ 
!>