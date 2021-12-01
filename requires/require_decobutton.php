<?php
	echo "<form method=\"post\">
	<input type=submit name=\"btn\" value=\"Deconnection\">
	</form>";
	if (isset($_POST["btn"])) {
		/* session_destroy(); */
		unset($_SESSION["user"]);
		header("location: login.php");
	}
?>
