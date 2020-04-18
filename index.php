<?php
	require("functions.php");
	
	$logged = false;
	$user = null;
	
	$page = blindage(isset($_GET["page"])? $_GET["page"] : "accueil");
	
	$token = blindage(isset($_COOKIE["token"])? $_COOKIE["token"] :"");
	list($logged , $user, $erreur) = userLogged($_DATABASE, $token);
?>


<?php include("./" . $page . ".php"); ?>
