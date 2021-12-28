<html>
	<html lang="fr">
	<head>
		<meta charset="UTF-8">
	</head>
	<body>
		<?php include 'requires/require_login.php'; ?>
		<?php include 'requires/require_db.php'; ?>
		<?php
			
			if (!isset($_POST['Modifier'])) {
				$req = $PDO->prepare('SELECT * FROM contact WHERE id = :id');
				$req->execute(array('id' => $_GET['id']));
				while($data = $req->fetch()){
					$nom = $data[0];
					$prenom = $data[1];
					$adresse = $data[2];
					$code_postal = $data[3];
					$ville = $data[4];
					$telephone = $data[5];
					$id = $data[6];
				}
			}
			
			if (isset($_POST["civilite"])) {
				$civilite = $_POST["civilite"];
			}
			if (isset($_POST["nom"])) {
				$nom = $_POST["nom"];
			}
			if (isset($_POST["prenom"])) {
				$prenom = $_POST["prenom"];
			}
			if (isset($_POST["adresse"])) {
				$adresse = $_POST["adresse"];
			}
			if (isset($_POST["dateNaissance"])) {
				$dateNaissance = $_POST["dateNaissance"];
			}
			if (isset($_POST["lieuNaissance"])) {
				$lieuNaissance = $_POST["lieuNaissance"];
			}
			if (isset($_POST["numSecu"])) {
				$numSecu = $_POST["numSecu"];
			}
		?>
		
		<form method="post">
			<p>Saisir les infos du contact à rechercher : </p>
			Nom <input type="text" name="nom" value="<?php echo $nom; ?>" /><br />
			Prénom <input type="text" name="prenom" value="<?php echo $prenom; ?>" /><br />
			Adresse <input type="text" name="adresse" value="<?php echo $adresse; ?>" /><br />
			Code postal <input type="text" name="code_postal" value="<?php echo $code_postal; ?>" /><br />
			Ville <input type="text" name="ville" value="<?php echo $ville; ?>" /></p>
			Téléphone <input type="text" name="telephone" value="<?php echo $telephone; ?>" /><br />
			<p><input type="reset" value="Vider">
			<input type="submit" name="Modifier" value="Modifier"></p>
		</form>
		
		<?php 
			$res = $PDO->prepare('UPDATE contact SET civilite = :civilite,
				nom = :nom,
				prenom = :prenom, 
				adresse = :adresse, 
				code_postal = :code_postal,
				ville = :ville,
				telephone = :telephone
				WHERE id = :id');
			$res->execute(array('id' => $_GET["id"],
				'nom' => $nom,
				'prenom' => $prenom,
				'adresse' => $adresse,
				'code_postal' => $code_postal,
				'ville' => $ville,
				'telephone' => $telephone));
				
		?>
	</body>
</html>