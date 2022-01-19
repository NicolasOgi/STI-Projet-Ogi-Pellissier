<?php
    // Le rôle d'administrateur est défini par un 1 dans la DB alors que le rôle de collaborateur est défini par un 0
    const ROLE_USER  = '0';
    const ROLE_ADMIN = '1';

    // page
    const EXCEPTION_ACCESS_PAGE = "You do not have the rights to access this page";

    // message
    const EXCEPTION_ACCESS_MESSAGE = "You do not have the rights to access this message";
    const EXCEPTION_DELETE_MESSAGE = "You do not have the rights to delete this message";
    const EXCEPTION_REPLY_MESSAGE  = "You do not have the rights to reply to this message";
    const MESSAGE_SENT             = "The message has been sent";
    const MESSAGE_DELETED          = "The message has been deleted";

    // login
    const ERROR_USER_DISABLED = "This user is disabled";
    const ERROR_CAPTCHA       = "CAPTCHA verification failed, try again";

    // csrf token
    const ERROR_CSRF_TOKEN = "The anti-CSRF token couldn't be verified";

    // user
    const ERROR_USER_NOT_EXIST  = "The user does not exist";
    const EXCEPTION_MODIFY_USER = "You do not have the rights to modify this user";
    const ERROR_CREATE_USER     = "The user couldn't be created";
    const USER_MODIFIED         = "The user has been modified";
    const USER_CREATED          = "The user has been created";
    const USER_DELETED          = "The user has been deleted";
    const ERROR_DELETE_ONESELF  = "You cannot delete yourself";
    const USER_LOGGED_OUT       = "Successfully disconnected";
    const ROLE_UPDATED          = "Your role has been updated. Please log in again";

    // password
    const PASSWORD_UPDATED                 = "Your password has been updated. Please log in again";
    const ERROR_PASSWORD_POLICY            = "The password does not match the security policy, it should be at least 8 char long, should contain at least one uppercase char, one lowercase char, one digit and one special character";
    const ERROR_PASSWORD_OR_USER_INCORRECT = "The user does not exist or the password is incorrect";

    require 'controller/details.php';
    require 'controller/mailbox.php';
    require 'controller/message.php';
    require 'controller/users.php';
    require 'controller/login.php';

    require 'model/details.php';
    require 'model/mailbox.php';
    require 'model/message.php';
    require 'model/users.php';
    require 'model/login.php';
    require 'model/db.php';

    require 'utils/csrf.php';

/**
 * Fonction permettant de rediriger l'utilisateur sur la mailbox s'il est connecté ou sur la page de login si ce n'est
 * pas le cas
 */
function home() {
    if(check_if_connected()) {
            mailbox();
    }
    else {
        login();
    }
}
?>