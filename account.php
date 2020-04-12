<?php
	require("functions.php");
	$erreur = ""; 
	$logged = false;
	$user = null;
	
	$token = isset($_COOKIE["token"])? $_COOKIE["token"] :"";
	list($logged , $user, $erreur) = userLogged($_DATABASE, $token);
	
	if($logged)
	{
		echo "Bienvenu, " . $user['Prenom'] . " " . $user['Nom'] . " :D <br>";
		echo "<a href='./logout.php?_=" . $token ."'>Se deconnecter</a><br>";
	}
	else
	{
		redirect('./loggin');
	}
?>

