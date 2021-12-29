<?php

/**
 * Fonction permettant de générer un token anti-CSRF
 * @return void
 */
function generateCSRFToken()
{
    // si le token n'a pas encore été créé
    if (empty($_SESSION['csrf_token'])) {
        // génération d'une suite de bytes pseudo-aléatoire transformée en représentation hexadécimale
        $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
}

/**
 * Fonction permettant de vérifier le token anti-CSRF
 * @param $token token anti-CSRF à vérifier
 * @return bool true s'il est valide, false sinon
 */
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
