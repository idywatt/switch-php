<?php
include '../inc/init.inc.php';
include '../inc/function.inc.php';

if(!user_is_admin()) {
  header('location:' . URL . 'connexion.php');
  exit(); // bloque l'exécution du code 
}

/*$tableau = $pdo->query("SELECT * FROM commande, produit, membre
                       WHERE commande.id_produit = produit.id_produit
                       AND membre.id_membre = commande.id_membre
                       AND membre.id_membre = :id_membre
                       ");*/


$id_commande = '';
$id_membre = '';
$id_produit = '';
$prix = '';
$date_enregistrement = '';


//***********************************************************************
//***********************************************************************
//**************DEBUT SUPRESSION DES COMMANDES***************
//***********************************************************************
//***********************************************************************


  if(isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_commande'])) {
    $suppression = $pdo->prepare("DELETE FROM commande WHERE id_commande = :id_commande");
    $suppression->bindParam(":id_commande", $_GET['id_commande'], PDO::PARAM_STR);
    $suppression->execute();
}

//***********************************************************************
//***********************************************************************
//*************************FIN SUPRESSION DES COMMANDES**********************
//***********************************************************************
//***********************************************************************

include '../inc/header.inc.php';
include '../inc/nav.inc.php';


?>



    <div class="mt-5 mb-5">
        <h1 class="text-center">GESTION DES COMMANDES</h1>
        <p class="lead"><?php echo $msg; ?></p>
    </div>
 

  <div class="col-12">

<?php

//***********************************************************************
//***********************************************************************
//************************DEBUT AFFICHAGE DES COMMANDES***********************
//***********************************************************************
//***********************************************************************
        //on récupere les commandes en bdd
        $liste_commande = $pdo->query("SELECT id_commande, commande.id_membre , commande.id_produit, commande.date_enregistrement, prix, email FROM produit, commande, membre WHERE commande.id_produit = produit.id_produit AND membre.id_membre = commande.id_membre");


            echo '<p> nombre de commandes : <b>' . $liste_commande->rowCount() . '</b></p>';  

            echo'<div class="table-responsive">';//On rajoute cette div pour avoir un tableau reponsive

            echo'<table class="table table-bordered text-center">';
            echo '<tr>';
            echo '<th>id commande</th>';
            echo '<th>id membre</th>';
            echo '<th>Id produit</th>';
            echo '<th>prix</th>';
            echo '<th>date_enregistrement</th>';
            echo '<th>Suppr</th>';
            echo '</tr>';


            while($listecommande = $liste_commande->fetch(PDO::FETCH_ASSOC)){ // on fait une boucle
              
        echo '<tr>';
        echo '<th>'.$listecommande["id_commande"].' </th>';            
        echo '<th>'.$listecommande["id_membre"] . " - " .$listecommande["email"].'</th>';
        echo '<th>'.$listecommande['id_produit'].'</th>';
        echo '<th>'.$listecommande["prix"].'</th>';
        echo '<th>'.$listecommande["date_enregistrement"].' </th>';
        echo '<th><a href="?action=supprimer&id_commande='.$listecommande['id_commande'].'" onClick="return( confirm(\'Etes-vous certain de vouloir supprimer '.$listecommande['id_commande'].' ?\') )">Suppression</a></th>';
      
        echo '</tr>';
         }
                echo'</table>';
                echo '</div>';
//***********************************************************************
//***********************************************************************
//************************FIN AFFICHAGE DES ARTICLES*******************
//***********************************************************************
//***********************************************************************

include '../inc/footer.inc.php';



