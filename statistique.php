<!DOCTYPE HTML>
<html>
	<html lang="fr">
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<title>Statistiques</title>
	</head>
	<body>
		<?php include 'requires/require_login.php'; ?>
		<?php include 'requires/require_db.php'; ?>
		<?php include 'requires/require_menu_nav.php'; ?>
		<h2>Statistiques des usagers par âges</h2>
		<?php
			$res = $linkpdo->prepare('SELECT civilite, dateNaissance
				FROM Usager');
				
			$res->execute(array());

			$result = $res->fetchAll(PDO::FETCH_ASSOC);
			
			echo '<table>';
				echo "<b><tr><th>Tranche d'âge</th><th>Nb Hommes</th><th>Nb Femmes</th></b>";
			$nbH1 = 0;
			$nbH2 = 0;
			$nbH3 = 0;
			$nbF1 = 0;
			$nbF2 = 0;
			$nbF3 = 0;
			foreach ($result as $r) {
				$timestamp = time() - $r['dateNaissance']; //Age en secondes
				if ($r['civilite'] == 1) { // Homme
					if ($timestamp < 788400000) { // Age < 25 ans
						$nbH1 += 1;
					} else if ($timestamp < 1576800000) { // Age < 50
						$nbH2 += 1;
					} else { // Age > 50
						$nbH3 += 1;
					}
				} else { // Femme
					if ($timestamp < 788400000) { // Age < 25 ans
						$nbF1 += 1;
					} else if ($timestamp < 1576800000) { // Age < 50
						$nbF2 += 1;
					} else { // Age > 50
						$nbF3 += 1;
					}
				}
			}
			echo '<tr>';
			echo '<td>'."Moins de 25 ans".'</td>';
			echo '<td>'.$nbH1.'</td>';
			echo '<td>'.$nbF1.'</td>';
			echo '</tr>';
			print("\n");
			echo '<tr>';
			echo '<td>'."Entre 25 et 50 ans".'</td>';
			echo '<td>'.$nbH2.'</td>';
			echo '<td>'.$nbF2.'</td>';
			echo '</tr>';
			print("\n");
			echo '<tr>';
			echo '<td>'."Plus de 50 ans".'</td>';
			echo '<td>'.$nbH3.'</td>';
			echo '<td>'.$nbF3.'</td>';
			echo '</tr>';
			print("\n");
			echo '</table>';
			
		?>
		<h2>Nombre d'heures par médecin</h2>
		<?php
			
			$resNbHeures = $linkpdo->prepare('SELECT Medecin.Civilite, Medecin.Nom, Medecin.Prenom , SUM(Consultation.Duree/3600) AS NbrHeures FROM Medecin, Consultation WHERE Consultation.Id_Medecin = Medecin.Id_Medecin GROUP BY Consultation.Id_Medecin ORDER BY Consultation.Duree DESC');
				
			$resNbHeures->execute();

			$result = $resNbHeures->fetchAll(PDO::FETCH_ASSOC);
			
			echo '<table>';
			echo '<b><tr><th>Civilité</th><th>Nom</th><th>Prénom</th><th>Heures Totales</th></tr>';
			foreach ($result as $r) {
				echo '<tr>';
				if ($r['Civilite'] == 0) {
					$r['Civilite'] = "Madame";
				} else {
					$r['Civilite'] = "Monsieur";
				}
				echo '<td>'.$r['Civilite'].'</td>';
				echo '<td>'.$r['Nom'].'</td>';
				echo '<td>'.$r['Prenom'].'</td>';
				echo '<td>'.$r['NbrHeures'].'</td>';
				echo '</tr>';
				print("\n");
			}
			echo '</table>';
		?>
	</body>
</html>