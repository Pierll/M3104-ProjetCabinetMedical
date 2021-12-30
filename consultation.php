<html>
	<html lang="fr">
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<title>Consultations</title>
	</head>
	<body>
		<?php require 'requires/require_login.php'; ?> 
		<?php include 'requires/require_db.php'; ?>
		<?php require 'requires/require_menu_nav.php'; ?>
		
		<h2>Liste des consultations</h2>
		<form method="post">
			<?php // liste deroulante des medecins 
				$medecins = $linkpdo->prepare("SELECT Id_Medecin, CONCAT(Nom, ' ', Prenom) AS NomPrenom FROM Medecin");
				$medecins->execute();
				$result_medecins = $medecins->fetchAll(PDO::FETCH_ASSOC); //pour ne avoir de doublons
				echo "Medecin :<br/>";
				echo "<select name=\"idmedecin\">";
				foreach ($result_medecins as $r) {
					//print_r($r);



					echo '<option value='.$r['Id_Medecin'];
					echo '>'.$r['NomPrenom'].'</option>';
					
					echo '<br/>';
				}
				echo "</select>";
				?>
				<input type="submit" name="btn_rechercher" value="Rechercher">
				<input type="submit" name="btn_affichertout" value="Afficher Tout">
		</form>
		
		<?php 

		if (isset($_POST['btn_affichertout'])) $_POST['btn_rechercher'] = 1;

		if (isset($_POST['btn_rechercher'])) {
			if (isset($_POST['btn_affichertout'])) {
				$requeteConsultation = $linkpdo->prepare("SELECT CONCAT(Usager.Nom, ' ', Usager.Prenom) AS NomPrenomUsager, CONCAT(Medecin.Nom, ' ', Medecin.Prenom) AS NomPrenomMedecin, Consultation.DateC, Consultation.Duree, Consultation.Id_Consultation FROM Medecin, Usager, Consultation WHERE Consultation.Id_Usager = Usager.Id_Usager AND Consultation.Id_Medecin = Medecin.Id_Medecin ORDER BY Consultation.DateC DESC");
					$requeteConsultation->execute();
			} else {
				$requeteConsultation = $linkpdo->prepare("SELECT CONCAT(Usager.Nom, ' ', Usager.Prenom) AS NomPrenomUsager, CONCAT(Medecin.Nom, ' ', Medecin.Prenom) AS NomPrenomMedecin, Consultation.DateC, Consultation.Duree, Consultation.Id_Consultation FROM Medecin, Usager, Consultation WHERE Consultation.Id_Usager = Usager.Id_Usager AND Consultation.Id_Medecin = Medecin.Id_Medecin AND Medecin.Id_Medecin = ? ORDER BY Consultation.DateC DESC");
					$requeteConsultation->execute(array($_POST['idmedecin']));
			}

			
			$result = $requeteConsultation->fetchAll(PDO::FETCH_ASSOC);
		if (empty($result)) { 
				print("<b>Pas de consultations trouvés ! </b><br/>");
		} else { //affiche le résultat dans un tableau			
				echo '<table>';
				echo '<b><tr><th>Usager</th><th>Médecin</th><th>Date et Heure (j/m/a h:m)</th><th>Duree (h:m)</th></tr></b>';
		        foreach ($result as $r) {
					echo '<tr>';
					echo '<td>'.$r['NomPrenomUsager'].'</td>';
					echo '<td>'.$r['NomPrenomMedecin'].'</td>';
					echo '<td>'.date('d/m/Y H:i', $r['DateC']).'</td>';
					echo '<td>'.date('H:i', $r['Duree']).'</td>';
					/* Génère un bouton supprimer consultation*/
						echo "<td><form method=\"post\"> 
		<input type=\"submit\" name=\"btn_supprimer\" value=\"Supprimer\" >
		<input type=\"hidden\" name=\"id_consultation\" value=\"".$r['Id_Consultation']."\" >
	</form></td>";
					/* Génère un bouton modifier consultation*/
						echo "<td><form method=\"post\" action=\"modifierconsultation.php\"> 
		<input type=\"submit\" name=\"btn_modifier\" value=\"Modifier\" >
		<input type=\"hidden\" name=\"id_consultation\" value=\"".$r['Id_Consultation']."\" >
	</form></td>";
					echo '</tr>';
				}
				echo '</table>';
		}

		if (isset($_POST["btn_supprimer"])) {
			$suppressionConsultation = $linkpdo->prepare('DELETE FROM Consultation WHERE Id_Consultation = ?');
			try {
					$suppressionConsultation->execute(array($_POST["id_consultation"]));
			} catch (PDOException $e) {
					print $e;
					die('Erreur');
			}
			echo '<b> Succès de la suppression </b>';
			header("location: consultation.php"); //rafraichie la page
		}
	}
		?>
		<i>Pour ajouter des consultations, utilisez la page "Gestion Usagers"</i>
	</body>
</html>
