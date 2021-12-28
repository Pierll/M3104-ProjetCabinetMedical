<html>
	<html lang="fr">
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
		<?php require 'requires/require_login.php'; ?> 

		<nav>
			<ul>

		    	<li><img class="logo" alt="logo du site" src="img/icone_menu.png"></li>
				<li><h1>Cabinet Medical</h1></li>
				<li><a class="selected" href="index.php"> Accueil</a></li>
				<li><a href = "./usager.php"> Gestion Usagers </a></li>
				<li><a href = "./medecin.php"> Gestion Médecins </a></li>
				<li><a href = "./consultation.php"> Consultation </a></li>
			</ul>
		</nav>
		
		<h1>Bienvenue sur notre site</h1><br/><br/>
		<?php require 'requires/require_decobutton.php'; ?>
	</body>
</html>
