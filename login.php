<?php

session_start();
$login = "user";
$password = "root";
echo "<h1>Veuillez vous connecter (login= user:root)</h1>";
echo "<form method=\"post\">
			Login<input type=text name=\"login\" required><br/>
			Password<input type=\"password\" name=\"password\" required><br/>
				<input type=submit name=\"btn\" value=\"Valider\">
			</form>
			";
if (isset($_POST["btn"])) {
	if (($_POST["login"] == $login) && ($_POST["password"] == $password)) {
		echo 'Bienvenue !';
		$_SESSION["user"] = 1;
		header("location: index.php"); //redirige vers la page d'accueil
	} else {
		echo 'Veuillez rentrer un mdp valide';
	}
} 
?>
