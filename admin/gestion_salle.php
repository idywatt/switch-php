<?php 
include '../inc/init.inc.php';
include '../inc/function.inc.php';

if(!user_is_admin()) {
  header('location:' . URL . 'connexion.php');
  exit(); // bloque l'exécution du code 
}

$titre = '';
$description = '';
$photo = '';
$pays = '';
$ville = '';
$adresse = '';
$cp = '';
$capacite = '';
$categorie = '';

//***********************************************************************
//***********************************************************************
//******************DEBUT MODIFICATION DES SALLES**********************
//***********************************************************************
//***********************************************************************


if(isset($_GET['action']) && $_GET['action'] == 'modifier' && !empty($_GET['id_salle'])) {
    $infos_salle = $pdo->prepare("SELECT * FROM salle WHERE id_salle = :id_salle");
    $infos_salle->bindParam(":id_salle", $_GET['id_salle'], PDO::PARAM_STR);
    $infos_salle->execute();

    if ($infos_salle->rowCount() > 0) {
      $salle_actuelle = $infos_salle->fetch(PDO::FETCH_ASSOC);

      $id_salle = $salle_actuelle['id_salle'];
      $titre = $salle_actuelle['titre'];
      $description = $salle_actuelle['description'];
      $photo = $salle_actuelle['photo'];
      $pays = $salle_actuelle['pays'];
      $ville = $salle_actuelle['ville'];
      $adresse = $salle_actuelle['adresse'];
      $cp = $salle_actuelle['cp'];
      $capacite = $salle_actuelle['capacite'];
      $categorie = $salle_actuelle['categorie'];
      
    }

}

//***********************************************************************
//***********************************************************************
//******************FIN MODIFICATION DES SALLES**********************
//***********************************************************************
//***********************************************************************

//***********************************************************************
//***********************************************************************
//**************DEBUT SUPRESSION ENREGISTREMENT DES SALLES***************
//***********************************************************************
//***********************************************************************

if(isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_salle'])) {
  $suppression = $pdo->prepare("DELETE FROM salle WHERE id_salle = :id_salle");
  $suppression->bindParam(":id_salle", $_GET['id_salle'], PDO::PARAM_STR);
  $suppression->execute();
}
//***********************************************************************
//***********************************************************************
//****************FIN SUPRESSION T DES SALLES***************
//***********************************************************************
//***********************************************************************


//***********************************************************************
//***********************************************************************
//************************DEBUT ENREGISTREMENT DES SALLES***************
//***********************************************************************
//***********************************************************************

if(  isset($_POST['titre']) &&
   isset($_POST['description']) &&
   isset($_POST['pays']) &&
   isset($_POST['ville']) &&
   isset($_POST['adresse']) &&
   isset($_POST['cp']) &&
   isset($_POST['capacite']) &&
   isset($_POST['categorie']))  {


        
      $titre = trim($_POST['titre']);
      $description = trim($_POST['description']);
      /*$photo = trim($_POST['photo_actuelle']);*/
      $pays = trim($_POST['pays']);
      $ville = trim($_POST['ville']);
      $adresse = trim($_POST['adresse']);
      $cp = trim($_POST['cp']);
      $capacite = trim($_POST['capacite']);
      $categorie = trim($_POST['categorie']);

/*      // Récupération de la photo actuelle pour les modif
      if(!isset($_POST['photo_actuelle'])) {
        $photo_bdd = $_POST['photo_actuelle'];
      }*/


      // Contrôle sur le titre avec au moins 4 caracteres
       if(iconv_strlen($titre) < 4 ) {
          $msg .= '<div class="alert alert-danger mt3">Le titre doit faire au moins 4 caracteres</div>';
        }
        else {
          $verif_titre = $pdo->prepare("SELECT * FROM  salle WHERE titre = :titre");
          $verif_titre->bindParam(':titre', $titre, PDO::PARAM_STR);
          $verif_titre->execute();

           if (isset($_GET['action']) && $_GET['action'] == "ajouter" &&  $verif_titre->rowCount () > 0) {
              $msg .= '<div class="alert alert-danger mt3">Attention, titre indisponible car déja attribuée</div>';
            }
            else {
                  // Vérification du format de l'image. Formats accéptés :jpg, jpeg, png, gif
                 // est-ce qu'une image a été postée:

                 if(!empty($_FILES['photo']['name'])){

                  //on vérifie le format de l'image en récupérant son extension
                  $extention = strrchr(($_FILES['photo']['name']), '.' );
                  //strrchr() découpe la chaine fournie en 1e argument en partant de la fin. On remonte jusqu'au caractere fourni en 2e argument et on recupere le tout depuis ce caractere.
                  //exemple strrchr('image.png', '.')=> on recupere .png
                  //var_dump($extension);

                  // On enleve le point et on passe l'extention en miscule pour pouvoir la comparer
                  $extention = strtolower(substr($extention, 1));
                  //exemple: .PNG=>.png   .Jpeg=>.jpeg

                  //On déclare un tableau array contenant les extensions autorisées:
                  $tab_extention_valide = array('png','gif', 'jpg', 'jpeg',);

                  // in array(ce_quon-cherche, le tableau_quon_cherche);
                  //in array() renvoie true si le 1e arguement correspond à une des valeurs présentes dans le tableau array fourni en 2e argument. Sinon false
                  $verif_extension = in_array($extention, $tab_extention_valide);

                    if($verif_extension) {
                       
                      //Pour ne pas écraser une image du meme nom, on renomme l'image en rajoutant la référence qui est une information unique
                      $nom_photo = $titre . '-' . $_FILES['photo']['name'];

                      $photo_bdd = $nom_photo; // represente l'insertion photo

                      // On prépare le chemin ou on va enregistrer l'image
                      $photo_dossier = SERVER_ROOT . '/img/' . $nom_photo;
                      //var_dump($photo_dossier);

                      // copy(); permet de copier un fichier depuis un emplacement fourni en 1e argument vers un emplacement fourni en 2e argument

                      copy($_FILES['photo']['tmp_name'], $photo_dossier);

                   }  /*else{
                     $msg .= '<div class="alert alert-danger mt3">Attention, le format de la photo est invalide.Extensions autorisée : png, gif, jpg, jpeg</div>';
                      }*/


                }//if(!empty($_FILES['photo']['name']
            } //else
        } //if(iconv_strlen($titre) < 4 )



         // on peut déclencher l'enregistrement s'il n'y a pas eu d'erreur dans les traitement précédents
      if(empty($msg)) { 
        if (!empty($id_salle)) {
          // Si titre n'est pas vide c'est un update (modification)
          $enregistrement = $pdo->prepare("UPDATE salle SET titre = :titre, description = :description, photo = :photo, pays= :pays, ville = :ville, adresse = :adresse, cp = :cp, capacite = :capacite, categorie = :categorie WHERE id_salle = :id_salle");

          //On ajoute le bindParam pour l'id_article CAR +> MODIFICATION
          $enregistrement->bindParam(":id_salle", $id_salle, PDO::PARAM_STR);

        $msg .= '<div class="alert alert-success mt3">La salle a bien été modifiée</div>';
          
        } else {
          // sinon c'est un INSERT
          $enregistrement = $pdo->prepare("INSERT INTO salle (titre, description, photo, pays, ville, adresse, cp, capacite, categorie) VALUES (:titre, :description, :photo, :pays, :ville, :adresse, :cp, :capacite, :categorie)");

        $msg .= '<div class="alert alert-success mt3">La salle a bien été ajoutée</div>';
        }
        
        $enregistrement->bindParam(":titre", $titre, PDO::PARAM_STR);
        $enregistrement->bindParam(":description", $description, PDO::PARAM_STR);
        $enregistrement->bindParam(":photo", $nom_photo, PDO::PARAM_STR);      
        $enregistrement->bindParam(":pays", $pays, PDO::PARAM_STR);
        $enregistrement->bindParam(":ville", $ville, PDO::PARAM_STR);
        $enregistrement->bindParam(":adresse", $adresse, PDO::PARAM_STR);
        $enregistrement->bindParam(":cp", $cp, PDO::PARAM_STR);
        $enregistrement->bindParam(":capacite", $capacite, PDO::PARAM_STR);
        $enregistrement->bindParam(":categorie", $categorie, PDO::PARAM_STR);
        
        $enregistrement->execute();

      } // if(empty($msg))
} // if(  isset($_POST['titre']) &&

//***********************************************************************
//***********************************************************************
//************************FIN ENREGISTREMENT DES SALLES***************
//***********************************************************************
//***********************************************************************


include '../inc/header.inc.php';
include '../inc/nav.inc.php';        
?>

<div class="mt-5 mb-5">
    <h1 class="text-center">GESTION DES SALLES</h1>
    <p class="lead"><?php echo $msg; ?></p>
  </div>
  <p class="text-center">
    <a href="?action=ajouter" class="btn btn-outline-danger">Ajout SALLE</a>
    <a href="?action=affichage" class="btn btn-outline-primary">Affichage salle</a>
  </p>
</div>

<div class="container">
  

  <div class="row">
    <div class="col-12">

<?php 

//***********************************************************************
//***********************************************************************
//************************DEBUT AFFICHAGE DES SALLES*******************
//***********************************************************************
//***********************************************************************

          if(isset($_GET['action']) && $_GET['action'] == 'affichage') {
            //on récupere les articles en bdd
            $liste_salle = $pdo->query("SELECT * FROM salle");
            

            echo '<p> nombre de salles : <b>' . $liste_salle->rowCount() . '</b></p>';  

            echo'<div class="table-responsive">';//On rajoute cette dic pour avoir un tableau reponsive

            echo'<table class="table table-bordered">';
            echo '<tr>';
            echo '<th>id_salle</th>';
            echo '<th>titre</th>';
            echo '<th>description</th>';
            echo '<th>photo</th>';
            echo '<th>pays</th>';
            echo '<th>ville</th>';
            echo '<th>adresse</th>';
            echo '<th>cp</th>';
            echo '<th>capacite</th>';
            echo '<th>categorie</th>';
            echo '<th>Modif</th>';
            echo '<th>Suppr</th>';
            echo '</tr>';
            

            // Chaque tour de ce while est une ligne de notre tableau
            while ($salle = $liste_salle -> fetch(PDO :: FETCH_ASSOC)) {
              echo '<tr>';
              echo '<th>' . $salle['id_salle'] . '</th>';
              echo '<th>' . $salle['titre'] . '</th>';
              echo '<th>' . $salle['description'] . '</th>';
              echo '<th><img src ="' . URL . 'img/' . $salle['photo'] .'" class="img-thumbnail" width="140"</td>';
              echo '<th>' . $salle['pays'] . '</th>';
              echo '<th>' . substr($salle['ville'],0,14) . '</th>'; // on affiche uniquement les 14 premiers caracteres de la description (car trop longue)        
              echo '<th>' . $salle['adresse'] . '</th>';
              echo '<td>' . $salle['cp'] . '</th>';
              echo '<th>' . $salle['capacite'] . '</th>';
              echo '<th>' . $salle['categorie'] . '</th>';
              echo'<th><a href ="?action=modifier&id_salle='. $salle['id_salle'] . '"class="btn btn-warning"><i class="fas fa-edit"></i></a></th>';
              echo'<th><a href =?action=supprimer&id_salle='. $salle['id_salle'] . '"class="btn btn-danger" onclick="return(confirm(\'Etes-vous sûr?\'))" ><i class="fas fa-trash-alt"></i></a></th>';


              echo '</tr>';

              
            }

                echo'</table>';
                echo '</div>';
          }


//***********************************************************************
//***********************************************************************
//************************FIN AFFICHAGE DES SALLES*********************
//***********************************************************************
//***********************************************************************

//***********************************************************************
//***********************************************************************
//************************DEBUT FORMULAIRE AJOUT SALLES***************
//***********************************************************************
//***********************************************************************

// On affiche le form si l'utilisateur a cliqué sur le bouton "ajout d'article"
 if (isset($_GET['action']) && ($_GET['action'] == 'ajouter' || $_GET['action'] == 'modifier')) {      

?>
      <form method="post" enctype="multipart/form-data" action="">
         
        <div class="row">
           <div class="col-6">
               <div class="form-group">
                  <label for="reference">Titre</label>
                  <input type="text" name="titre" id="titre" value="<?php echo $titre; ?>" class="form-control">
                </div>  
                <div class="form-group">
                  <label for="description">Description</label>
                  <textarea name="description" id="description" rows="2" class="form-control"><?php echo $description; ?></textarea>
                </div> 
                <div class="form-group">
                  <label for="photo">Photo</label>
                  <input type="file" name="photo" id="photo" class="form-control">
                </div>
                <div class="form-group">
                  <label for="ccapacite">capacite</label>
                  <select name="capacite" id="capacite" class="form-control">
                    <option>5</option>
                    <option <?php if($capacite == '20') {echo 'selected';} ?> >20</option>
                    <option <?php if($capacite == '40') {echo 'selected';} ?> >40</option>
                    <option  <?php if($capacite == '50') {echo 'selected';} ?> >50</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="categorie">Categorie</label>
                  <select name="categorie" id="categorie" class="form-control">
                    <option <?php if($categorie == 'reunion') {echo 'selected';} ?> >reunion</option>
                    <option <?php if($categorie == 'bureau') {echo 'selected';} ?> >bureau</option>
                    <option  <?php if($categorie == 'formation') {echo 'selected';} ?> >formation</option>
                  </select>
                </div>
           </div> <!-- class="col-6" -->
           <div class="col-6">
                <div class="form-group">
                  <label for="pays">Pays</label>
                  <select name="pays" id="pays" class="form-control">
                    <option>Pays</option>
                    <option <?php if($pays == 'France') {echo 'selected';} ?> >France</option>
                    <option <?php if($pays == 'Belgique') {echo 'selected';} ?> >Belgique</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="ville">Ville</label>
                  <select name="ville" id="ville" class="form-control">
                    <option>ville</option>
                    <option <?php if($ville == 'Paris') {echo 'selected';} ?> >Paris</option>
                    <option <?php if($ville == 'Lyon') {echo 'selected';} ?> >Lyon</option>
                    <option <?php if($ville == 'Bruxelles') {echo 'selected';} ?> >Bruxelles</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="description">Adresse</label>
                  <textarea name="adresse" id="adresse" rows="2" class="form-control"><?php echo $adresse; ?></textarea>
                </div>
               <div class="form-group">
                  <label for="reference">Code postal</label>
                  <input type="text" name="cp" id="cp" value="<?php echo $cp; ?>" class="form-control">
                </div>
                <div class="form-group mt-5">
                  <button type="submit" name="enregistrement" id="enregistrement" class="form-control btn btn-outline-dark"> Enregistrer </button>
                </div> 
            </div> <!-- row -->               
          </div> <!-- class="col-6" -->
      </form>
            <?php
    } // fin du if(isset($_GET['action']) && $_GET['action'] == ajouter)
    ?> 
    </div> <!-- class="col-12" -->
  </div> <!-- row -->





</div> <!-- container -->


<?php 
include '../inc/footer.inc.php';