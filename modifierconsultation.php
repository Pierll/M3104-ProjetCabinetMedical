<!DOCTYPE HTML>
<html>
	<html lang="fr">
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<title>Modifier Consultation</title>
	</head>
	<body>
		<?php include 'requires/require_login.php'; ?>
		<?php include 'requires/require_db.php'; ?>
		<?php include 'requires/require_menu_nav.php'; ?>
		<h2>Modifier la consultation : </h2>
		<?php 
			if (!isset($_POST['id_consultation'])) { //on retourne à la page consultation quand on a finis
				header("location: consultation.php");
			}
			$res = $linkpdo->prepare('SELECT * FROM Consultation 
			WHERE Id_Consultation = ?');
			$res->execute(array($_POST['id_consultation']));
			$result = $res->fetchAll(PDO::FETCH_ASSOC);

		?>
		<form method="post">
			<?php // liste deroulante des medecins 
			$medecins = $linkpdo->prepare("SELECT Id_Medecin, CONCAT(Nom, ' ', Prenom) AS NomPrenom FROM Medecin");
			$medecins->execute();
			$result_medecins = $medecins->fetchAll(PDO::FETCH_ASSOC); //pour ne avoir de doublons
			echo "Medecin :<br/>";
			echo "<select name=\"idmedecin\">";
			if ($result[0]['Id_Medecin'] == NULL) { //si le patient n'a pas de médecins référants
				echo '<option value="NULL" selected>(Le médecin à été supprimé)</option>';
			}
			foreach ($result_medecins as $r) {
				//print_r($r);



				echo '<option value='.$r['Id_Medecin'];
				if ($r['Id_Medecin'] == $result[0]['Id_Medecin'])
					echo ' selected';
				echo '>'.$r['NomPrenom'].'</option>';
				
				echo '<br/>';
			}
			echo "</select>";
			?>
			<br/>
			<?php // liste deroulante des usagers 
			$usagers = $linkpdo->prepare("SELECT Id_Usager, CONCAT(Nom, ' ', Prenom) AS NomPrenom FROM Usager");
			$usagers->execute();
			$result_usagers = $usagers->fetchAll(PDO::FETCH_ASSOC); //pour ne avoir de doublons
			echo "Usager :<br/>";
			echo "<select name=\"idusager\">";
			foreach ($result_usagers as $r) {
				//print_r($r);
				echo '<option value='.$r['Id_Usager'];
				if ($r['Id_Usager'] == $result[0]['Id_Usager'])
					echo ' selected';
				echo '>'.$r['NomPrenom'].'</option>';
				
				echo '<br/>';
			}
			echo "</select>";
			?>
			<p>Date:</p>
			<input type="date" name="date_consultation" value=<?php echo date('Y-m-d', $result[0]['DateC']); ?>>
			<p>Heure (h:m):</p>
			<input type="time" name="heure_consultation" value=<?php echo date('H:i', $result[0]['DateC']); ?>> 
			<p>Duree (h:m):</p>
			<input type="time" name="duree_consultation" value=<?php 
			$time = $result[0]['Duree'];
			$seconds = $time % 60;
			$time = ($time - $seconds) / 60;
			$minutes = $time % 60;
			$hours = ($time - $minutes) / 60;
			if ($minutes < 10) 
				$minutes = '0'.$minutes;
			if ($hours < 10) 
				$hours = '0'.$hours;
			echo $hours.':'.$minutes; 
			?>> 

			<input type="hidden" name="idconsultation" value=<?php echo $_POST['id_consultation']; ?>>
			<br/><br/>
			<input name="btn_ajouterconsultation" type="submit" value="Modifier">
		</form>
			

<?php 
			if (isset($_POST["btn_ajouterconsultation"])) {
				foreach ($_POST as $param_name => $param_val) {
				    if (!isset($_POST[$param_name]) || $_POST[$param_name] == '') {
				    	echo "Erreur lors du traitement, une valeur est manquante: Param: $param_name; Value: $param_val\n";
				    	exit();
				    }
					
				}	
				$modifierconsultation = $linkpdo->prepare('UPDATE Consultation SET Id_Usager= ?, Id_Medecin = ?, DateC = ?, Duree = ? WHERE Id_Consultation = ?');
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
				$verifierconsultation->execute(array($_POST['idmedecin'],$dateconsultation,$dateconsultation,$finconsultation,$finconsultation,$dateconsultation,$finconsultation));
				$resultatChevauchement = $verifierconsultation->fetchAll(PDO::FETCH_ASSOC);
				if (!empty($resultatChevauchement)) {
					echo 'Erreur, chevauchement !'; 
					die(); // !!! BUG !!! Si le chevauchement a lieu la page ne l'afficheras pas (le contenue ne seras quand même pas modifié)
				}
				
				try {
					$modifierconsultation->execute(array($_POST["idusager"], $_POST["idmedecin"],$dateconsultation, $dureeconsultation, $_POST['idconsultation']));
				} catch (PDOException $e) {
					print $e;
					die('Erreur');
				}
				
			    
				
			}

?>
	</body>
</html>


