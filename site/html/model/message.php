<?php
/**
 * Fonction permettant d'écrire un nouveau mail dans la DB
 * @param int $noSender    Numéro unique de l'expéditeur
 * @param int $noRecipient Numéro unique du destinataire
 * @param string $subject  Sujet du mail
 * @param string $body     Corps du mail
 * @param string $date     Date du mail
 * @return false|true True si le mail a bien été écrit dans la DB, false sinon
 */
function sendMail($noSender, $noRecipient, $subject, $body, $date) {

    $db = connect();
    // Création de la string pour la requête
    $request = "INSERT INTO Message (noSender, noRecipient, subject, body, date) VALUES (:noSender, :noRecipient, :subject, :body, :date)";
    $query = $db->prepare($request);
    $query->bindValue(':noSender', $noSender);
    $query->bindValue(':noRecipient', $noRecipient);
    $query->bindValue(':subject', $subject);
    $query->bindValue(':body', $body);
    $query->bindValue(':date', $date);

    // exécution de la requête
    return $query->execute();
}
?>