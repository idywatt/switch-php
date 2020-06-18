<?php
include 'inc/init.inc.php';
include 'inc/function.inc.php';


//var_dump($_POST);  

include 'inc/header.inc.php';
include 'inc/nav.inc.php';       
?>


  <div class="container" style="margin-top: 100px;"> 

 <!--  DEBUT FORMULAIRE INSCRIPTION -->
        <div class="row justify-content-center"> 
            <div class="col-md-8">
                    <div class="card text-center">
                        <div class="card-header">Nous contacter</div>
                        <div class="card-body">
                            <form name="my-form" action="" method="post">
                                <div class="form-group row">
                                    <label for="full_name" class="col-md-4 col-form-label text-md-right">Nom</label>
                                    <div class="col-md-6">
                                        <input type="text" id="nom" value="nom" class="form-control" name="nom">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="phone_number" class="col-md-4 col-form-label text-md-right">Prenom</label>
                                    <div class="col-md-6">
                                        <input type="text" id="prenom" class="form-control" value="" name="prenom">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="present_address" class="col-md-4 col-form-label text-md-right">Email</label>
                                    <div class="col-md-6">
                                        <input type="text" id="email" value="email" class="form-control" name="email">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="user_name" class="col-md-4 col-form-label text-md-right">Votre demande</label>
                                    <div class="col-md-6">
                                        <textarea name="commentaire" id="commentaire" rows="2" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                    Envoyer votre demande
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