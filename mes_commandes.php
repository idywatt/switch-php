<?php
include 'inc/init.inc.php';
include 'inc/function.inc.php';

/*$tableau = $pdo->query("SELECT * FROM commande, produit, membre
                       WHERE commande.id_produit = produit.id_produit
                       AND membre.id_membre = commande.id_membre
                       AND membre.id_membre = :id_membre");*/



$id_commande = '';
$id_membre = '';
$id_produit = '';
$prix = '';
$date_enregistrement = '';
$date_arrivee = '';
$date_depart = '';



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

include 'inc/header.inc.php';
include 'inc/nav.inc.php';


?>



    <div class="mt-5 mb-5">
        <h1 class="text-center">MES COMMANDES</h1>
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
        $liste_commande = $pdo->query("SELECT id_commande, commande.id_membre , commande.id_produit, commande.date_enregistrement, prix, date_arrivee, date_depart FROM produit, commande, membre WHERE commande.id_produit = produit.id_produit AND membre.id_membre = commande.id_membre");


            echo '<p> nombre de commandes : <b>' . $liste_commande->rowCount() . '</b></p>';  

            echo'<div class="table-responsive">';//On rajoute cette div pour avoir un tableau reponsive

            echo'<table class="table table-bordered text-center">';
            echo '<tr>';
            echo '<th>N° commande</th>';
            echo '<th>Date arrivée</th>';
            echo '<th>Date depart</th>';
            echo '<th>prix</th>';
            echo '<th>date d\'achat</th>';
            echo '</tr>';


            while($listecommande = $liste_commande->fetch(PDO::FETCH_ASSOC)){ // on fait une boucle
              
        echo '<tr>';

        echo '<th>'.$listecommande["id_commande"].' </th>';            
        echo '<th>'.$listecommande['date_arrivee'].'</th>';
        echo '<th>'.$listecommande['date_depart'].'</th>';
        echo '<th>'.$listecommande["prix"].'</th>';
        echo '<th>'.$listecommande["date_enregistrement"].' </th>';
      
        echo '</tr>';
         }
                echo'</table>';
                echo '</div>';
//***********************************************************************
//***********************************************************************
//************************FIN AFFICHAGE DES ARTICLES*******************
//***********************************************************************
//***********************************************************************

include 'inc/footer.inc.php';



