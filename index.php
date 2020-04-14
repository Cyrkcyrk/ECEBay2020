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
	<script>
		function dynamicHeigh() {
			
			//https://stackoverflow.com/questions/1145850/how-to-get-height-of-entire-document-with-javascript
			var body = document.body;
			var html = document.documentElement;

			var height = Math.max( document.body.scrollHeight, 
								   document.body.offsetHeight, 
								   document.documentElement.clientHeight, 
								   document.documentElement.scrollHeight, 
								   document.documentElement.offsetHeight 
								 );
			
			var heightContent = height - ( document.getElementById("header").offsetHeight + document.getElementById("menu").offsetHeight + (document.getElementById("footer").offsetHeight*2));
			
			
		document.getElementById("content").style = "height : " + heightContent + "px;";
		}
		
		window.onresize = dynamicHeigh();
		
	</script>
</head>

<body onload="dynamicHeigh();">
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
	<div id="menu" class="menu">
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
				<li class="huuum"><a href='./?page=vente'>Vendre</a></li>
				<li class="huuum"><a href='./?page=account'>Mon compte</a></li>
				<li class="huuum">Admin</li>
			</ul>
		</nav>
	</div>
	<div id="content" class="content">
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