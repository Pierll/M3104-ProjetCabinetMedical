<!DOCTYPE HTML>
<html>
	<html lang="fr">
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<title>Gestion usagers</title>
	</head>
	<body>

	<?php include 'requires/require_login.php'; ?>
	<?php include 'requires/require_db.php'; ?>
	<?php require 'requires/require_menu_nav.php'; ?>

	<form method="post">
		<h2>Recherche d'usagers</h2>
		<i><p>Il est possible d'entrer seulement un nom ou un prénom</p></i>
		Nom <input type="text" name="nom"/><br />
		Prénom <input type="text" name="prenom"/><br />
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
			/* Si l'utilisateur n'entre rien le champs concerne TOUT les noms/prénom */
			($nom = $_POST["nom"]) == "" ? $nom = "%" : 1 ; 
			($prenom = $_POST["prenom"]) == "" ? $prenom = "%" : 1 ;

			$res = $linkpdo->prepare('SELECT civilite, nom, prenom, dateNaissance, Id_Usager, Id_Medecin
			FROM Usager 
			WHERE nom LIKE :nom 
			AND prenom LIKE :prenom');

			$medecinReferant = $linkpdo->prepare("SELECT CONCAT(Nom, ' ', Prenom) AS NomPrenom FROM Medecin WHERE Id_Medecin = ?");

			$res->execute(array('nom' => $nom,
								'prenom' => $prenom));

			$result = $res->fetchAll(PDO::FETCH_ASSOC);

			if (empty($result)) { 
				print("<b>Pas d'usagés trouvés ! </b>");
			} else { //affiche le résultat dans un tableau			

				echo '<table>';
				echo '<b><tr><th>Civilité</th><th>Nom</th><th>Prénom</th><th>Date Naissance</th><th>Médecin Référant</th></tr>';
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
					echo '<td>'.date('d/m/Y', $r['dateNaissance']).'</td>';
					$medecinReferant->execute(array($r["Id_Medecin"])); //on cherche le médecin associé au client
					$result_medecins_referant = $medecinReferant->fetchAll(PDO::FETCH_ASSOC);
					if (empty($result_medecins_referant)) { //si le médecin n'existe pas
						echo '<td><i>(Aucun)</td></i>';
					} else {
						echo '<td><i>'.$result_medecins_referant[0]["NomPrenom"].'</i></td>';
					}
					/* Génère un bouton supprimer usager*/
						echo "<td><form method=\"post\"> 
		<input type=\"submit\" name=\"btn_supprimer\" value=\"Supprimer\" />
		<input type=\"hidden\" name=\"id_usager\" value=\"".$r['Id_Usager']."\" />
	</form></td>";
					/* Génère un bouton modifier usager*/
						echo "<td><form method=\"post\" action=\"modifierusager.php\"> 
		<input type=\"submit\" name=\"btn_modifier\" value=\"Modifier\" />
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
	<br/>
	<a href="ajoutusager.php">Ajouter un usager</a>

	</body>
</html>
