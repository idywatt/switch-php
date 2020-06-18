<?php
include 'inc/init.inc.php';
include 'inc/function.inc.php';



$capacite = '';
$salle ='';
$categorie ='';
$prix ='';
$date_depart = '';
$ate_arrivee = '';
// 
$query = "SELECT * FROM salle INNER JOIN produit ON salle.id_salle = produit.id_salle WHERE produit.date_depart >= NOW()";
$queryCategorie = "SELECT DISTINCT categorie FROM salle";
$queryVille = "SELECT DISTINCT ville FROM salle";

$tableau1 = $pdo->query($queryCategorie);
$tableau2 = $pdo->query($queryVille);

// TOUT AFFICHER


// CHERCHER PAR CATEGORIE
if(isset($_GET['categorie'])){
  $choix_categorie = $_GET['categorie'];
  $query .= " AND salle.categorie = '" . $choix_categorie . "'";
}


// CHERCHER PAR VILLE
if(isset($_GET['ville'])){
  $choix_ville = $_GET['ville'];
  $query .= " AND salle.ville = '" . $choix_ville . "'";
}

// CHERCHER PAR CAPACITE
if(isset($_GET['capacite'])){
  $choix_capacite = $_GET['capacite'] + 1;
  echo $choix_capacite;
  if($_GET['capacite'] === "+20") {
    $where = " > 20";
  } else {
    $where = " BETWEEN 0 AND " . $choix_capacite;
  }
  $query .= " AND salle.capacite ". $where;
}

// CHERCHER PAR PRIX
if(isset($_GET['prix'])){
  $choix_prix = $_GET['prix'];
  
  if($_GET['prix'] === "+1500") {
    $where = " > 1500";
  } elseif($_GET['prix'] === "1500") {
    $where = " BETWEEN 501 AND " . $choix_prix;
  } else {
    $where = " BETWEEN 0 AND " . $choix_prix;
  }
  $query .= " AND produit.prix ". $where;
}

// CHERCHER PAR DATE
// Affichera toutes les salles comprises entre la date d'arrivée et date de départ choisie
if(!empty($_GET['date_arrivee']) && !empty($_GET['date_depart'])){
  $where = " AND date_arrivee >= '" . $_GET['date_arrivee'] . "' AND date_depart <= '" . $_GET['date_depart'] . "'";
  $query .= $where;
 
}

$tableau3 = $pdo->query($query);

include 'inc/header.inc.php';
include 'inc/nav.inc.php';
?>

<div class="container mt-5">
  <div class="row">
    <div class="col-md-3 text-center">


        <div> 
          <?php 
// ************************AFFICHER TOUS LES PRDUITS****************
      echo '<a class="form-control btn btn-outline-dark mb-5" href="'. URL . '"> Afficher tous les produits</a>';

// *********************CATEGORIE************************ 
             echo '<ul class="list-group">
                      <li class="list-group-item active">Catégories</li>';            

             while($liste_categorie = $tableau1->fetch(PDO::FETCH_ASSOC)) {
                // echo '<pre>'; var_dump($categorie); echo '</pre><hr>';
                echo '<li class="list-group-item">
                <a href="?categorie=' . $liste_categorie['categorie'] . '">' . $liste_categorie['categorie'] . '</a></li>';
             } 
              
                echo '</ul>';
           ?>
        </div>
        <!-- ********************* TRI PAR VILLE************************ -->
        <div class="mt-5"> 
         <?php

           
             echo '<ul class="list-group">
                      <li class="list-group-item active">Ville</li>';     

             while($liste_ville = $tableau2->fetch(PDO::FETCH_ASSOC)) {
                // echo '<pre>'; var_dump($categorie); echo '</pre><hr>';
                echo '<li class="list-group-item">
                <a href="?ville=' . $liste_ville['ville'] . '">' . $liste_ville['ville'] . '</a></li>';
             } 
             
                echo '</ul>';
           ?>
        </div> 
        <!-- ********************* TRI PAR CAPACITE************************ -->
        <div class="form-group mt-5"> 
                <form action="#" method="get">
                  <label for="ville"><b>Capacite</b></label>
                  <select name="capacite" id="capacite" class="form-control">
                    <option>Capacite</option>
                    <option value="5"> <?php if($capacite == '5') {echo 'selected';} ?> >5 personnes</option>
                    <option value="20"> <?php if($capacite == '20') {echo 'selected';} ?> >5 à 20 Personnes</option>
                    <option value="+20"> <?php if($capacite == '+20') {echo 'selected';} ?> >plus de 21 personnes</option>
                  </select>
                  <input class="mt-3" type="submit" value="valider capacite">
                </form>
        </div>

                <!-- *********************TRI PAR TARIF************************ -->
        <div class="form-group mt-5"> 
                <form action="#" method="get">
                  <label for="ville"><b>Tarif</b></label>
                  <select name="prix" id="prix" class="form-control">
                    <option>Tarif</option>
                    <option value="500"> <?php if($prix == '500') {echo 'selected';} ?> >de 0 à 500€</option>
                    <option value="1500"> <?php if($prix == '1500') {echo 'selected';} ?> >501 à 1500€</option>
                    <option value="+1500"> <?php if($prix == '+1500') {echo 'selected';} ?> >plus de 1500€</option>
                  </select>
                  <input class="mt-3" type="submit" value="valider tarif">
                </form>
        </div>
       
          <!-- *********************TRI PAR DATE************************ -->
          <form action="#" method="get">
          <div class="form-group">
            <p><b>Periode</b></p>
            <label for="date_arrivee">Date d'arrivée</label>
            <input type="date" min="<?= date('Y-m-d'); ?>" name="date_arrivee" id="date_arrivee" value="" class="form-control">
          </div>
          <div class="form-group">  
            <label for="date_depart">Date de depart</label>
            <input min="<?= date('Y-m-d'); ?>" type="date" rows="2" name="date_depart" id="date_depart" value="" class="form-control">
          </div>
          <input type="submit" value="Filtrer par date">
        </form>

        
    </div> <!-- col-3 text-center -->

  
  

    <div class="col-md-9 mx-auto">
      <div class="row">
                   <!-- Il faut récupeérer la liste des catégories article en BDD pour les afficher dans des liens a href="" dasn une liste ul li -->
              <?php 

              //Affichage des articles
              while ($salle = $tableau3->fetch(PDO::FETCH_ASSOC)) {
                 //echo'<pre>'; var_dump($article); echo '</pre><hr>';

                  echo '<div class="col-sm-4 text-center p-2 mx-auto" style="border:1px solid black">';

                  echo '<h5>' . $salle['titre'] .'</h5>';

                  echo'<img src="' . URL . 'img/' . $salle['photo'] . '"alt="'. $salle['titre'] . '"class="img-thumbnail w-100">';

                          // Afficher la categorie et le prix
                          echo '<p><b>Catégorie : </b>' . $salle['categorie'] . '</p><br>';
                          echo '<p><b>Description :</b>' . substr($salle['description'], 0, 70) . '...</p><br>';
                          echo '<p><b>Du :</b>' . $salle['date_arrivee'] . '</p><br>';               
                          echo '<p><b> au </b>' . $salle['date_depart'] . '</p><br>';
                          echo '<b>Prix :</b>' . $salle['prix'] . '€<br>';
                          
                                  //Lien vers la fiche produit
                          echo '<a href="admin/fiche_produit.php?id_produit=' . $salle['id_produit'].'" class="btn btn-primary w-100">Voir le produit</a><hr>';

                          
                         
                  echo '</div>';

              }

              ?>
      </div><!-- class="row" -->
    </div><!-- class="col-9 -->
  </div><!-- class="row -->
</div> <!-- class="container"> -->






<?php 
include 'inc/footer.inc.php';
