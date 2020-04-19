<?php
	require("functions.php");
	
	$logged = false;
	$user = null;
	
	$page = blindage(isset($_GET["page"])? $_GET["page"] : "accueil");
	$erreur = "";
	session_start();
	list($logged , $user, $erreur) = userLogged($_DATABASE, $erreur);
?>


<?php include("./" . $page . ".php"); ?>
