<?php 
include '../inc/init.inc.php';
include '../inc/function.inc.php';

if(!isset($_GET['id_produit'])) {
  header('location:index.php');
}

$id_membre = false; // Par default id_membre est false
if(user_is_connect()) {
  $id_membre = $_SESSION['membre']['id_membre']; // Si session membre existe, alors on récupère l'id du membre
}

$fiche = $pdo->prepare("SELECT * FROM produit, salle WHERE salle.id_salle = produit.id_salle AND produit.id_produit = :id_produit");
$fiche->bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_STR);
$fiche->execute();

$fiche_produit = $fiche->fetch(PDO::FETCH_ASSOC);

$avis = $pdo->prepare("SELECT commentaire FROM avis WHERE id_salle=:id_salle ");
$avis->bindParam(':id_salle', $fiche_produit['id_salle'], PDO::PARAM_STR);
$avis->execute();

if($fiche->rowCount() < 1) {
  header('location:index.php');
}

// Pour la reservation
if(isset($_GET['action']) && $_GET['action'] == "reserver" && $id_membre) { // Si nous sommes en mode reservé et qu'on a un membre en session
  $enregistrement = $pdo->prepare("INSERT INTO commande (id_membre, id_produit, date_enregistrement) VALUES (:id_membre, :id_produit, NOW())");

        
        $enregistrement->bindParam(":id_membre", $id_membre, PDO::PARAM_STR);
        $enregistrement->bindParam(":id_produit", $fiche_produit['id_produit'], PDO::PARAM_STR);
        $enregistrement->execute();

        if(user_is_admin()) {
            header('location:gestion_commande.php');
        }
        else {
            header('location:../mes_commandes.php');
        }
}

/*$produit = $infos_produit->fetch(PDO::FETCH_ASSOC);*/

include '../inc/header.inc.php';
include '../inc/nav.inc.php';
//echo '<pre>'; var_dump($article); echo '</pre>';
?>

<div class="container">

  <div>
    <div>
      <h1 class="text-center mb-5"><?php echo ucfirst($fiche_produit['categorie']) .' '. $fiche_produit['titre']; ?></h1>
    </div>
    <div class="col-6 form-group mx-auto">
        <button type="submit" id="avis" class="form-control btn btn-outline-dark"><a href="<?php echo URL; ?>laisser_avis.php?id_salle=<?php echo $fiche_produit['id_salle'] ?>" >Laisser un commentaire</a></button>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="text-center mx-auto mb-md-5">
          <p class=""><b>Decription</b></p>
          <div><?php echo $fiche_produit['description'];?></div>
      </div>
      <div class="row">
        <div class="col-md-6 mt-md-5 mb-md-2">
          <img src="<?php echo URL . 'img/' . $fiche_produit['photo']; ?>" alt="<?php echo $fiche_produit['titre']; ?>" class="w-100 img-thumbnail">
        </div>
        <div class="col-md-6">
          <div class="text-center">
            <div><iframe src="https://maps.google.fr/maps?q=<?= $fiche_produit['adresse'].$fiche_produit['cp']. $fiche_produit['ville'] ?>&output=embed" width="450" height="450" frameborder="0" style="border:0" allowfullscreen=""></iframe></div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 text-center mt-5">
      <div class="row">
        <div class="col-4">
          <p><b>Arrivée : </b><?php echo $fiche_produit['date_arrivee']; ?></p>
          <p><b>depart : </b><?php echo $fiche_produit['date_depart']; ?></p>
        </div>
        <div class="col-4">
          <p><b>Capacité : </b><?php echo $fiche_produit['capacite']; ?></p>
          <p><b>Catégorie : </b><?php echo $fiche_produit['categorie']; ?></p>
        </div>
        <div class="col-4">
          <p><b>Adresse : </b><?php echo $fiche_produit['adresse'] . ' ' .$fiche_produit['cp'] . ' '. $fiche_produit['ville'] ; ?></p>
          <p><b>Tarif : </b><?php echo $fiche_produit['prix'] . '€'; ?></p>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 text-center mt-5">
    <h4>Le derniers commentaires</h4>
    <?php while ($liste_avis = $avis->fetch(PDO::FETCH_ASSOC)) { ?>
      <div>
        <?php echo '"<i>' . $liste_avis["commentaire"] . '</i>"' ?>
      </div>
    <?php } ?>
  </div>

  <?php if($id_membre) { ?>    
  <div class="row">
    <div class="col-4 mx-auto mt-3">
      <a href="?id_produit=<?php echo $fiche_produit["id_produit"] ?>&action=reserver" id="reservation" class="form-control btn btn-outline-dark">Reserver</a>
    </div>
  </div>
  <?php } ?>


</div><!-- container -->

<?php 
include '../inc/footer.inc.php';

