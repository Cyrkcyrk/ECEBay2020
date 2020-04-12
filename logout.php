<?php
	require("functions.php");
	
	$token = isset($_COOKIE["token"])? $_COOKIE["token"] :"";
	
	
	if($token != "")
	{
		$sql = "DELETE FROM `logintoken` WHERE `Token` = '" . $token . "'";
		
		$erreur = "";
		if (SQLquery($_DATABASE, $sql, $erreur))
		{
			//https://www.geeksforgeeks.org/remove-a-cookie-using-php/
			setcookie("token", "", time() - 3600); 
			header("Location: ./index", true, 301);
			exit();
			echo "logout success";
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
		header("Location: ./index", true, 301);
		exit();
	}
?>