<html>
	<html lang="fr">
	<head>
		<meta charset="UTF-8">
	</head>
	<body>
		<?php require 'requires/require_login.php'; ?> 

		<nav>
			<div class="logo">
				<a href="index.html">
		         <img class="img" alt="logo du site" src="img/icone_menu.png">
		      	</a>
			</div>
		
			<div class="pageTitle">
				<p class="pageTitle-text">Machine Learning</p>
			</div>
		
			<div class="nav_right">
				<a class="selected" href="index.html"> Accueil</a>
				<a href="page1.html"> Alpha GO </a>
				<a href="page2.html"> Avancées </a>
				<a href="page3.html"> Autres Applications </a>
			</div>
		</nav>
		
		<h1>Bienvenue sur notre site</h1><br/><br/>
		<h2><a href = "./usager.php"> Gestion Usagers </h2><br/><br/>
		<h2><a href = "./medecin.php"> Gestion Médecins </h2><br/><br/>
		<?php require 'requires/require_decobutton.php'; ?>
	</body>
</html>
