<?php
	$erreur = ""; 
	$sql = "DELETE FROM `logintoken` WHERE `Token` = '" . blindage(isset($_SESSION["token"])? $_SESSION["token"] :"") . "' OR `Token` = '". blindage(isset($_COOKIE["token"])? $_COOKIE["token"] :"") ."'";
	
	list($_, $erreur) = SQLquery($_DATABASE, $sql, $erreur);
	if ($_)
	{
		//https://www.geeksforgeeks.org/remove-a-cookie-using-php/
		setcookie("token", "", time() - 3600); 
		unset($_SESSION["token"]);
		redirect('./?page=accueil');
	}
	else
	{
		echo "erreur : " . $erreur;
		// header("Location: ./index", true, 301);
		// exit();
	}
?>