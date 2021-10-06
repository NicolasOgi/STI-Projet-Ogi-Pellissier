
<?php 

function login(){
    // Récupération des variables POST
    if (isset($_POST['fLogin']) && isset($_POST['fPasswd'])) {
        try {
            $infoUser = checkLogin($_POST);//vérification du login
            if ($infoUser) {
                //Initialise les variables de session nécessaires pour  être identifié sur le site avec son compte
                $_SESSION["isConnected"] = true;
                $_SESSION["no"] = $infoUser['no'];
                $_SESSION['username'] = $infoUser['username'];
                $_SESSION['valid'] = $infoUser['valid'];
                $_SESSION['role'] = $infoUser['role'];
                $_SESSION['error'] = "";
                @header("location: index.php?action=home");
                exit();
            }
        } catch (Exception $e) {
            $_SESSION['erreur'] = $e->getMessage();
            @header("location: index.php?action=vue_login");
            exit();
        }
    } else {
        if (isset($_SESSION['login'])) {
            require "vue/vue_accueil.php"; //affiche la vue accueil si l'utilisateur est connecté
        } else //Si l'utilisateur n'est pas connecté
        {
            require ROOT_PROFIL . "vue_login.php";
        }
    }

    require 'view/login.php';
}

?>