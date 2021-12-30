<?php
	echo "<form method=\"post\">
	<input type=submit name=\"btn_deconnexion\" value=\"Deconnection\">
	</form>";
	if (isset($_POST["btn_deconnexion"])) {
		/* session_destroy(); */
		unset($_SESSION["user"]);
		header("location: login.php");
	}
?>
