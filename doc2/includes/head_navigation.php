<?php $url_site = "/"; ?>
<h4 class="nav-titre">Général</h4>
<ul class="nav nav-pills nav-stacked">
    <li <?php
    if ($url_site == $_SERVER["REQUEST_URI"])
        echo 'class="active"';
    else
        activeMenuIfContain("index"); ?>
    ><a href="index.php">Accueil</a></li>

    <?php if (estConnecte()) { ?>
        <li <?php activeMenuIfContain("api_gestion"); ?>><a aria-disabled="true" disabled class="disabled">Tableau de bord</a></li>
    <?php }else{ ?>
        <li <?php activeMenuIfContain("api_inscription"); ?>><a aria-disabled="true" disabled class="disabled">Obtenir une Clé d'API</a></li>
        <li <?php activeMenuIfContain("api_connexion"); ?>><a aria-disabled="true" disabled class="disabled">S'identifier</a></li>
    <?php } ?>

    <li><a aria-disabled="true" disabled class="disabled">Conditions d'utilisation</a></li>
</ul>

