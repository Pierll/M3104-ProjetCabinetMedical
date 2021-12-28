<!DOCTYPE HTML>
<html>
	<html lang="fr">
	<head>
		<meta charset="UTF-8">
	</head>
	<body>
		<?php include 'requires/require_login.php'; ?>
		<?php include 'requires/require_db.php'; ?>
		<p>Saisir l'usager : </p>
		<form method="post">
		   Civilite: <select name="civ">
			   <option value="M">Monsieur</option>
		       <option value="Mme">Madame</option>
			</select>
			<p> Nom <input type="text" name="nom" /></p>
			<p> Prénom <input type="text" name="prenom" /></p>
			<p> Adresse <input type="text" name="adresse" /></p>
			<p> Date de naissance <input type="text" name="datenaissance" /></p>
			<p> Lieu de naissance <input type="text" name="lieunaissance" /></p>
			<p> Numéro de sécu <input type="text" name="numsecu" /></p>
			<?php // liste deroulante des medecins 
			$medecins = $linkpdo->prepare("SELECT Id_Medecin, CONCAT(Nom, ' ', Prenom) AS NomPrenom FROM Medecin");
			$medecins->execute();
			$result = $medecins->fetchAll(PDO::FETCH_ASSOC); //pour ne avoir de doublons
			if (empty($result)) {
				echo '<b>Pas de resultats trouves<b>';
			} else {
				echo "Medecin Referant :";
				echo "<select name=\"civ\">";
				foreach ($result as $r) {
					//print_r($r);
					echo '<option value='.$r['Id_Medecin'].'>'.$r['NomPrenom'];
					
					echo '<br/>';
				}
				echo "</select>";
			}
			?>
			<p><input name="btn" type="submit" value="Envoyer">
			<input type="reset" value="Vider"></p>

		</form>

			

<?php 
			if (isset($_POST["btn"])) {
				foreach ($_POST as $param_name => $param_val) {
				    if (!isset($_POST[$param_name]) || $_POST[$param_name] == '') {
				    	echo "Erreur lors du traitement, une valeur est manquante: Param: $param_name; Value: $param_val\n";
				    	exit();
				    }
					
				}	
				if ($_POST["civ"] == "Mme") {
					echo '<p>MADAME</p>';
					$civilite = 0;
				} else {
					echo '<p>MONSIEUR</p>';
					$civilite = 1;
				}
			    $ajoutusager = $linkpdo->prepare('INSERT INTO Usager(Civilite, Nom, Prenom, Adresse, DateNaissance, LieuNaissance, NumSecu) values (?,?,?,?,?,?,?)');
				
				try {
					$ajoutusager->execute(array($civilite, $_POST["nom"],$_POST["prenom"], $_POST["adresse"], $_POST["datenaissance"], $_POST["lieunaissance"], $_POST["numsecu"]));
				} catch (PDOException $e) {
					print $e;
					die('Erreur');
				}
				

			}
?>
	</body>
</html>


