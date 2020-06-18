<?php
include '../inc/init.inc.php';
include '../inc/function.inc.php';



$pseudo = '';
$mdp = '';
$nom = '';
$prenom = '';
$email = '';
$civilite = '';
$statut = '';
$date_enregistrement ='';

//***********************************************************************
//***********************************************************************
//**************DEBUT SUPRESSION  DES SALLES***************
//***********************************************************************
//***********************************************************************

if(isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_membre'])) {
  $suppression = $pdo->prepare("DELETE FROM membre WHERE id_membre = :id_membre");
  $suppression->bindParam(":id_membre", $_GET['id_membre'], PDO::PARAM_STR);
  $suppression->execute();
}


//***********************************************************************
//***********************************************************************
//******************DEBUT MODIFICATION DES MEMBRES**********************
//***********************************************************************
//***********************************************************************


if(isset($_GET['action']) && $_GET['action'] == 'modifier' && !empty($_GET['id_membre'])) {
    $infos_membre = $pdo->prepare("SELECT * FROM membre WHERE id_membre = :id_membre");
    $infos_membre->bindParam(":id_membre", $_GET['id_membre'], PDO::PARAM_INT);
    $infos_membre->execute();
    

    if ($infos_membre->rowCount() > 0) {
      $membre_actuel = $infos_membre->fetch(PDO::FETCH_ASSOC);


      $id_membre = $membre_actuel['id_membre'];
      $pseudo = $membre_actuel['pseudo'];
      $nom = $membre_actuel['nom'];
      $prenom = $membre_actuel['prenom'];
      $email = $membre_actuel['email'];
      $civilite = $membre_actuel['civilite'];
      $statut = $membre_actuel['statut'];
      $mdpBDD = $membre_actuel['mdp'];

    }

}

//***********************************************************************
//***********************************************************************
//****************FIN MODIFICATION  DES MEMBRES***************
//***********************************************************************
//***********************************************************************


// On controle l'existance des champs de formulaire
if(
    isset($_POST['pseudo']) && 
    isset($_POST['mdp']) &&
    isset($_POST['nom']) && 
    isset($_POST['prenom']) && 
    isset($_POST['email']) &&  
    isset($_POST['civilite'])) {

//Si elles existent, afficher 'TEST'
//echo'TEST'; 


// Si elles existent on les place dans des variables avec trim pour effacer les espaces
         $pseudo = trim($_POST['pseudo']);
         $mdp = trim($_POST['mdp']);
         $nom = trim($_POST['nom']);
         $prenom = trim($_POST['prenom']);
         $email = trim($_POST['email']);
         $civilite = trim($_POST['civilite']);


         // On bloque certains caracteres pour le champ pseudo via une expression réguliere (regex).
         // On autorise uniquement a-z ,  A-Z , 1-9
         $verif_caractere = preg_match('#^[a-zA-Z0-9._-]+$#', $pseudo);

           if(!$verif_caractere && !empty($pseudo)) { //Si le pseudo n'est pas vide, on vérifie qu'il n'y a pas de caractere speciaux
            //cas d'erreur
            $msg .= '<div class="alert alert-danger mt3">Pseudo invalide caractere autorisés : a-z et de 0-9</div>';
           }
           

           // Verifier taille du pseudo => Message d'erreur  la taille du pseudo n'est pas entre 4 et 14 caracteres inclus.
           if(iconv_strlen($pseudo) < 4 || iconv_strlen($pseudo) > 14) {
            $msg .= '<div class="alert alert-danger mt3">Vote pseudo doit faire entre 4 et 14 caracteres inclus</div>';
           }

           if (empty($msg)) {
                // Si la variable $msg est vide, alors il n'y a pas eu d'erreur dans nos controle.

                // On vérifie si le pseudo est disponible
                $verif_pseudo =$pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
                $verif_pseudo->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
                $verif_pseudo->execute();


                if(isset($_GET['action']) && $_GET['action'] == "ajouter" && $verif_pseudo->rowCount() > 0) {
                    //Si le nombre de ligne est supérieur à 0, alors le pseudo est déja utilisé
                    $msg .= '<div class="alert alert-danger mt3">Pseudo indisponible</div>';
                }else {
                    // insert into
                    // cryptage du mot de passe pour l'insertion en BDD si ajout OU modification et input non vide, sinon on envoi le mdp en bdd tel quel.
                    if((isset($_GET['action']) && $_GET['action'] == 'ajouter') || (isset($_GET['action']) && $_GET['action'] == 'modifier' && iconv_strlen(trim($mdp)) > 0 ) ) {
                      $mdp = password_hash($mdp, PASSWORD_DEFAULT);
                    }
                    else {
                      $mdp = $mdpBDD;
                    }

               // Insertion des saisies dans la table « membre » de la base de données  membre 
                 
                     if(empty($msg)) { 
                        if (!empty($id_membre)) {
                          // Si titre n'est pas vide c'est un update (modification)
                          $enregistrement = $pdo->prepare("UPDATE membre SET pseudo = :pseudo, mdp = :mdp, nom = :nom, prenom = :prenom, email = :email, civilite = :civilite, statut = :statut WHERE id_membre = :id_membre");

                          //On ajoute le bindParam pour l'id_membre CAR +> MODIFICATION
                          $enregistrement->bindParam(":id_membre", $id_membre, PDO::PARAM_STR);

                          $msg .= '<div class="alert alert-success mt3">La modification du membre a bien été prise en compte</div>';
                        } else {
                          // sinon c'est un INSERT
                          $enregistrement = $pdo->prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, statut, date_enregistrement) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :civilite, :statut , NOW())");

                          $msg .= '<div class="alert alert-success mt3">Le membre a bien été ajouté</div>';
                              }

                          $enregistrement->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
                          $enregistrement->bindParam(":mdp", $mdp, PDO::PARAM_STR);
                          $enregistrement->bindParam(":nom", $nom, PDO::PARAM_STR);
                          $enregistrement->bindParam(":prenom", $prenom, PDO::PARAM_STR);
                          $enregistrement->bindParam(":email", $email, PDO::PARAM_STR);
                          $enregistrement->bindParam(":civilite", $civilite, PDO::PARAM_STR);
                          $enregistrement->bindParam(":statut", $statut, PDO::PARAM_INT);
                          $enregistrement->execute();

                    }//if(empty($msg))

                } //else

           } //if(!$verif_caracter   

     
}// if

//***********************************************************************
//***********************************************************************
//******************FIN MODIFICATION DES MEMBRES**********************
//***********************************************************************
//***********************************************************************

include '../inc/header.inc.php';
include '../inc/nav.inc.php';       
?>


  <div class="container" style="margin-top: 100px;"> 

 <!--  DEBUT FORMULAIRE INSCRIPTION -->

    <div class="mt-5 mb-5">
        <h1 class="text-center">GESTION DES MEMBRES</h1>
      </div>
      <p class="text-center">
        <a href="?action=ajouter" class="btn btn-outline-danger">Ajout membres</a>
        <a href="?action=afficher" class="btn btn-outline-primary">Affichage membres</a>
      </p>
    </div>

<?php
//***********************************************************************
//***********************************************************************
//************************DEBUT AFFICHAGE DES MEMBRES*******************
//***********************************************************************
//***********************************************************************


          if(isset($_GET['action']) && $_GET['action'] == 'afficher') {
            //on récupere les membres en bdd
            $liste_membre = $pdo->query("SELECT * FROM membre");

            echo '<p> nombre de membres : <b>' . $liste_membre->rowCount() . '</b></p>';  

            echo'<div class="table-responsive">';//On rajoute cette dic pour avoir un tableau reponsive

            echo'<table class="table table-bordered text-center">';
            echo '<tr>';
            echo '<th>id_membre</th>';
            echo '<th>pseudo</th>';
            echo '<th>nom</th>';
            echo '<th>prenom</th>';          
            echo '<th>email</th>';
            echo '<th>civilite</th>';
            echo '<th>statut</th>';
            echo '<th>date_enregistrement</th>';
            echo '<th>Modif</th>';
            echo '<th>Suppr</th>';
            echo '</tr>';
            

            // Chaque tour de ce while est une ligne de notre tableau
            while ($membre = $liste_membre -> fetch(PDO :: FETCH_ASSOC)) {

              echo '<tr>';
              echo '<th>' . $membre['id_membre'] . '</th>';
              echo '<th>' . $membre['pseudo'] . '</th>';
              echo '<th>' . $membre['prenom'] . '</th>';
              echo '<th>' . $membre['nom'] . '</th>';
              echo '<th>' . $membre['email'] . '</td>';     
              echo '<th>' . $membre['civilite'] . '</th>';
              echo '<th>' . $membre['statut'] . '</th>';
              echo '<th>' . $membre['date_enregistrement'] . '</th>';
              echo'<th><a href ="?action=modifier&id_membre='. $membre['id_membre'] . '"class="btn btn-warning"><i class="fas fa-edit"></i></a></th>';
              echo'<th><a href =?action=supprimer&id_membre='. $membre['id_membre'] . '"class="btn btn-danger" onclick="return(confirm(\'Etes-vous sûr?\'))" ><i class="fas fa-trash-alt"></i></a></th>';

              echo '</tr>';

              
            }

                echo'</table>';
                echo '</div>';
          }


//***********************************************************************
//***********************************************************************
//************************FIN AFFICHAGE DES MEMBRES*********************
//***********************************************************************
//***********************************************************************
 if (isset($_GET['action']) && ($_GET['action'] == 'ajouter' || $_GET['action'] == 'modifier')) {      

?>
        <div class="row justify-content-center"> 
            <div class="col-md-8">
                    <div class="card text-center">
                        <div class="card-header">Ajout/modification d'un membre</div>
                        <p class="lead"> <?php echo $msg; ?></p>
                        <div class="card-body">
                            <form name="my-form" action="" method="post">
                                <div class="form-group row">
                                    <label for="full_name" class="col-md-4 col-form-label text-md-right">Pseudo</label>
                                    <div class="col-md-6">
                                        <input type="text" id="pseudo" value="<?php echo $pseudo; ?>" class="form-control" name="pseudo">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="email_address" class="col-md-4 col-form-label text-md-right">Mot de passe</label>
                                    <div class="col-md-6">
                                        <input placeholder="nouveau mot de passe" type="password" id="mdp" class="form-control" value="" name="mdp">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="user_name" class="col-md-4 col-form-label text-md-right">Nom</label>
                                    <div class="col-md-6">
                                        <input type="text" id="nom" class="form-control" value="<?php echo $nom; ?>" name="nom">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="phone_number" class="col-md-4 col-form-label text-md-right">Prenom</label>
                                    <div class="col-md-6">
                                        <input type="text" id="prenom" class="form-control" value="<?php echo $prenom; ?>" name="prenom">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="present_address" class="col-md-4 col-form-label text-md-right">Email</label>
                                    <div class="col-md-6">
                                        <input type="text" id="email" value="<?php echo $email; ?>" class="form-control" name="email">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="permanent_address" class="col-md-4 col-form-label text-md-right">Civilite</label>
                                    <div class="col-md-6">
                                      <select name="civilite" id="civilite" class="form-control">
                                        <option value="m">Homme</option>
                                        <option value="f"  >Femme</option> <!-- On met un select afin que le champs 'femme' ne se reinitialise pas en cas d'erreur sur le formulaire -->
                                      </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="permanent_address" class="col-md-4 col-form-label text-md-right">Statut</label>
                                    <div class="col-md-6">
                                      <select name="statut" id="statut" class="form-control">
                                        <option value="1">membre</option>
                                        <option value="2">Administrateur</option> <!-- On met un select afin que le champs 'femme' ne se reinitialise pas en cas d'erreur sur le formulaire -->
                                      </select>
                                    </div>
                                </div>
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                    Enregistrer
                                    </button>
                                </div>
                            </form>
    <?php
    } // fin du if(isset($_GET['action']) && $_GET['action'] == ajouter)
    ?> 
                        </div> <!-- class="card-body" -->
                    </div> <!-- <div class="card"> -->
            </div> <!-- class="col-md-8" -->
        </div> <!-- class="row justify-content-center  -->
    </div> <!-- FIN CLASSE CONTAINER -->

<?php
include '../inc/footer.inc.php';