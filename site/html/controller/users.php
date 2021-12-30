<?php
/**
 * Fonction retournant la variable de session indiquant si l'utilisateur est connecté
 * @return false|true True si l'utilisateur est connecté, false sinon
 */
function check_if_connected() {
    return $_SESSION['isConnected'];
}

/**
 * Fonction permettant de récupérer la liste des utilisateurs afin de les afficher dans la vue users.php accessible par
 * les administrateurs
 * @throws Exception Dans le cas où un collaborateur essaie d'accéder à cette vue
 */
function administration() {
    // si l'utilisateur n'est pas un administrateur
    if($_SESSION['role'] != ROLE_ADMIN){
        throw new Exception(ERROR_ACCESS_PAGE);
    }

    // récupération des utilisateurs dans la DB afin qu'ils soient affichés dans la vue grâce à la variable $users
    $users = getAllUsers()->fetchAll();
    require 'view/users.php';
}

/**
 * Fonction permettant de modifier les informations d'utilisateur existant
 * @throws Exception Dans le cas où un collaborateur essaie d'accéder à cette vue
 */
function change_user_details() {

    // récupération du numéro unique de l'utilisateur
    $userNo = $_GET['no'];

    // si l'utilisateur n'est pas admin et qu'il essaie de modifier un autre utilisateur que lui-même
    if($_SESSION['role'] != ROLE_ADMIN && $userNo != $_SESSION['no']){
        throw new Exception(ERROR_MODIFY_USER);
    }

    if (empty($_POST)){
        // récupération des informations de l'utilisateur
        $user = getUserByID($userNo)->fetch();

        // si ce n'est pas null alors l'utilisateur existe déjà et c'est donc une modification
        if($user != null) {
            $isCreation = false;
            require 'view/usermodify.php';
        }
        else { // sinon l'utilisateur n'existe pas
            $_SESSION['message'] = ERROR_USER_NOT_EXIST;
            administration();
        }
    }
    else if(isset($_POST['password']) && !empty($_POST['password'])) { // si l'on veut définir un nouveau mot de passe

        // on vérifie le token anti-CSRF au changement du mot de passe
        if (verifyCSRFToken($_POST['csrf_token'])) {

            if (password_matches_security_policy($_POST['password'])) {

                // appel de la fonction permettant de faire la modification dans la DB
                updatePassword($userNo, $_POST['password']);

                // redirection de l'utilisateur sur la page de login une fois la modification effectuée
                // et empêche qu'un administrateur soit déconnecté s'il modifie le mot de passe d'un compte qui n'est
                // pas le sien
                if($userNo == $_SESSION['no']){
                    $_SESSION['message'] = PASSWORD_UPDATED;
                    logout();
                    exit();
                }
            }
            else {
                $_SESSION['message'] = ERROR_PASSWORD_POLICY;
                mailbox();
                exit();
            }
        }
        else {
            $_SESSION['message'] = ERROR_CSRF_TOKEN;
            mailbox();
            exit();
        }
    }

    // si l'utilisateur est admin et qu'il veut modifier les infos d'un utilisateur
    if($_SESSION['role'] == ROLE_ADMIN && isset($_POST['role'])) {

        if (verifyCSRFToken($_POST['csrf_token'])) {

            // appel de la fonction permettant de mettre à jour les données dans la DB
            updateUserNonEmptyFields($userNo);
            $_SESSION['message'] = USER_MODIFIED;
            administration();
        }
        else {
            $_SESSION['message'] = ERROR_CSRF_TOKEN;
            administration();
        }
    }
}

/**
 * Fonction permettant de créer un nouvel utilisateur
 * @throws Exception Dans le cas où un collaborateur essaie d'accéder à cette vue
 */
function add_user(){

    // si l'utilisateur n'est pas un administrateur
    if($_SESSION['role'] != ROLE_ADMIN){
        throw new Exception(ERROR_ACCESS_PAGE);
    }

    // si ce n'est pas null alors l'utilisateur existe déjà et c'est donc une modification
    if (empty($_POST)) {
        $isCreation = true;
        require 'view/usermodify.php';
    }
    else {

        // vérification du token anti-CSRF à la création d'un nouvel utilisateur
        if (verifyCSRFToken($_POST['csrf_token'])) {

            try {
                if (password_matches_security_policy($_POST["password"])) {
                    $validity = isset($_POST['valid']) ? 1 : 0;
                    // appel de la fonction permettant de créer le nouvel utilisateur dans la DB
                    insertUser($_POST['username'], $_POST["password"], $validity, $_POST["role"]);
                    $_SESSION['message'] = USER_CREATED;
                }
                else {
                    $_SESSION['message'] = ERROR_PASSWORD_POLICY;
                }

            } catch(Exception $e){
                $_SESSION['message'] = ERROR_CREATE_USER;
            }
        }
        else {
            $_SESSION['message'] = ERROR_CSRF_TOKEN;
        }
        administration();
    }
}

/**
 * Fonction permettant de supprimer un utilisateur
 * @throws Exception
 */
function delete_user(){

    // si l'utilisateur n'est pas un administrateur
    if($_SESSION['role'] != ROLE_ADMIN){
        throw new Exception(ERROR_ACCESS_PAGE);
    }

    // vérification du token anti-CSRF à la suppression d'un utilisateur
    if (verifyCSRFToken($_GET['csrf_token'])) {
        $userNo = $_GET['no'];

        // vérifie que l'utilisateur existe
        $user = getUserByID($userNo)->fetch();
        if($user != null) {

            // si l'utilisateur à supprimer est différent de celui connecté actuellement
            if($userNo != $_SESSION['no']){
                // appel de la fonction supprimant l'utilisateur de la DB
                dropUser($userNo);
            }
            else {
                $_SESSION['message'] = ERROR_DELETE_ONESELF;
            }
        }
        else {
            $_SESSION['message'] = ERROR_USER_NOT_EXIST;
        }
    }
    else {
        $_SESSION['message'] = ERROR_CSRF_TOKEN;
    }
    administration();
}

/**
 * Fonction permettant de vérifier si un mot de passe correspond à la politique de sécurité
 * @param string $password Mot de passe à vérifier
 * @return false|int 1 si le mot de passe matche, false sinon
 */
function password_matches_security_policy($password) {
    // min. 8 caractères, min. 1 chiffre,  min. 1 majuscule, min. 1 minuscule, min. 1 caractère spécial selon la liste
    return preg_match("/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])[0-9A-Za-z!?()<>+&=~^¦|¬;,.:_@#€£$%]{8,}$/", $password);
}
?>