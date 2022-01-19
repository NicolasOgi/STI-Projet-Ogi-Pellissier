<?php
/**
 * Fonction permettant d'afficher une alerte Bootstrap
 * @return void
 */
function display_alert() {
    if(isset($_SESSION['message']) && $_SESSION['message'] != "") {
        // Si c'est une information alors alert-success, sinon si c'est une erreur alors alert-danger
        switch($_SESSION['message']) {
            case MESSAGE_SENT:
            case USER_MODIFIED:
            case USER_CREATED:
            case PASSWORD_UPDATED:
            case USER_LOGGED_OUT:
            case USER_DELETED:
            case MESSAGE_DELETED:
                $alert = "alert-success";
                break;
            default:
                $alert = "alert-danger";
        }

        echo '<div class="alert '. $alert . '">' .
            htmlspecialchars($_SESSION['message']) .
            '</div>';
        $_SESSION['message'] = "";
    }
}
?>