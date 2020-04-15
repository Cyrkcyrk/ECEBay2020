<!DOCTYPE html>
<html>
<head>
	<title>TP7: Introduction au Framework Bootstrap</title>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php
	// <script src="bootstrap/js/jquery.js"></script>
	// <script src="bootstrap/js/bootstrap.min.js"></script>
	// <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
	
	// <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
	// <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
	// <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	?>
	<link rel="stylesheet"href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script> 
	
	
	<link href="css.css" rel="stylesheet" type="text/css" />
	<script src="javascript.js" type="text/javascript"></script>
	
	<script type="text/javascript">
		$(document).ready(function(){
			$('.header').height($(window).height());
		});
	</script>
</head>
<body onload="init();">
	<div id="content">
		<nav class="navbar navbar-expand-md">
			<a class="navbar-brand" href="#">
				Logo
			</a>
			<button class="navbar-toggler navbar-dark" type="button" data-toggle="collapse" data-target="#main-navigation">
				<span class="navbar-toggler-icon">
				</span>
			</button>
			<div class="collapse navbar-collapse" id="main-navigation">
				<ul class="navbar-nav">
					<li class="nav-item">
						<a class="nav-link" href="#">
							Accueil
						</a>
					</li>
						<li class="nav-item">
						<a class="nav-link" href="#">
							A propos
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link"href="#">
							Contact
						</a>
					</li>
				</ul>
			</div>
		</nav>
		
		<header class="page-header header container-fluid">
			<div class="overlay">
			</div>
			<div class="description">
				<h1>Bienvenue à la page de votre destination!</h1>
				<p>
					Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque interdum quam odio, quis placerat ante luctus eu. Sed aliquetdolor id sapien rutrum, id vulputate quam iaculis. Suspendisse consectetur mi id libero fringilla, in pharetra sem ullamcorper.
				</p>  
				<button class="btn btn-outline-secondary btn-lg">Dites m'en plus!</button>
			</div>
		</header>
		
		<br>
		<div class="container features">
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-12">
					<h3 class="feature-title">
						Lorem ipsum
					</h3>
					<img src="img/column1.jpg" class="img-fluid">
					<p>
						Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque interdum quam odio, quis placerat ante luctus eu. Sed aliquet dolor id sapien rutrum, id vulputate quam iaculis.
					</p>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-12">
					<h3 class="feature-title">
						Lorem ipsum
					</h3>
					<img src="img/column2.jpg" class="img-fluid">
					<p>
						Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque interdum quam odio, quis placerat ante luctus eu. Sed aliquet dolor id sapien rutrum, id vulputate quam iaculis.
					</p>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-12">
					<h3 class="feature-title">Entrer en contact!</h3>
					<div class="form-group">
						<input type="text" class="form-control" placeholder="Votre nom:" name="">
					</div>
					<div class="form-group">
						<input type="email" class="form-control" placeholder="Courriel:" name="email">
					</div>
					<div class="form-group">
						<textarea class="form-control" rows="4">Vos commentaires</textarea>
					</div>
					<input type="submit" class="btn btn-secondary btn-block" value="Envoyer" name="">
				</div>
			</div>
		</div>

		<footer class="page-footer">
			<div class="container">
				<div class="row">
					<div class="col-lg-8 col-md-8 col-sm-12">
						<h6 class="text-uppercase font-weight-bold">Information additionnelle</h6>
						<p>
							Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque interdum quam odio, quis placerat ante luctus eu. Sed aliquet dolor id sapien rutrum, id vulputate quam iaculis. <a href="https://getbootstrap.com/docs/4.0/components/forms/">Bootstrap from documentation</a>
						</p>
						<p>
							Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque interdum quam odio, quis placerat ante luctus eu. Sed aliquet dolor id sapien rutrum, id vulputate quam iaculis.
						</p>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-12">
						<h6 class="text-uppercase font-weight-bold">Contact</h6>
						<p>
							37, quai de Grenelle, 75015 Paris, France <br>
							info@webDynamique.ece.fr <br>
							+33 01 02 03 04 05 <br>
							+33 01 03 02 05 04
						</p>
					</div>
				</div>
				<div class="footer-copyright text-center">
					&copy; 2019 Copyright | Droit d'auteur: webDynamique.ece.fr
				</div>
			</div>
		</footer>

	</div>
</body>
</html>