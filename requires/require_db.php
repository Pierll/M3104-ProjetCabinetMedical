<?php 
			$server = "localhost";
			$login = "root";
			$mdp = "password";
			$db = "CabinetMedical";
		
			//Connexion au serveur MySQL 
	        try { 
	  	        $linkpdo = new PDO("mysql:host=$server;dbname=$db", $login, $mdp); 
		    } catch (Exception $e) { 
			    die('Erreur : ' . $e->getMessage());
			}
			$linkpdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
