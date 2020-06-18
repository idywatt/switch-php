<nav class="navbar navbar-expand navbar-light bg-light">
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto mx-auto">
      <li class="nav-item">
        <a class="nav-link" href="<?php echo URL; ?>index.php">Accueil</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Membre
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
           <?php if(!user_is_connect()) { ?>  <!-- Si je suis déconnécté :lien inscription et connection-->
          <a class="dropdown-item" href="<?php echo URL; ?>connexion.php">Connexion</a>
          <a class="dropdown-item" href="<?php echo URL; ?>inscription.php">Inscription</a>

          <?php } else { ?> <!-- Si je suis connécté :lien profil et deconnection-->
          <a class="dropdown-item" href="<?php echo URL; ?>profil.php">Profil</a>
          <a class="dropdown-item" href="<?php echo URL; ?>mes_commandes.php">Voir mes commandes</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo URL; ?>connexion.php?action=deconnexion">Deconnexion</a>
      </li>
      <?php } ?>


      <?php if(user_is_admin()) : ?>

      <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Admin</a>
      <div class="dropdown-menu" aria-labelledby="dropdown01">
        <a class="dropdown-item" href="<?php echo URL; ?>admin/gestion_produit.php">gestion des produits</a>
        <a class="dropdown-item" href="<?php echo URL; ?>admin/gestion_membre.php">Gestion des membres</a>
        <a class="dropdown-item" href="<?php echo URL; ?>admin/gestion_salle.php">Gestion des salles</a>
        <a class="dropdown-item" href="<?php echo URL; ?>admin/gestion_commande.php">Gestion des commandes</a>
        <a class="dropdown-item" href="<?php echo URL; ?>admin/gestion_avis.php">Gestion des avis</a>
      </div>
      </li>

    <?php endif; ?>
      <li class="nav-item">
        <a class="nav-link" href="contact.php">Contact</a>
      </li>
    </ul>
  </div>
</nav>
