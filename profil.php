<?php 
include 'inc/init.inc.php';
include 'inc/function.inc.php';

// Restriction d'accès : si l'utilisateur n'est pas connécté, on le renvoie sur connection.php
if(!user_is_connect()) {
	header('location:connexion.php');
}




include 'inc/header.inc.php';
include 'inc/nav.inc.php';
// echo '<pre>'; var_dump($_SESSION); echo '</pre>';
?>



	<div class="row">
		<div class="col-12">
			<div class="row">
				<div class="col-6 mx-auto">
					<ul class="list-group mt-5">    <!-- ucfirst pour mettre la 1e lettre en majuscule -->
						<li class="list-group-item active">Bonjour <b><?php echo ucfirst($_SESSION['membre']['pseudo']); ?></b>
						</li>
						<li class="list-group-item">Pseudo : <b><?php echo ucfirst($_SESSION['membre']['pseudo']); ?></b>
						</li>
						<li class="list-group-item">Nom : <b><?php echo ucfirst($_SESSION['membre']['nom']); ?></b>
						</li>
						<li class="list-group-item">Prénom : <b><?php echo ucfirst($_SESSION['membre']['prenom']); ?></b>
						</li>
						<li class="list-group-item">Email : <b><?php echo $_SESSION['membre']['email']; ?></b>
						</li>
						<li class="list-group-item">Civilité : <b>
						<?php 
							if($_SESSION['membre']['civilite'] == 'm') {
								echo 'Homme';
							} else {
								echo 'Femme';
							}
						?></b>
						</li>
						<li class="list-group-item">Statut : <b>
						<?php 
							if($_SESSION['membre']['statut'] == 1) {
								echo 'membre';
							} elseif($_SESSION['membre']['statut'] == 2) {
								echo 'administrateur';
							}
						?>
						</b></li>
					</ul>
				</div>
			</div>
		</div>
	</div>


<?php 
include 'inc/footer.inc.php';