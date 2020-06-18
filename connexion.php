<?php
include 'inc/init.inc.php';
include 'inc/function.inc.php';

//Pour reutiliser la variable
$pseudo = "";

//echo '<pre>'; var_dump($_SESSION); echo'</pre>';

// Deconnexion
if(isset($_GET['action']) && $_GET['action'] == 'deconnexion') {
	session_destroy(); // on détruit la session pour provoquer la deconnexion
}

//Si l'utilisateur est connécté, on le renvoie sur la page profil
if(user_is_connect()) {
	header('location:profil.php');
}


//Est ce que le formulaire a été validé
if (isset($_POST['pseudo']) && isset($_POST['mdp'])) {
	$pseudo = trim($_POST['pseudo']);
	$mdp = trim($_POST['mdp']);

	//On récupere les informations de l'utilisateur sur la BDD(unique en bdd)
	$verif_connexion = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
	$verif_connexion->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
	$verif_connexion->execute();

	if ($verif_connexion->rowCount() > 0) {
			//s'il y a une ligne dans $verif_connexion alors le pseudo est bon
		  $infos = $verif_connexion->fetch(PDO::FETCH_ASSOC); // le fetch transforme la base de donnée en tableau array pour pouvoir le manipuler
		 
		    //On compare le mot de passe qui a été crypté avec password_hash() via la fonction prédéfinie password_verify()
		    if(password_verify($mdp, $infos['mdp'])) {
		    	//Le pseudo et le mot de passe sont corrects, on enregistre les informations du membre de la session

	          $_SESSION['membre'] = array();
	          $_SESSION['membre']['id_membre'] = $infos['id_membre'];
	          $_SESSION['membre']['pseudo'] = $infos['pseudo'];
	          $_SESSION['membre']['nom'] = $infos['nom'];
	          $_SESSION['membre']['prenom'] = $infos['prenom'];
	          $_SESSION['membre']['email'] = $infos['email'];
	          $_SESSION['membre']['civilite'] = $infos['civilite'];        
	          $_SESSION['membre']['statut'] = $infos['statut'];
	          $_SESSION['membre']['date_enregistrement'] = $infos['date_enregistrement'];
	          
          	//Maintenant que l'utilisateur est connécté, on le redirige vers profil.php
          	header('location:profil.php');
          	//header('location:...') doit être éxécuté avant le moindre affichage dans la page sinon =>bug
          
		    } else{
		    		$msg .= '<div class="alert alert-danger mt3">Erreur sur le pseudo et/ou le mot de passe</div>';
		    	}
		    

  }else {
		$msg .= '<div class="alert alert-danger mt3">Erreur sur le pseudo et/ou le mot de passe</div>';
		}

}



include 'inc/header.inc.php';
include 'inc/nav.inc.php';
?>

  <div classe="row"> 
      	<form method="post" action="" class="col-4 mx-auto text-center mt-5">
      		<p class="lead"> <?php echo $msg; ?></p>
      			<div class="form-group">
	      				<label>Pseudo</label>
	      				<input type="text" name="pseudo" id="pseudo" value="<?php echo $pseudo; ?>" class="form-control">
	      		</div>
	      		<div class="form-group">
	      				<label>Mot de passe</label>
	      				<input type="password" name="mdp" id="mdp" value="" class="form-control">
	      		</div>
	      		<div class="form-group">
	      				<button type="submit" name="Connexion" id="Connexion" class="form-control btn btn-outline-primary">Connexion</button>
	      		</div>  		
      	</form>
  </div>



<?php
include 'inc/footer.inc.php';