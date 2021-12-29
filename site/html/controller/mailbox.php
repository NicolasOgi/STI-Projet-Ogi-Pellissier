<?php

/**
 * Fonction permettant de récupérer tous les mails d'un utilisateur afin de les afficher dans la maibox
 */
function mailbox(){
    // appel à la fonction getUserMails du model pour récupérer les mails, la variable $mails sera ensuite utilisée dans
    // la vue mailbox.php pour afficher les infos des mails
    $mails = getUserMails($_SESSION['no'])->fetchAll();
    require('view/mailbox.php');
}

/**
 * Fonction permettant de supprimer un mail en fonction de son numéro reçu par un paramètre GET
 * @throws Exception
 */
function deleteMail(){

    if (verifyCSRFToken($_GET['csrf_token'])) {
        // Les détails du mail à supprimer sont récupérés dans un premier temps afin de vérifier que l'utilisateur connecté
        // ne puisse supprimer que les mails dont il est le destinataire
        $mail = getMailDetails($_GET['no'])->fetch();

        if ($mail['recipient'] == $_SESSION["no"]) {
            // appel à la fonction présente dans le model
            delMail($_GET['no']);
            mailbox();
        }
        else { // sinon une exception est lancée
            throw new Exception('You do not have the rights to delete this message');
        }
    }
    else {
        $_SESSION['message'] = "The anti-CSRF token couldn't be verified";
        mailbox();
    }
}
?>