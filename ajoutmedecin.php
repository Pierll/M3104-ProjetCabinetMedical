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
			    $ajoutusager = $linkpdo->prepare('INSERT INTO Medecin(Civilite, Nom, Prenom) values (?,?,?)');
				
				try {
					$ajoutusager->execute(array($civilite, $_POST["nom"],$_POST["prenom"]));
				} catch (PDOException $e) {
					print $e;
					die('Erreur');
				}
				

			}
?>
	</body>
</html>


