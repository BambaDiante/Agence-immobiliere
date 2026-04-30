<?php
session_start();

// supprimer toutes les variables de session
session_unset();

// détruire la session
session_destroy();

// redirection vers l'accueil
header("Location: authentification.php");
exit;
?>