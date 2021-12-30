<!DOCTYPE HTML>
<html>
	<html lang="fr">
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<title>Ajout usager</title>
	</head>
	<body>
		<?php include 'requires/require_login.php'; ?>
		<?php include 'requires/require_db.php'; ?>
		<?php include 'requires/require_menu_nav.php'; ?>
		<p>Saisir l'usager : </p>
		<form method="post">
		   Civilite: <select name="civ">
			   <option value="1">Monsieur</option>
		       <option value="0">Madame</option>
			</select>
			<p> Nom <input type="text" name="nom" /></p>
			<p> Prénom <input type="text" name="prenom" /></p>
			<p> Adresse <input type="text" name="adresse" /></p>
			<p> Date de naissance <input type="date" name="datenaissance" /></p>
			<p> Lieu de naissance <input type="text" name="lieunaissance" /></p>
			<p> Numéro de sécu (ex: <?php echo rand(111111111111111, 999999999999999);?>)<input type="text" name="numsecu" /></p>
			<?php // liste deroulante des medecins 
			$medecins = $linkpdo->prepare("SELECT Id_Medecin, CONCAT(Nom, ' ', Prenom) AS NomPrenom FROM Medecin");
			$medecins->execute();
			$result = $medecins->fetchAll(PDO::FETCH_ASSOC); //pour ne avoir de doublons
			if (empty($result)) {
				echo '<b>Pas de medecins, veuillez en ajouter<b>';
			} else {
				echo "Medecin Referant :";
				echo "<select name=\"idmedecin\">";
				foreach ($result as $r) {
					//print_r($r);
					echo '<option value='.$r['Id_Medecin'].'>'.$r['NomPrenom'];
					
					echo '<br/>';
				}
				echo "</select>";
			}
			?>
			<p><input name="btn_ajouterusager" type="submit" value="Ajouter">
			<input type="reset" value="Vider"></p>

		</form>

			

<?php 
			if (isset($_POST["btn_ajouterusager"])) {
				foreach ($_POST as $param_name => $param_val) {
				    if (!isset($_POST[$param_name]) || $_POST[$param_name] == '') {
				    	echo "Erreur lors du traitement, une valeur est manquante: Param: $param_name; Value: $param_val\n";
				    	exit();
				    }
					
				}	

			    $ajoutusager = $linkpdo->prepare('INSERT INTO Usager(Civilite, Nom, Prenom, Adresse, DateNaissance, LieuNaissance, NumSecu, Id_Medecin) values (?,?,?,?,?,?,?,?)');
				$datenaissance = strtotime($_POST["datenaissance"]);
				try {
					$ajoutusager->execute(array($_POST["civ"], $_POST["nom"],$_POST["prenom"], $_POST["adresse"], $datenaissance, $_POST["lieunaissance"], $_POST["numsecu"], $_POST["idmedecin"]));
				} catch (PDOException $e) {
					print $e;
					die('Erreur');
				}
				header("location: usager.php"); 

			}
?>
	</body>
</html>


