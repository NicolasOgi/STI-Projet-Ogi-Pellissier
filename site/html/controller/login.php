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
                $infoUser = checkLogin($_POST);
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
?>