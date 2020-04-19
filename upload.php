<?php
	require("functions.php");
	$logged = false;
	$user = null;
	
	$page = isset($_GET["page"])? $_GET["page"] : "accueil";
	
	$token = isset($_COOKIE["token"])? $_COOKIE["token"] :"";
	list($logged , $user, $erreur) = userLogged($_DATABASE, $token);
	
	if($logged)
	{
		$targetDir = './uploads/' . $user['ID'] ."/";
		
		if (!file_exists($targetDir)) {
			mkdir($targetDir, 0777, true);
		}
		
		if (!empty($_FILES)) {
			
			$oldFileName  = $_FILES['file']['name'];
			$oldFileName  = str_replace("'", "", $oldFileName );
			$oldFileName  = str_replace('"', "", $oldFileName);
			$oldFileName  = str_replace('\\', "", $oldFileName);
			$oldFileName  = str_replace('%', "", $oldFileName);
			// $oldFileName  = str_replace(' ', "", $oldFileName);
			$oldFileName  = preg_replace('/\s*/', "", $oldFileName);
			$oldFileName  = str_replace('_', "", $oldFileName);
			$oldFileName  = str_replace('<', "", $oldFileName);
			
			$fileName = "item" . tokenGenerator() . "_" . time() . '_' . $oldFileName;
			$targetFile = $targetDir . $fileName;
			move_uploaded_file($_FILES['file']['tmp_name'],$targetFile);
			
			
			$success_message = array( 
				'success' => 200,
				'filename' => $fileName,
			);
			echo json_encode($success_message);
		}
	}
?>