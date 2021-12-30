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
?>