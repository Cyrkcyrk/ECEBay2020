<?php
	$token = isset($_COOKIE["token"])? $_COOKIE["token"] :"";
	
	
	if($token != "")
	{
		$sql = "DELETE FROM `logintoken` WHERE `Token` = '" . $token . "'";
		
		$erreur = "";
		list($_, $erreur) = SQLquery($_DATABASE, $sql, $erreur);
		if ($_)
		{
			//https://www.geeksforgeeks.org/remove-a-cookie-using-php/
			setcookie("token", "", time() - 3600); 
			redirect('./?page=accueil');
		}
		else
		{
			echo "erreur : " . $erreur;
			// header("Location: ./index", true, 301);
			// exit();
		}
	
	}
	else
	{
		redirect('./?page=accueil');
	}
?>