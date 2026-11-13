<?php
class controllerAdminServices{

    public static function ServicesList() {
        $arr = modelAdminServices::getServiceList();
        include_once 'viewAdmin/servicesList.php';
    }

    //----------------------add
    public static function serviceAddForm()
    {
        $arr = modelAdminCategory::getCategoryList();
        include_once('viewAdmin/serviceAddForm.php');
    }

    public static function serviceAddResult()
    {
        $uploadError = null;
        $test = modelAdminServices::getServiceAdd($uploadError);
        if ($uploadError !== null) {
            unset($test);
        }
        include_once('viewAdmin/serviceAddForm.php');
    }

    //----------------------edit
    public static function serviceEditForm($id)
    {
        $arr = modelAdminCategory::getCategoryList();
        $detail = modelAdminServices::getServiceDetail($id);
        include_once('viewAdmin/serviceEditForm.php');
    }

    public static function serviceEditResult($id)
    {
        $uploadError = null;
        $test = modelAdminServices::getServiceEdit($id, $uploadError);
        if ($uploadError !== null) {
            unset($test);
            $arr = modelAdminCategory::getCategoryList();
            $detail = modelAdminServices::getServiceDetail($id);
        }
        include_once('viewAdmin/serviceEditForm.php');
    }

//----------------------delete
    public static function serviceDeleteForm($id)
    {
        $arr = modelAdminCategory::getCategoryList();
        $detail = modelAdminServices::getServiceDetail($id);
        include_once('viewAdmin/serviceDeleteForm.php');
    }

    public static function serviceDeleteResult($id)
    {
        $test = modelAdminServices::getServiceDelete($id);
        include_once('viewAdmin/serviceDeleteForm.php');
    }

} //class
?>