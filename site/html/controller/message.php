<?php
/**
 * Fonction permettant d'écrire un nouveau message et de l'envoyer
 * @throws Exception
 */
function new_msg(){

    // si les variables POST sont définies
    if (isset($_POST['recipient']) && isset($_POST['subject']) && isset($_POST['body'])) {

        // on vérifie le token anti-CSRF uniquement lors de l'envoi d'un message
        if (verifyCSRFToken($_POST['csrf_token'])) {

            // récupération du destinataire
            $results = getUserByUsername($_POST['recipient']);
            $results = $results->fetch();

            // si le résultat de la fonction getUserByLogin est vide, alors le destinataire n'existe pas
            if (empty($results['username'])) {
                $_SESSION['message'] = ERROR_USER_NOT_EXIST;
                require 'view/message.php';
            }
            else {
                // la date est utilisée dans ce format pour que les mails soient triés correctement dans la mailbox, même
                // avec des mails arrivés le même jour, cependant elle sera tronquée dans la vue pour n'afficher que la date
                // sans l'heure
                $date = date("Y-m-d H:i:s");
                // appel de la fonction qui permet d'inscrire le mail dans la DB
                sendMail($_SESSION['no'], $results['no'], $_POST['subject'], $_POST['body'], $date);
                $_SESSION['message'] = MESSAGE_SENT;
                mailbox();
            }
        }
        else {
            $_SESSION['message'] = ERROR_CSRF_TOKEN;
            require 'view/message.php';
        }
    }
    else {
        // si l'utilisateur répond à un mail, on récupère les infos de celui-ci afin de pouvoir les afficher dans la vue
        // message.php grâce à la variable $mail
        if (isset($_GET['reply'])) {

            $mail = getMailDetails($_GET['reply'])->fetch();

            // une exception est lancée si l'utilisateur connecté n'est pas le destinataire du message
            if ($mail['recipient'] != $_SESSION["no"]) {
                throw new Exception(EXCEPTION_REPLY_MESSAGE);
            }
        }
        require 'view/message.php';
    }
}

/**
 * Fonction permettant de supprimer un mail en fonction de son numéro reçu par un paramètre GET
 * @throws Exception
 */
function delete_msg(){

    if (verifyCSRFToken($_GET['csrf_token'])) {
        // Les détails du mail à supprimer sont récupérés dans un premier temps afin de vérifier que l'utilisateur connecté
        // ne puisse supprimer que les mails dont il est le destinataire
        $mail = getMailDetails($_GET['no'])->fetch();

        if ($mail['recipient'] == $_SESSION["no"]) {
            // appel à la fonction présente dans le model
            deleteMail($_GET['no']);
            $_SESSION['message'] = MESSAGE_DELETED;
            mailbox();
        }
        else { // sinon une exception est lancée
            throw new Exception(EXCEPTION_DELETE_MESSAGE);
        }
    }
    else {
        $_SESSION['message'] = ERROR_CSRF_TOKEN;
        mailbox();
    }
}
?>