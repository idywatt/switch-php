<?php
include '../inc/init.inc.php';
include '../inc/function.inc.php';

if(!user_is_admin()) {
  header('location:' . URL . 'connexion.php');
  exit(); // bloque l'exécution du code 
}

$tableau = $pdo->query("SELECT * FROM avis, membre, salle
                       WHERE avis.id_membre = membre.id_membre
                       AND avis.id_salle = salle.id_salle");


$id_avis = '';
$id_salle = '';
$id_membre = '';
$commentaire = '';
$note = '';
$date_enregistrement = '';


//***********************************************************************
//***********************************************************************
//**************DEBUT SUPRESSION DES AVIS***************
//***********************************************************************
//***********************************************************************

if(isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_avis'])) {
  $suppression = $pdo->prepare("DELETE FROM avis WHERE id_avis = :id_avis");
  $suppression->bindParam(":id_avis", $_GET['id_avis'], PDO::PARAM_STR);
  $suppression->execute();
}

//***********************************************************************
//***********************************************************************
//*************************FIN SUPRESSION DES AVIS**********************
//***********************************************************************
//***********************************************************************

include '../inc/header.inc.php';
include '../inc/nav.inc.php';


?>
<div class="container">

    <div class="mt-5 mb-5">
        <h1 class="text-center">GESTION DES AVIS</h1>
        <p class="lead"><?php echo $msg; ?></p>
    </div>
    
 

  <div class="col-12">

    <?php

    //***********************************************************************
    //***********************************************************************
    //************************DEBUT AFFICHAGE DES AVIS***********************
    //***********************************************************************
    //***********************************************************************
            //on récupere les avis en bdd
            $liste_avis = $pdo->query("SELECT id_avis, avis.id_membre , id_salle, commentaire, note, avis.date_enregistrement, email FROM avis, membre WHERE avis.id_membre = membre.id_membre");

                echo '<p> nombre d\'avis : <b>' . $liste_avis->rowCount() . '</b></p>';  

                echo'<div class="table-responsive">';//On rajoute cette div pour avoir un tableau reponsive

                echo'<table class="table table-bordered text-center">';
                echo '<tr>';
                echo '<th>id avis</th>';
                echo '<th>id membre</th>';
                echo '<th>Id salle</th>';
                echo '<th>commentaire</th>';
                echo '<th>note / 5</th>';
                echo '<th>date_enregistrement</th>';
                echo '<th>Suppr</th>';
                echo '</tr>';


                while($listeavis = $liste_avis->fetch(PDO::FETCH_ASSOC)){ // on fait une boucle
                  
            echo '<tr>';
            echo '<th>'.$listeavis["id_avis"].' </th>';            
            echo '<th>'.$listeavis["id_membre"] . " - " .$listeavis["email"].'</th>';
            echo '<th>'.$listeavis['id_salle'].'</th>';
            echo '<th>'.$listeavis["commentaire"].'</th>';
            echo '<th>'.$listeavis["note"].'</th>';
            echo '<th>'.$listeavis["date_enregistrement"].' </th>';
            echo '<th><a href="?action=supprimer&id_avis='.$listeavis['id_avis'].'" onClick="return( confirm(\'Etes-vous certain de vouloir supprimer '.$listeavis['id_avis'].' ?\') )">Suppression</a></th>';
          
            echo '</tr>';
             }
                    echo'</table>';
                    echo '</div>';
    //***********************************************************************
    //***********************************************************************
    //************************FIN AFFICHAGE DES ARTICLES*******************
    //***********************************************************************
    //***********************************************************************

    ?>
</div>      
      



  
  
<?php 
include '../inc/footer.inc.php';

