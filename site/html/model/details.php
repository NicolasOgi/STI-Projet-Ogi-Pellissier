<?php
/**
 * Fonction permettant de récupérer les détails d'un mail dans la DB
 * @param int $no Numéro unique d'un mail
 * @return false|PDOStatement le résultat de la requête
 */
function getMailDetails($no) {
    $db = connect();

    // Création de la string pour la requête
    $request = "SELECT Message.no, User.username as 'sender', noRecipient as 'recipient', subject, body, date 
                FROM Message
                    INNER JOIN User
                        ON Message.noSender = User.no
                WHERE Message.no = :no";

    $query = $db->prepare($request);
    $query->bindParam(':no', $no);
    $query->execute();

    return $query;
}
?>