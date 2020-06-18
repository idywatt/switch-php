
<?php 
include '../inc/init.inc.php';
include '../inc/function.inc.php';

if(!user_is_admin()) {
  header('location:' . URL . 'connexion.php');
  exit(); // bloque l'exécution du code 
}

/**********************************************************
 *********************************************************
 ************* \ SUPPRESSION D'UN PRODUIT *****************
 *********************************************************
 *********************************************************/

if(isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_produit'])) {
    $suppression = $pdo->prepare("DELETE FROM produit WHERE id_produit = :id_produit");
    $suppression->bindParam(":id_produit", $_GET['id_produit'], PDO::PARAM_STR);
    $suppression->execute();

}


//*********************************************************************
//********************************************************************
//**************DEBUT ENREGISTREMENT DE PRODUIT**********************
//*********************************************************************
//*********************************************************************

$date_arrivee = "";
$date_depart = "";
$prix = "";
$salle = "";
$verif_date;
$id_produit = "";

if (
	isset($_POST['date_arrivee']) &&
	isset($_POST['date_depart']) &&
	isset($_POST['prix']) && 
	isset($_POST['salle']) ) {

		$date_arrivee = trim($_POST['date_arrivee']);
		$date_depart = trim($_POST['date_depart']);
		$prix = trim($_POST['prix']);
		$salle = trim($_POST['salle']);

		$newDateArrivee = new DateTime($date_arrivee);
		$newDateDepart = new DateTime($date_depart);
//contrôle sur les dates 
	if($newDateArrivee >= $newDateDepart) {
		$msg .= '<div class="alert alert-danger mt-3">Attention la date de départ doit être strictement supérieur à la date d\'arrivée</div>';
	}


//Contrôle sur le prix
	if(empty($prix) || !is_numeric($prix)) {
		$msg .= '<div class="alert alert-danger mt-3">Le tarif est obligatoire.</div>';
	}

	
	if(empty($msg)){
		if (!empty($_GET['id_produit'])) {
				$id_produit = $_GET['id_produit'];
	 	  		// Si id_produit n'est pas vide  c'est un update (modification)
	 	  		$enregistrement = $pdo->prepare("UPDATE produit SET id_salle = :id_salle, date_arrivee= :date_arrivee, date_depart= :date_depart, prix = :prix WHERE id_produit = :id_produit");

	 	  		//On ajoute le bindParam pour l'id_produit car c'est une modification
	 	  		$enregistrement->bindParam(":id_produit", $id_produit, PDO::PARAM_STR);
	 	  		$enregistrement->bindParam(":id_salle", $salle, PDO::PARAM_STR);
				$enregistrement->bindParam(":date_arrivee", $date_arrivee, PDO::PARAM_STR);
				$enregistrement->bindParam(":date_depart", $date_depart, PDO::PARAM_STR);
				$enregistrement->bindParam(":prix", $prix, PDO::PARAM_STR);	 	  		
				$enregistrement->execute();
				$msg .= '<div class="alert alert-success mt-3">La modification du produit a bien été prise en compte</div>';
	 	  		
	 	  	} else{

				$enregistrement = $pdo->prepare("INSERT INTO produit (id_salle, date_arrivee, date_depart, prix, etat) VALUES (:salle, :date_arrivee, :date_depart, :prix, 'libre')");
				
				$enregistrement->bindParam(":salle", $salle, PDO::PARAM_STR);
				$enregistrement->bindParam(":date_arrivee", $date_arrivee, PDO::PARAM_STR);
				$enregistrement->bindParam(":date_depart", $date_depart, PDO::PARAM_STR);
				$enregistrement->bindParam(":prix", $prix, PDO::PARAM_STR);
				$enregistrement->execute();
				$msg .= '<div class="alert alert-success mt-3">Le nouveau produit a bien été ajouté</div>';
				}
			}

	}

//*********************************************************************
//********************************************************************
//**************FIN ENREGISTREMENT DE PRODUIT**********************
//*********************************************************************
//*********************************************************************

// Affichage des produits
//*********************************************************************
//*********************************************************************

	$liste_produit = $pdo->query("SELECT * FROM produit");
	$listeproduit = $liste_produit->fetch(PDO::FETCH_ASSOC);
	$liste_salle= $pdo->query("SELECT * FROM salle");


//***********************************************************************
//***********************************************************************
//******************DEBUT MODIFICATION DES SALLES EN BDD*****************
//***********************************************************************
//***********************************************************************


	if(isset($_GET['action']) && $_GET['action'] == 'modifier' && !empty($_GET['id_produit'])) {
	    $infos_produit = $pdo->prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
	    $infos_produit->bindParam(":id_produit", $_GET['id_produit'], PDO::PARAM_STR);
	    $infos_produit->execute();

	    if ($infos_produit->rowCount() > 0) {
	      $produit_actuel = $infos_produit->fetch(PDO::FETCH_ASSOC);
	      $id_produit = $produit_actuel['id_produit'];
	      $date_arrivee_to_time = strtotime($produit_actuel['date_arrivee']);
		  $date_arrivee = date("Y-m-d", $date_arrivee_to_time);

	      $date_depart_to_time = strtotime($produit_actuel['date_depart']);
		  $date_depart = date("Y-m-d", $date_depart_to_time);

	      $prix = $produit_actuel['prix'];
	      $salle = $produit_actuel['id_salle'];     
	    }
	}

//***********************************************************************
//***********************************************************************
//******************FIN MODIFICATION DES SALLES**********************
//***********************************************************************
//***********************************************************************
//***********************************************************************

include '../inc/header.inc.php';
include '../inc/nav.inc.php';


?>



	<div class="mt-5 mb-5">
	    <h1 class="text-center">GESTION DES PRODUITS</h1>
	    <p class="lead"><?php echo $msg; ?></p>
	  </div>
	  <p class="text-center">
	    <a href="?action=ajouter" class="btn btn-outline-danger">Ajout produit</a>
	    <a href="?action=affichage" class="btn btn-outline-primary">Affichage produits</a>
	  </p>
	</div>

	<div class="col-12">

<?php

//***********************************************************************
//***********************************************************************
//************************DEBUT AFFICHAGE DES ARTICLES*******************
//***********************************************************************
//***********************************************************************

      if(isset($_GET['action']) && $_GET['action'] == 'affichage') {
        //on récupere les articles en bdd
        $liste_produit = $pdo->query("SELECT * FROM produit");

            echo '<p> nombre de produits : <b>' . $liste_produit->rowCount() . '</b></p>';  

            echo'<div class="table-responsive">';//On rajoute cette dic pour avoir un tableau reponsive

            echo'<table class="table table-bordered text-center">';
            echo '<tr>';
            echo '<th>id_produit</th>';
            echo '<th>Date arrivee</th>';
            echo '<th>Date depart</th>';
            echo '<th>id_salle</th>';
            echo '<th>prix</th>';
            echo '<th>etat</th>';
            echo '<th>Modif</th>';
            echo '<th>Suppr</th>';
            echo '</tr>';


            while($listeproduit = $liste_produit->fetch(PDO::FETCH_ASSOC)){ // on fait une boucle
            	
				echo '<tr>';
				echo '<th> Id produit: '.$listeproduit["id_produit"].' </th>';            
				echo '<th> Date d\'arrivée : '.$listeproduit["date_arrivee"].'</th>';
				echo '<th> Date de départ : '.$listeproduit["date_depart"].'</th>';
				echo '<th> Id salle : '.$listeproduit["id_salle"].'</th>';
				echo '<th> Prix : '.$listeproduit["prix"].'</th>';
				echo '<th> Etat: '.$listeproduit["etat"].' </th>';
				echo '<th><a href="?action=modifier&id_produit='.$listeproduit['id_produit'].'">Modification</a></th>';
				echo '<th><a href="?action=supprimer&id_produit='.$listeproduit['id_produit'].'" onClick="return( confirm(\'Etes-vous certain de vouloir supprimer '.$listeproduit['id_produit'].' ?\') )">Suppression</a></th>';
			
				echo '</tr>';
			   }
                echo'</table>';
                echo '</div>';
		}
//***********************************************************************
//***********************************************************************
//************************FIN AFFICHAGE DES ARTICLES*******************
//***********************************************************************
//***********************************************************************


?>

		<form method="post" action="" enctype="multipart/form-data">
			<input type="hidden" name="id_produit" value="<?php echo $id_produit; ?>">
			<?php //echo "<pre>";var_dump($_POST);echo"</pre>"; ?>
		  <div class="row">				
			<div class="col-6 mx-auto">					
				<div class="form-group">
					<label for="date_arrivee">Date d'arrivée</label>
					<input type="date" name="date_arrivee" min="<?= date('Y-m-d'); ?>"  id="date_arrivee" value="<?php echo $date_arrivee; ?>" class="form-control">
				</div>	
				<div class="form-group">
					<label for="date_depart">Date de depart</label>
					<input type="date" min="<?= date('Y-m-d'); ?>" rows="2" name="date_depart" id="date_depart" value="<?php echo $date_depart; ?>" class="form-control">
				</div>
				<div class="form-group">
					<label for="prix">Tarif</label>
					<input name="prix" id="prix" class="form-control" value="<?php echo $prix; ?>"/>
				</div>	
				<div class="form-group">
					<label for="salle">Salle</label>
					<select name="salle" id="salle" class="form-control">
						<?php		
					while($salle = $liste_salle->fetch(PDO::FETCH_ASSOC)){
					echo	'<option value="'.$salle["id_salle"].'" >';
					echo ''.$salle["id_salle"].' , '.ucfirst($salle["titre"]).' '.ucfirst($salle["adresse"]).'';
					echo	"</option>";
				   } ?>
					</select>
				</div>
				<div class="form-group">
				    <button type="submit" id="enregistrement" class="form-control btn btn-outline-dark"> Enregistrer</button>
				</div>
			</div> <!-- col-6 mx-auto -->
		  </div> <!-- class="row" -->
		</form>
	</div> <!-- class="col-12" -->
	



	
	
<?php 
include '../inc/footer.inc.php';