<?php
include 'inc/init.inc.php';
include 'inc/function.inc.php';





$id_avis = '';
$id_salle = $_GET["id_salle"] ;
$id_membre = $_SESSION["membre"]["id_membre"];
$commentaire = '';
$note = '';
$date_enregistrement = '';

if(   
    !empty($_POST['commentaire']) && 
    !empty($_POST['note']) && 
    !empty($_POST['id_salle'])
  )    
 {


// Si elles existent on les place dans des variables avec trim pour effacer les espaces
         $commentaire = trim($_POST['commentaire']);
         $note = trim($_POST['note']);

                             
              $enregistrement = $pdo->prepare("INSERT INTO avis (id_membre, id_salle, commentaire, note, date_enregistrement) VALUES (:id_membre, :id_salle, :commentaire, :note, NOW())");
                  

              $enregistrement->bindParam(":id_membre", $id_membre, PDO::PARAM_STR);
              $enregistrement->bindParam(":id_salle", $id_salle, PDO::PARAM_STR);
              $enregistrement->bindParam(":commentaire", $commentaire, PDO::PARAM_STR);
              $enregistrement->bindParam(":note", $note, PDO::PARAM_STR);
              $enregistrement->execute();

              header('location:' . URL . 'index.php');
     
}// if

include 'inc/header.inc.php';
include 'inc/nav.inc.php';


?>

    <div class="mt-5 mb-5">
        <h1 class="text-center">LAISSER UN AVIS</h1>
        <p class="lead"><?php echo $msg; ?></p>
    </div>
 

  <div class="col-12">

    <form method="post" action="?id_salle=<?php echo $id_salle; ?>" enctype="multipart/form-data">
      <!-- <input type="hidden" name="id_avis" value="<?php echo $id_avis; ?>"> -->
      <?php //echo "<pre>";var_dump($_POST);echo"</pre>"; ?>
      <div class="row">       
      <div class="col-6 mx-auto">         
        <div class="form-group">
          <label for="commentaire">Commentaire</label>
          <textarea name="commentaire" id="commentaire" rows="2" class="form-control"><?php echo $commentaire;?></textarea>
        </div>
        <div class="form-group">
          <label for="note">Note</label>
          <select name="note" id="note" class="form-control"> 
            <option <?php if($note == '1') {echo 'selected';} ?> >1</option>
            <option <?php if($note == '2') {echo 'selected';} ?> >2</option>
            <option <?php if($note == '3') {echo 'selected';} ?> >3</option>
            <option <?php if($note == '4') {echo 'selected';} ?> >4</option>
            <option <?php if($note == '5') {echo 'selected';} ?> >5</option>
          </select>
          <input type="hidden" name="id_salle" value="<?php echo $id_salle ?>" />
        </div>
        <div class="form-group">
            <button type="submit" id="enregistrement" class="form-control btn btn-outline-dark"> Enregistrer</button>
        </div>
      </div> <!-- col-6 mx-auto -->
      </div> <!-- class="row" -->
    </form>
  </div> <!-- class="col-12" -->
  



  
  
<?php 
include 'inc/footer.inc.php';