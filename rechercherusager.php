<html>
	<html lang="fr">
	<head>
		<meta charset="UTF-8">
	</head>
	<body>
		<?php 
			$nom="";
			$prenom="";

			if (isset($_POST["nom"])) {
				$nom = $_POST["nom"];
			}
			if (isset($_POST["prenom"])) {
				$prenom = $_POST["prenom"];
			}
		?>
		
		<form method="post">
			<p>Saisir les infos du contact à rechercher : </p>
			Nom <input type="text" name="nom" value="<?php echo $nom; ?>" /><br />
			Prénom <input type="text" name="prenom" value="<?php echo $prenom; ?>" /><br />
			<p><input type="reset" value="Vider">
			<input type="submit" name="Rechercher" value="Rechercher"></p>
		</form>
		
		<?php
			if (isset($_POST['Rechercher'])) {
				if ($nom == "") {
					$nom = "%";
				}
				if ($prenom == "") {
					$prenom = "%";
				}
			}
			$server = "localhost";
			$login = "root";
			$mdp = "password";
			$db = "CabinetMedical";
			
			try {
				$PDO = new PDO("mysql:host=$server;dbname=$db", $login, $mdp);
			}
			catch (Exception $e) {
				die('Erreur : ' . $e->getMessage());
			}
			
			// prepare
			$res = $PDO->prepare('SELECT civilite, nom, prenom, adresse, dateNaissance, lieuNaissance, numSecu 
								FROM usager 
								WHERE nom LIKE :nom 
								AND prenom LIKE :prenom');
			//execute
			$res->execute(array('nom' => $nom,
								'prenom' => $prenom));
			
			$result = $res->fetchAll();
			if (empty($result)) {
				echo '<b>Pas d usagers trouves<b>';
			} else {			
				echo '<table>';
				foreach ($result as $r) {
					if ($r[0] == 0) {
						$r[0] = "Madame";
					} else {
						$r[0] = "Monsieur";
					}
					echo 'Usagers :';
					echo '<tr>';
					for ($i = 0; $i < sizeof($r)-(sizeof($r)/2); $i++) { // le sizeof est 2x trop grand (???) donc je le diminue de moitie 
						echo '<td>'.$r[$i].'</td>';
					}
					echo '</tr>';
					print("\n");
				}
				echo '</table>';
			}
		?>  
	</body>
</html>