<?php 
$server = "localhost";
	$login = "root";
	$mdp = "password";
	$db = "CabinetMedical";

	//Connexion au serveur MySQL 
	try { 
		  $linkpdo = new PDO("mysql:host=$server;dbname=$db", $login, $mdp); 
	} catch (Exception $e) { 
		die('Erreur : ' . $e->getMessage());
	}
	$linkpdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	foreach ($_POST as $param_name => $param_val) {
		if (!isset($_POST[$param_name]) || $_POST[$param_name] == '') {
			echo "Erreur lors du traitement, une valeur est manquante: Param: $param_name; Value: $param_val\n";
			exit();
		}

	}
	$ajoutcontact = $linkpdo->prepare('INSERT INTO Usager (civilite, nom, prenom, adresse, dateNaissance, lieuNaissance, numSecu) values (?,?,?,?,?,?,?)');

	try {
		$ajoutcontact->execute(array($_POST["civilite"],$_POST["nom"],$_POST["prenom"],$_POST["adresse"],$_POST["dateNaissance"],$_POST["lieuNaissance"],$_POST["numSecu"]));
	} catch (PDOException $e) { 
		print($e);
		//die('Erreur, le numero est deja present dans la base de donnee');
		die('Une erreur est survenue');
	}
	echo 'Le numero a ete correctement enregistre';
?>
