<?php
/**
 * Fonction appelée dans l'index.php afin de retourner les détails d'un mail, eux-mêmes retournés par la
 * fonction getMailDetails située dans la partie model
 * @throws Exception
 */
function show_msg_details() {

    // la variable $mail est ensuite utilisée pour afficher les détails du mail dans la vue details.php
    $mail = getMailDetails($_GET['no'])->fetch();

    // les détails du message s'affichent seulement si l'utilisateur connecté en est le destinataire
    if ($mail['recipient'] == $_SESSION["no"]) {
        require('view/details.php');
    }
    else { // sinon une exception est lancée
        throw new Exception(ERROR_ACCESS_MESSAGE);
    }
}
?>