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


			
		</form>
		<?php 
			/*SELECT CONCAT(Usager.Nom, ' ', Usager.Prenom) AS NomPrenomUsager, CONCAT(Medecin.Nom, ' ', Medecin.Prenom) AS NomPrenomMedecin FROM Medecin, Usager, Consultation WHERE Consultation.Id_Usager = Usager.Id_Usager AND Consultation.Id_Medecin = Medecin.Id_Medecin;*/
			$requeteConsultation = $linkpdo->prepare("SELECT CONCAT(Usager.Nom, ' ', Usager.Prenom) AS NomPrenomUsager, CONCAT(Medecin.Nom, ' ', Medecin.Prenom) AS NomPrenomMedecin, Consultation.DateC, Consultation.Duree FROM Medecin, Usager, Consultation WHERE Consultation.Id_Usager = Usager.Id_Usager AND Consultation.Id_Medecin = Medecin.Id_Medecin");
			$requeteConsultation->execute();
			$result = $requeteConsultation->fetchAll(PDO::FETCH_ASSOC);
		if (empty($result)) { 
				print("<b>Pas de consultations trouvés ! </b>");
		} else { //affiche le résultat dans un tableau			
				echo '<table>';
				echo '<b><tr><th>Usager</th><th>Médecin</th><th>Date (j/m/a)</th><th>Duree (h:m)</th></tr></b>';
		        foreach ($result as $r) {
					echo '<tr>';
					echo '<td>'.$r['NomPrenomUsager'].'</td>';
					echo '<td>'.$r['NomPrenomMedecin'].'</td>';
					echo '<td>'.date('d/m/Y', $r['DateC']).'</td>';
					echo '<td>'.date('H:i', $r['Duree']).'</td>';
					echo '</tr>';
				}
				echo '</table>';
		}
		?>
		<i>Pour ajouter des consultations, utilisez la page "Gestion Usagers"</i>
	</body>
</html>
