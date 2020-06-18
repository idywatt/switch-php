<?php
include 'inc/init.inc.php';
include 'inc/function.inc.php';



$pseudo = '';
$mdp = '';
$nom = '';
$prenom = '';
$email = '';
$civilite = '';
$ville = '';

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


                if($verif_pseudo->rowCount() > 0) {
                    //Si le nombre de ligne est supérieur à 0, alors le pseudo est déja utilisé
                    $msg .= '<div class="alert alert-danger mt3">Pseudo indisponible</div>';
                }else {
                    // insert into
                    // cryptage du mot de passe pour l'insertion en BDD
                    $mdp = password_hash($mdp, PASSWORD_DEFAULT);


               // Insertion des saisies dans la table « annuaire » de la base de données  membre 
                 $enregistrement = $pdo->prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, statut, date_enregistrement) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :civilite, 1, NOW())");

                    $enregistrement->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
                    $enregistrement->bindParam(":mdp", $mdp, PDO::PARAM_STR);
                    $enregistrement->bindParam(":nom", $nom, PDO::PARAM_STR);
                    $enregistrement->bindParam(":prenom", $prenom, PDO::PARAM_STR);
                    $enregistrement->bindParam(":email", $email, PDO::PARAM_STR);
                    $enregistrement->bindParam(":civilite", $civilite, PDO::PARAM_STR);
                    $enregistrement->execute();



                } //else

           } //if(!$verif_caracter   

     
}// if

//var_dump($_POST);  

include 'inc/header.inc.php';
include 'inc/nav.inc.php';       
?>


  <div class="container" style="margin-top: 100px;"> 

 <!--  DEBUT FORMULAIRE INSCRIPTION -->
        <div class="row justify-content-center"> 
            <div class="col-md-8">
                    <div class="card text-center">
                        <div class="card-header">Inscription</div>
                        <p class="lead"> <?php echo $msg; ?></p>
                        <div class="card-body">
                            <form name="my-form" action="#" method="post">
                                <div class="form-group row">
                                    <label for="sseudo" class="col-md-4 col-form-label text-md-right">pseudo</label>
                                    <div class="col-md-6">
                                        <input type="text" id="pseudo" value="<?php echo $pseudo; ?>" class="form-control" name="pseudo">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="mdp" class="col-md-4 col-form-label text-md-right">Mot de passe</label>
                                    <div class="col-md-6">
                                        <input type="password" id="mdp" class="form-control" value="mdp" name="mdp">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="nom" class="col-md-4 col-form-label text-md-right">Nom</label>
                                    <div class="col-md-6">
                                        <input type="text" id="nom" class="form-control" value="<?php echo $nom; ?>" name="nom">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="prenom" class="col-md-4 col-form-label text-md-right">Prenom</label>
                                    <div class="col-md-6">
                                        <input type="text" id="prenom" class="form-control" value="<?php echo $prenom; ?>" name="prenom">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>
                                    <div class="col-md-6">
                                        <input type="text" id="email" value="<?php echo $email; ?>" class="form-control" name="email">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="civilite" class="col-md-4 col-form-label text-md-right">Civilite</label>
                                    <div class="col-md-6">
                                      <select name="civilite" id="civilite" class="form-control">
                                        <option value="m">Homme</option>
                                        <option value="f"  >Femme</option> <!-- On met un select afin que le champs 'femme' ne se reinitialise pas en cas d'erreur sur le formulaire -->
                                      </select>
                                    </div>
                                </div>

                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                    S'inscrire
                                    </button>
                                </div>
                            </form>
                        </div> <!-- class="card-body" -->
                    </div> <!-- <div class="card"> -->
            </div> <!-- class="col-md-8" -->
        </div> <!-- class="row justify-content-center  -->
    </div> <!-- FIN CLASSE CONTAINER -->

<?php
include 'inc/footer.inc.php';