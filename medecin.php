<!DOCTYPE HTML>
<html>
	<html lang="fr">
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<title>Gestion medecins</title>
	</head>
	<body>

	<?php include 'requires/require_login.php'; ?>
	<?php include 'requires/require_db.php'; ?>
	<?php require 'requires/require_menu_nav.php'; ?>

	<form method="post">
		<h2>Recherche de medecins</h2>
		<i><p>Il est possible d'entrer seulement un nom ou un prénom</p></i>
		Nom <input type="text" name="nom"/><br />
		Prénom <input type="text" name="prenom"/><br />
		<input type="submit" name="btn_rechercher" value="Rechercher"/>
		<input type="submit" name="btn_affichertout" value="Afficher tout les médecins"/>
	</form>
	<br/> 
	<?php
		if (isset($_POST["btn_affichertout"])) {
			$_POST["btn_rechercher"] = 1;
			$_POST["nom"] = "";
			$_POST["prenom"] = "";
		}

		if (isset($_POST["btn_rechercher"])) {
			/* Si l'utilisateur n'entre rien le champs concerne TOUT les noms/prénom */
			($nom = $_POST["nom"]) == "" ? $nom = "%" : 1 ; 
			($prenom = $_POST["prenom"]) == "" ? $prenom = "%" : 1 ;

			$res = $linkpdo->prepare('SELECT civilite, nom, prenom, Id_Medecin
			FROM Medecin 
			WHERE nom LIKE :nom 
			AND prenom LIKE :prenom');
			$res->execute(array('nom' => $nom,
								'prenom' => $prenom));
			
			$result = $res->fetchAll(PDO::FETCH_ASSOC);

			if (empty($result)) { 
				print("<b>Pas de médecins trouvés ! </b>");
			} else { //affiche le résultat dans un tableau			
				echo '<table>';
				echo '<b><tr><th>Civilité</th><th>Nom</th><th>Prénom</th></tr>';
		        foreach ($result as $r) {
					echo '<tr>';
					if ($r['civilite'] == 0) {
						$r['civilite'] = "Madame";
					} else {
						$r['civilite'] = "Monsieur";
					}
					echo '<td>'.$r['civilite'].'</td>';
					echo '<td>'.$r['nom'].'</td>';
					echo '<td>'.$r['prenom'].'</td>';
					/* Génère un bouton supprimer médecin*/
						echo "<td><form method=\"post\"> 
		<input type=\"submit\" name=\"btn_supprimer\" value=\"Supprimer\" />
		<input type=\"hidden\" name=\"id_medecin\" value=\"".$r['Id_Medecin']."\" />
	</form></td>";
					/* Génère un bouton modifier médecin*/
						echo "<td><form method=\"post\" action=\"modifiermedecin.php\"> 
		<input type=\"submit\" name=\"btn_modifier\" value=\"Modifier\" />
		<input type=\"hidden\" name=\"id_medecin\" value=\"".$r['Id_Medecin']."\" />
	</form></td>";
					echo '</tr>';
					print("\n");
		        }
				echo '</table>';
			}
			
		}	
		if (isset($_POST["btn_supprimer"])) {
			$suppressionUsager = $linkpdo->prepare('UPDATE Usager SET Id_Medecin = NULL WHERE Id_Medecin = ?'); //il faut d'abord retirer la clé étrangère des usagers
			$suppressionConsultation = $linkpdo->prepare('DELETE FROM Consultation WHERE Id_Medecin = ?'); //supprimer les consultations...
			$suppressionMedecin = $linkpdo->prepare('DELETE FROM Medecin WHERE Id_Medecin = ?');
			try {
					$suppressionUsager->execute(array($_POST["id_medecin"]));
					$suppressionConsultation->execute(array($_POST["id_medecin"]));
					$suppressionMedecin->execute(array($_POST["id_medecin"]));
			} catch (PDOException $e) {
					print $e;
					die('Erreur');
			}
			echo '<b> Succès de la suppression </b>';
		}
	?>    
	<br/>
	<a href="ajoutmedecin.php">Ajouter un médecin</a>

	</body>
</html>
