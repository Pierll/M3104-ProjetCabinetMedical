<!DOCTYPE HTML>
<html>
	<html lang="fr">
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<title>Modifier medecin</title>
	</head>
	<body>
		<?php include 'requires/require_login.php'; ?>
		<?php include 'requires/require_db.php'; ?>
		<?php include 'requires/require_menu_nav.php'; ?>
		<p>Modifier le medecin : </p>
		<?php 
			if (!isset($_POST['id_medecin'])) { //on retourne à la page medecin quand on a finis
				header("location: medecin.php");
			}
			$res = $linkpdo->prepare('SELECT * FROM Medecin 
			WHERE Id_Medecin LIKE ?');
			$res->execute(array($_POST['id_medecin']));
			$result = $res->fetchAll(PDO::FETCH_ASSOC);

		?>
		<form method="post">
		   Civilite: <select name="civ">
			   <option value="1" <?php if ($result[0]['Civilite'] == 1) echo "selected" ?> >Monsieur</option>
		       <option value="0" <?php if ($result[0]['Civilite'] == 0) echo "selected" ?>>Madame</option>
			</select>
			<p> Nom <input type="text" name="nom" value=<?php echo $result[0]['Nom'];?> /></p>
			<p> Prénom <input type="text" name="prenom" value=<?php echo $result[0]['Prenom'];?> /></p>
			<input type="hidden" name="idmedecin" value=<?php echo $_POST['id_medecin']; ?>>
			<br/><br/>
			<input name="btn_ajoutermedecin" type="submit" value="Modifier">
		</form>

			

<?php 
			if (isset($_POST["btn_ajoutermedecin"])) {
				foreach ($_POST as $param_name => $param_val) {
				    if (!isset($_POST[$param_name]) || $_POST[$param_name] == '') {
				    	echo "Erreur lors du traitement, une valeur est manquante: Param: $param_name; Value: $param_val\n";
				    	exit();
				    }
					
				}	

					$ajoutmedecin = $linkpdo->prepare('UPDATE Medecin SET Civilite= ?, Nom = ?, Prenom = ? WHERE Id_Medecin = ?');
					try {
						$ajoutmedecin->execute(array($_POST["civ"], $_POST["nom"],$_POST["prenom"], $_POST["idmedecin"]));
					} catch (PDOException $e) {
						print $e;
						die('Erreur');
					}
				
			    
				
			}

?>
	</body>
</html>


