<?php

function generateCSRFToken()
{
    // si le token n'a pas encore été créé
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32)); // génération d'une suite de bytes pseudo-aléatoire
    }
}


function verifyCSRFToken($token)
{
    // si le token anti-CSRF contenu dans la session existe et qu'il a bien été envoyé
    if(isset($_SESSION['csrf_token']) && isset($token)) {

        // si le token reçu est bien égal à celui de la session
        if($_SESSION['csrf_token'] === $token) {
            return true;
        }
    }
    return false;
}
?>
