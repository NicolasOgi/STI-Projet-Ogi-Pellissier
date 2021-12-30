<?php
/**
 * Fonction permettant de hasher un mot de passe
 * @param $password mot de passe à hasher
 * @return false|string|null le hash du mot de passe
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Fonction permettant de mettre à jour le mot de passe
 * @param $id numéro unique de l'utilisateur
 * @param $newPassword nouveau mot de passe
 * @return false|PDOStatement résultat de la requête
 */
function updatePassword($id, $newPassword) {
    $db = connect();
    $newPassword = hashPassword($newPassword);
    // création de la string pour la requête
    $request = "UPDATE User SET password = :new_password WHERE no = :id";

    $query = $db->prepare($request);
    $query->bindParam(':new_password', $newPassword);
    $query->bindParam(':id', $id);

    return $query->execute();
}

/**
 * Fonction permettant de mettre à jour les champs d'un utilisateur dans la DB
 * @param $id numéro unique de l'utilisateur
 * @return false|PDOStatement résultat de la requête
 */
function updateUserNonEmptyFields($id){

    $db = connect();
    $request = "UPDATE User SET role = :role, valid = :valid WHERE no = :id";

    if(isset($_POST['role'])){
        $role = $_POST['role'] % 2; // mod 2 car role = 0 ou 1
    }

    // valid n'est pas envoyé si la checkbox n'est pas cochée depuis la vue
    $valid = isset($_POST['valid']) ? 1 : 0;

    $query = $db->prepare($request);
    $query->bindParam(':role', $role);
    $query->bindParam(':valid', $valid);
    $query->bindParam(':id', $id);

    return $query->execute();
}

/**
 * Fonction permettant de récupérer les infos d'un utilisateur
 * @param $no numéro unique de l'utilisateur dont il faut récupérer les infos
 * @return false|PDOStatement
 */
function getUserByID($no) {
    $db = connect();
    $request = "SELECT no, username, valid, role 
                FROM User 
                WHERE no = :no";

    $query = $db->prepare($request);
    $query->bindParam(':no', $no);
    $query->execute();

    return $query;
}

/**
 * Fonction permettant de récupérer tous les utilisateurs existants dans la DB
 * @return false|PDOStatement résultat de la requête
 */
function getAllUsers() {
    $db = connect();
    $request = "SELECT no, username, valid, role FROM User";

    $query = $db->prepare($request);
    $query->execute();

    return $query;
}

/**
 * Fonction permettant de supprimer un utilisateur de la DB
 * @param $id numéro unique appartenant à l'utilisateur qu'il faut supprimer
 * @return false|PDOStatement résultat de la requête
 */
function dropUser($id){
    $db = connect();
    $request = "DELETE FROM User WHERE no = :id";

    $query = $db->prepare($request);
    $query->bindParam(':id', $id);

    return $query->execute();
}

/**
 * Fonction permettant de créer un nouvel utilisateur dans la DB
 * @param $username nom de l'utilisateur
 * @param $password mot de passe
 * @param $valid    compte activé('1') ou non('0')
 * @param $role     administrateur('1') ou collaborateur('0')
 * @return false|PDOStatement
 */
function insertUser($username, $password, $valid, $role){
    $db = connect();
    $password = password_hash($password, PASSWORD_DEFAULT);
    $request = "INSERT INTO User (username, password, valid, role) VALUES (:username, :password, :valid, :role)";

    $query = $db->prepare($request);
    $query->bindValue(':username', $username);
    $query->bindValue(':password', $password);
    $query->bindValue(':valid', $valid);
    $query->bindValue(':role', $role);

    // exécution de la requête
    return $query->execute();
}
?>