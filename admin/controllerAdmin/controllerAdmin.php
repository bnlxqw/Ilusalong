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

    public static function requestRejected() {
        http_response_code(403);
        $content = '<section class="admin-panel"><div class="alert alert-warning">' .
            htmlspecialchars(language_text('Invalid or expired request token. Please reload and try again.'), ENT_QUOTES, 'UTF-8') .
            '</div></section>';
        include 'viewAdmin/templates/layout.php';
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

    public static function categoryAddResult() {
        $error = null;
        $added = modelAdminCategory::addCategory($error);
        $arr = modelAdminCategory::getCategoryList();
        include_once 'viewAdmin/categoriesList.php';
    }

    public static function categoryEditResult() {
        $error = null;
        $updated = modelAdminCategory::editCategory($error);
        $arr = modelAdminCategory::getCategoryList();
        include_once 'viewAdmin/categoriesList.php';
    }

    public static function categoryDeleteResult() {
        $error = null;
        $deleted = modelAdminCategory::deleteCategory($error);
        $arr = modelAdminCategory::getCategoryList();
        include_once 'viewAdmin/categoriesList.php';
    }

    public static function specialistsList() {
        $arr = modelAdminSpecialists::getSpecialistList();
        $services = modelAdminSpecialists::getServiceOptions();
        $serviceAssignments = modelAdminSpecialists::getSpecialistServiceAssignments();
        include_once 'viewAdmin/specialistsList.php';
    }

    public static function specialistAddResult() {
        $error = null;
        $saved = modelAdminSpecialists::addSpecialist($error);
        $arr = modelAdminSpecialists::getSpecialistList();
        $services = modelAdminSpecialists::getServiceOptions();
        $serviceAssignments = modelAdminSpecialists::getSpecialistServiceAssignments();
        include_once 'viewAdmin/specialistsList.php';
    }

    public static function specialistEditResult() {
        $error = null;
        $saved = modelAdminSpecialists::editSpecialist($error);
        $arr = modelAdminSpecialists::getSpecialistList();
        $services = modelAdminSpecialists::getServiceOptions();
        $serviceAssignments = modelAdminSpecialists::getSpecialistServiceAssignments();
        include_once 'viewAdmin/specialistsList.php';
    }

    public static function specialistDeleteResult() {
        $error = null;
        $deleted = modelAdminSpecialists::deleteSpecialist($error);
        $arr = modelAdminSpecialists::getSpecialistList();
        $services = modelAdminSpecialists::getServiceOptions();
        $serviceAssignments = modelAdminSpecialists::getSpecialistServiceAssignments();
        include_once 'viewAdmin/specialistsList.php';
    }

}
?>