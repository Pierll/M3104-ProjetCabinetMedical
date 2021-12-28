<html>
	<html lang="fr">
	<head>
		<meta charset="UTF-8">
	</head>
	<body>
		<?php include 'requires/require_login.php'; ?>
		<?php include 'requires/require_db.php'; ?>
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
			
			// prepare
			$res = $linkpdo->prepare('SELECT civilite, nom, prenom, adresse, dateNaissance, lieuNaissance, numSecu 
								FROM usager 
								WHERE nom LIKE :nom 
								AND prenom LIKE :prenom');
			//execute
			$res->execute(array('nom' => $nom,
								'prenom' => $prenom));
			
			$result = $res->fetchAll(PDO::FETCH_ASSOC);
			if (empty($result)) {
				echo '<b>Pas de medecins trouves<b>';
			} else {			
				echo '<table>';
                foreach ($result as $r) {
                    //print_r($r);
					echo '<tr>';
					if ($r['civilite'] == 0) {
						$r['civilite'] = "Madame";
					} else {
						$r['civilite'] = "Monsieur";
					}
					echo '<td>'.$r['civilite'].'</td>';
					echo '<td>'.$r['nom'].'</td>';
					echo '<td>'.$r['prenom'].'</td>';
					echo '<td>'.$r['adresse'].'</td>';
					echo '<td>'.date('d/m/Y', $r['dateNaissance']).'</td>';
					echo '<td>'.$r['lieuNaissance'].'</td>';
					echo '<td>'.$r['numSecu'].'</td>';
					echo '</tr>';
					print("\n");
                }
				echo '</table>';
			}
		?>    
	</body>
</html>