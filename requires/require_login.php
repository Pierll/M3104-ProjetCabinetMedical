<?php
	session_start(); 
	if (!isset($_SESSION["user"])) { /* Verifie si l'utilisateur est connecte, sinon le rediriger vers la page de login*/
	  $_SESSION["last_page"] = $_SERVER['PHP_SELF'];
	  header("location: login.php");
	  exit();
	}
	$_SESSION["last_page"] = $_SERVER['PHP_SELF']; /* variable qui prend le nom de la page actuelle */
?>
