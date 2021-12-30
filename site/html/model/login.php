<?php
/**
 * Fonction permettant de récupérer les champs d'un utilisateur dans le DB
 * @param string $username Nom d'utilisateur à rechercher dans la DB
 * @return false|PDOStatement
 */
function getUserByUsername($username)
{
    $db = connect();
    // création de la string pour la requête
    $request = "SELECT * 
                FROM User 
                WHERE username = :username";

    $query = $db->prepare($request);
    $query->bindParam(':username', $username);
    $query->execute();

    return $query;
}
?>