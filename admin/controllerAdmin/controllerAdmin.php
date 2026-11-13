<?php
class ControllerAdmin {

    public static function formLoginSite() {
        include_once('viewAdmin/formLogin.php');
    }

    public static function loginAction() {
        if(modelAdmin::userAuthentication()){
            include_once('viewAdmin/startAdmin.php');
        }
        else{
            $_SESSION['errorString'] = 'Invalid email, password, or administrator access.';
            include_once('viewAdmin/formLogin.php');
        }
    }

    public static function logoutAction() {
        modelAdmin::userLogout();
        include_once('viewAdmin/formLogin.php');
    }

    public static function error404() {
        include_once('viewAdmin/error404.php');
    }

    public static function requireAdmin() {
        if (!modelAdmin::isAdminAuthenticated()) {
            $_SESSION['errorString'] = 'Please sign in with an administrator account.';
            self::formLoginSite();
            return false;
        }
        return true;
    }

    public static function categoriesList() {
        $arr = modelAdminCategory::getCategoryList();
        include_once 'viewAdmin/categoriesList.php';
    }

}
?>