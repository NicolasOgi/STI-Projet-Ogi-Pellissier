<?php

/**
 * Fonction permettant de récupérer les mails d'un utilisateur dans la DB
 * @param $user utilisateur dont il faut récupérer les mail
 * @return false|PDOStatement résultat de la requête
 */
 function getUserMails($user) {
     $db = connect();
     // création de la string pour la requête
     $request = "SELECT Message.no, date,User.username as 'sender', subject, body
                FROM Message
                INNER JOIN User
                ON Message.noSender = User.no
                WHERE noRecipient = :user
                ORDER BY date DESC";

     $query = $db->prepare($request);
     $query->bindParam(':user', $user);
     $query->execute();

     return $query;
 }

/**
 * Fonction permettant de supprimer un mail dans la DB
 * @param $no numéro unique du mail à supprimer
 * @return false|PDOStatement résultat de la requête
 */
 function delMail($no){
     $db = connect();
     $request = "DELETE 
                 FROM Message 
                 WHERE no = :no";

     $query = $db->prepare($request);
     $query->bindParam(':no', $no);

     return $query->execute();
 }
?>