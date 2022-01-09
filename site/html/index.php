<?php
session_set_cookie_params(10000, null, null, null, true); // durée de vie de session si > destruction automatique et httpOnly à true
session_start();

// pour afficher les erreurs PHP
/*error_reporting(E_ALL);
ini_set("display_errors", 1);*/

require 'controller/controller.php';

// génération d'un token anti-CSRF
generateCSRFToken();

try {
    // Permet de rediriger sur les bonnes pages en fonction de ce qui est passé dans le paramètre GET action
    if (isset($_GET['action']) && $_SESSION['isConnected']) {
        $action = $_GET['action'];
        switch ($action) {
            case 'home' :
                home();
                break;
            case 'message' :
                new_msg();
                break;
            case 'details' :
                show_msg_details();
                break;
            case 'logout':
                logout();
                break;
            case 'login' :
                login();
                break;
            case 'admin':
                administration();
                break;
            case 'delete_mail':
                delete_msg();
                break;
            case 'change_password':
            case 'update_user':
                change_user_details();
                break;
            case 'delete_user':
                delete_user();
                break;
            case 'add_user':
                add_user();
                break;
            default :
                throw new Exception("Requested action unknown");
        }
    } else
        home();
} catch (Exception $e) {
    // si une exception non-gérée survient, on affiche une erreur 500
    http_response_code(500);
    echo $e->getMessage();
}