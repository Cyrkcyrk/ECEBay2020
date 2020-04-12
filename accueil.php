<?php
	$erreur = ""; 
	
	$logged = false;
	$user = null;
	
	
	$token = isset($_COOKIE["token"])? $_COOKIE["token"] :"";
	list($logged , $user, $erreur) = userLogged($_DATABASE, $token);

	if($logged)
	{
		echo "Bienvenu, " . $user['Prenom'] . " " . $user['Nom'] . " :D <br>";
		echo "<a href='./?page=account'>Gerer mon compte</a><br>";
		echo "<a href='./?page=logout&_=" . $token ."'>Se deconnecter</a><br>";
	}
	else
	{
		echo "<a href='./?page=register'>Creer un compte</a><br>";
		echo "<a href='./?page=login'>se connecter</a>";
	}
?>