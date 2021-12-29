<!DOCTYPE HTML>
<html>
	<html lang="fr">
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>

	<?php include 'requires/require_login.php'; ?>
	<?php include 'requires/require_db.php'; ?>
	<?php require 'requires/require_menu_nav.html'; ?>

	<form method="post">
		<p>Nom / Prénom ?</p>
		Nom <input type="text" name="nom"/><br />
		Prénom <input type="text" name="prenom"/><br />
		<input type="reset" value="Vider"/>
		<input type="submit" name="btn_rechercher" value="Rechercher"/>
		<input type="submit" name="btn_affichertout" value="Afficher tout les usagers"/>
	</form>
	<br/>
	<?php
		if (isset($_POST["btn_affichertout"])) {
			$_POST["btn_rechercher"] = 1;
			$_POST["nom"] = "";
			$_POST["prenom"] = "";
		}

		if (isset($_POST["btn_rechercher"])) {

			($nom = $_POST["nom"]) == "" ? $nom = "%" : 1 ;
			($prenom = $_POST["prenom"]) == "" ? $prenom = "%" : 1 ;

			$res = $linkpdo->prepare('SELECT civilite, nom, prenom, dateNaissance, Id_Usager
			FROM Usager 
			WHERE nom LIKE :nom 
			AND prenom LIKE :prenom');
			//execute
			$res->execute(array('nom' => $nom,
								'prenom' => $prenom));
			
			$result = $res->fetchAll(PDO::FETCH_ASSOC);
			if (empty($result)) {
				print("<b>Pas d'usagés trouvés ! </b>");
			} else {			
				echo '<table>';
				echo '<b><tr><th>Civilité</th><th>Nom</th><th>Prénom</th><th>Date Naissance</th></tr>';
		        foreach ($result as $r) {
		            //print_r($r);
					echo '<tr>';
					if ($r['civilite'] == 0) {
						$r['civilite'] = "Madame";
					} else {
						$r['civilite'] = "Monsieur";
					}
					echo '<td>'.$r['civilite'].'</td>';
					echo '<td>'.$r['nom'].'</td>';
					echo '<td>'.$r['prenom'].'</td>';
					echo '<td>'.date('d/m/Y', $r['dateNaissance']).'</td>';
						echo "<td><form method=\"post\">
		<input type=\"submit\" name=\"btn_supprimer\" value=\"Supprimer\" />
		<input type=\"hidden\" name=\"id_usager\" value=\"".$r['Id_Usager']."\" />
	</form></td>";
					echo '</tr>';
					print("\n");
		        }
				echo '</table>';
			}
			
		}	
		if (isset($_POST["btn_supprimer"])) {
			$suppressionUsager = $linkpdo->prepare('DELETE FROM Usager WHERE Id_Usager = ?');
			try {
					$suppressionUsager->execute(array($_POST["id_usager"]));
			} catch (PDOException $e) {
					print $e;
					die('Erreur');
			}
			echo '<b> Succès de la suppression </b>';
		}

	?>    

	<form method="post">
		<input type="submit" name="btn_ajouter" value="Ajouter un Usager" />
	</form>
	</body>
</html>
