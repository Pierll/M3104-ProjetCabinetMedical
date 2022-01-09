<!DOCTYPE HTML>
<html>
	<html lang="fr">
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<title>Creer une consultation</title>
	</head>
	<body>
		<?php include 'requires/require_login.php'; ?>
		<?php include 'requires/require_db.php'; ?>
		<?php include 'requires/require_menu_nav.php'; ?>

		<h2>Créer une consultation</h2>
		
		<form method="post">
			<p>Medecin:</p>
			<?php // liste deroulante des medecins 
				$medecins = $linkpdo->prepare("SELECT Id_Medecin, CONCAT(Nom, ' ', Prenom) AS NomPrenom FROM Medecin");
				$medecins->execute();
				$result = $medecins->fetchAll(PDO::FETCH_ASSOC); //pour ne avoir de doublons

				if (empty($result)) {
					echo '<b>Pas de medecins, veuillez en ajouter<b>';
				} else {
					echo "<select name=\"id_medecin\">";
					foreach ($result as $r) {
						echo '<option value='.$r['Id_Medecin'];

						if ($r['Id_Medecin'] == $_POST['id_medecin']) //selectionner le médecin traitant par défaut
							echo ' selected';

						echo '>'.$r['NomPrenom'];
						
						echo '<br/>';
					}
					echo "</select>";
				}
			?>
			<p>Date:</p>
			<input type="date" name="date_consultation"> 
			<p>Heure (h:m):</p>
			<input type="time" name="heure_consultation"> 
			<p>Duree (h:m) (MAX: 23h59h)</p>
			<input type="time" name="duree_consultation"> 
			<p><input type="submit" name="btn_ajout_consultation" value="Créer consultation"></p>			
			<input type="hidden" name="id_usager" value=<?php echo $_POST['id_usager']; ?>>
		</form>
		<?php 

			if (isset($_POST["btn_ajout_consultation"])) {
				foreach ($_POST as $param_name => $param_val) {
				    if (!isset($_POST[$param_name]) || $_POST[$param_name] == '') {
				    	echo "Erreur lors du traitement, une valeur est manquante: Param: $param_name; Value: $param_val\n";
				    	exit();
				    }
					
				}	

			    $ajoutconsultation = $linkpdo->prepare('INSERT INTO Consultation(Id_Usager, Id_Medecin, DateC, Duree) values (?,?,?,?)');
				$dateconsultation = strtotime($_POST["date_consultation"].' '.$_POST['heure_consultation']);
				$dureeconsultation = strtotime('1970-01-01 ' . $_POST["duree_consultation"] . 'GMT');
				$finconsultation = $dateconsultation + $dureeconsultation;

				/* Vérification chevauchement */ 
				$verifierconsultation = $linkpdo->prepare("SELECT Id_Consultation
					FROM Consultation
					WHERE Id_Medecin = ?
					  AND ((? >= DateC
					       AND ? <= DateC + Duree)
					  OR (? >= DateC
					      AND ? <= DateC + Duree)
					  OR (? < DateC
					      AND ? > DateC + Duree))");
				$verifierconsultation->execute(array($_POST['id_medecin'],$dateconsultation,$dateconsultation,$finconsultation,$finconsultation,$dateconsultation,$finconsultation));
				$resultatChevauchement = $verifierconsultation->fetchAll(PDO::FETCH_ASSOC);
				if (!empty($resultatChevauchement)) {
					echo 'Erreur, chevauchement !';
					die();
				}
				

				try {
					$ajoutconsultation->execute(array($_POST['id_usager'], $_POST['id_medecin'], $dateconsultation, $dureeconsultation));
				} catch (PDOException $e) {
					print $e;
					die('Erreur');
				}
				header("location: usager.php"); 

			}
		?>
	</body>
</html>
