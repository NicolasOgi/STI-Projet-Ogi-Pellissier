<?php

/**
 * Fonction permettant de vérifier les credentials
 * @param $postArray crendentials envoyés dans le POST
 * @return array infos de l'utilisateur
 */
function checkLogin($postArray)
{
    $username = $postArray["fLogin"];
    $passwdPost = $postArray["fPasswd"];
    $results = getUserByLogin($username);
    $results = $results->fetch();

    // si le résultat de la fonction est vide, alors l'utilisateur n'existe pas
    if (empty($results['username'])) {
        // Faux hash pour que la vérification du mot de passe soit quand même effectuée afin d'éviter les timing attacks
        $hash = '$2y$10$ketJpZi7uOnjXX/jaRolFeqru6YEmpbSv2krw2nK0/Mm/PAcpAZp6';
    }
    else {
        $hash = $results['password'];
    }

    // vérification du mot de passe
    if (password_verify($passwdPost, $hash)) {
        // initialisation du tableau qui va contenir les informations de l'utilisateur
        $infoUser = array(
            'no' => $results['no'],
            'username' => $results['username'],
            'valid' => $results['valid'],
            'role' => $results['role'],
        );
    } else { // si le mot de passe est incorrect
        $_SESSION['message'] = ERROR_PASSWORD_OR_USER_INCORRECT;
        require 'view/login.php';
    }
    return @$infoUser; // renvoie le tableau contenant les infos de l'utilisateur
}

/**
 * Fonction permettant de récupérer les champs d'un utilisateur dans le DB
 * @param $login nom d'utilisateur à rechercher dans la DB
 * @return false|PDOStatement
 */
function getUserByLogin($login)
{
    $db = connect();
    // création de la string pour la requête
    $request = "SELECT * 
                FROM User 
                WHERE username ='" . $login . "'";
    // exécution de la requête
    return $db->query($request);
}
?>