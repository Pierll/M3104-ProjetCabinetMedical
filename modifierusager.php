<!DOCTYPE HTML>
<html>
	<html lang="fr">
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<title>Modifier usager</title>
	</head>
	<body>
		<?php include 'requires/require_login.php'; ?>
		<?php include 'requires/require_db.php'; ?>
		<?php include 'requires/require_menu_nav.php'; ?>
		<p>Modifier l'usager : </p>
		<?php 
			if (!isset($_POST['id_usager'])) { //on retourne à la page usager quand on a finis
				header("location: usager.php");
			}
			$res = $linkpdo->prepare('SELECT * FROM Usager 
			WHERE Id_Usager LIKE ?');
			$res->execute(array($_POST['id_usager']));
			
			$result = $res->fetchAll(PDO::FETCH_ASSOC);

		?>
		<form method="post">
		   Civilite: <select name="civ">
			   <option value="1" <?php if ($result[0]['Civilite'] == 1) echo "selected" ?> >Monsieur</option>
		       <option value="0" <?php if ($result[0]['Civilite'] == 0) echo "selected" ?>>Madame</option>
			</select>
			<p> Nom <input type="text" name="nom" value=<?php echo $result[0]['Nom'];?> /></p>
			<p> Prénom <input type="text" name="prenom" value=<?php echo $result[0]['Prenom'];?> /></p>
			<p> Adresse <input type="text" name="adresse" value=<?php echo $result[0]['Adresse'];?> /></p>
			<p> Date de naissance <input type="date" name="datenaissance" value=<?php echo date('Y-m-d', $result[0]['DateNaissance']); ?> /></p>
			<p> Lieu de naissance <input type="text" name="lieunaissance" value=<?php echo $result[0]['LieuNaissance']; ?>  /></p>
			<p> Numéro de sécu (ex: <?php echo rand(111111111111111, 999999999999999);?>)<input type="text" name="numsecu" value=<?php echo $result[0]['NumSecu']; ?> /></p>
			<?php // liste deroulante des medecins 
			$medecins = $linkpdo->prepare("SELECT Id_Medecin, CONCAT(Nom, ' ', Prenom) AS NomPrenom FROM Medecin");
			$medecins->execute();
			$result_medecins = $medecins->fetchAll(PDO::FETCH_ASSOC); //pour ne avoir de doublons
			echo "Medecin Referant :<br/>";
			echo "<select name=\"idmedecin\">";
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
			<input type="hidden" name="idusager" value=<?php echo $_POST['id_usager']; ?>>
			<br/><br/>
			<input name="btn_ajouterusager" type="submit" value="Modifier">
		</form>

			

<?php 
			if (isset($_POST["btn_ajouterusager"])) {
				foreach ($_POST as $param_name => $param_val) {
				    if (!isset($_POST[$param_name]) || $_POST[$param_name] == '') {
				    	echo "Erreur lors du traitement, une valeur est manquante: Param: $param_name; Value: $param_val\n";
				    	exit();
				    }
					
				}	

					$ajoutusager = $linkpdo->prepare('UPDATE Usager SET Civilite= ?, Nom = ?, Prenom = ?, Adresse = ?, DateNaissance = ?, LieuNaissance = ?, NumSecu = ?, Id_Medecin = ? WHERE Id_Usager = ?');
					$datenaissance = strtotime($_POST["datenaissance"]);
					try {
						$ajoutusager->execute(array($_POST["civ"], $_POST["nom"],$_POST["prenom"], $_POST["adresse"], $datenaissance, $_POST["lieunaissance"], $_POST["numsecu"], $_POST["idmedecin"], $_POST["idusager"]));

					} catch (PDOException $e) {
						print $e;
						die('Erreur');
					}
				
			    
				
			}

?>
	</body>
</html>


