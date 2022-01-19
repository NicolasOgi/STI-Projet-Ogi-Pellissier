<?php
/**
 * Fonction permettant de vérifier la validité de la paire username/password dans la page de login
 */
function login() {
    // récupération des variables POST
    if (isset($_POST['fLogin']) && isset($_POST['fPasswd']) && !empty($_POST['g-recaptcha-response'])) {
        try {

            // vérification du CAPTCHA
            $secret = '6Le6JdQdAAAAAHtDLuOOdbXJPz66I6nyLkqCy_eY';
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
            $responseData = json_decode($verifyResponse);

            // Si le CAPTCHA est valide alors les credentials sont vérifiés
            if($responseData->success) {
                // vérification des credentials, si la fonction checkLogin retourne qqch alors ils sont valides
                $infoUser = check_login($_POST);
                if ($infoUser) {
                    // initialise les variables de session nécessaires pour être identifié sur le site avec son compte
                    $_SESSION["isConnected"] = true;
                    $_SESSION["no"] = $infoUser['no'];
                    $_SESSION['username'] = $infoUser['username'];
                    $_SESSION['valid'] = $infoUser['valid'];
                    $_SESSION['role'] = $infoUser['role'];
                    $_SESSION['message'] = "";

                    // si le compte est activé, l'utilisateur est redirigé dans la mailbox
                    if($_SESSION['valid'] == 1) {
                        @header("location: index.php?action=home");
                    }
                    else { // sinon la page de login s'affiche avec un message d'erreur
                        $_SESSION['message'] = ERROR_USER_DISABLED;
                        require "view/login.php";
                    }
                }
            }
            else { // Sinon la page de login est rechargée et un message d'erreur est affiché
                $_SESSION['message'] = ERROR_CAPTCHA;
                require "view/login.php";
            }

        } catch (Exception $e) {
            $_SESSION['message'] = $e->getMessage();
            @header("location: index.php?action=home");
        }
    }
    else {
        // si les variables POST ne sont pas set mais que la variable de session 'username' l'est et que le compte est activé
        if (isset($_SESSION['username']) && $_SESSION['valid'] == 1) {
            // affiche la vue mailbox si l'utilisateur est connecté
            @header("location: index.php?action=home");
        }
        else { // si l'utilisateur n'est pas connecté
            require "view/login.php";
        }
    }
}

/**
 * Fonction permettant de détruire la session et de retourner sur la page de login
 */
function logout() {
    // Vérification du token anti-CSRF lors d'une déconnexion de l'utilisateur
    if (verifyCSRFToken($_GET['csrf_token'])) {
        session_destroy();
        $_SESSION['message'] = USER_LOGGED_OUT;
        require 'view/login.php';
    }
    else {
        $_SESSION['message'] = ERROR_CSRF_TOKEN;
        mailbox();
    }
}

/**
 * Fonction permettant de vérifier les credentials
 * @param array $postArray credentials envoyés dans le POST
 * @return array Infos de l'utilisateur
 */
function check_login($postArray)
{
    $username = $postArray["fLogin"];
    $passwdPost = $postArray["fPasswd"];
    $results = getUserByUsername($username)->fetch();

    // si le résultat de la fonction est vide, alors l'utilisateur n'existe pas
    if (empty($results['username'])) {
        // Faux hash pour que la vérification du mot de passe soit quand même effectuée afin d'éviter les timing attacks
        $hash = '$2y$10$ketJpZi7uOnjXX/jaRolFeqru6YEmpbSv2krw2nK0/Mm/PAcpAZp6';
    }
    else {
        $hash = $results['password'];
    }

    // vérification du mot de passe
    if (password_verify($passwdPost, $hash)) {
        // initialisation du tableau qui va contenir les informations de l'utilisateur
        $infoUser = array(
            'no' => $results['no'],
            'username' => $results['username'],
            'valid' => $results['valid'],
            'role' => $results['role'],
        );
    } else { // si le mot de passe est incorrect
        $_SESSION['message'] = ERROR_PASSWORD_OR_USER_INCORRECT;
        require 'view/login.php';
    }
    return @$infoUser; // renvoie le tableau contenant les infos de l'utilisateur
}
?>