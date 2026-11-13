<?php
class modelAdminCategory{

    //------------------------------List
    public static function getCategoryList(){
        $sql = "SELECT category_id, name FROM category ORDER BY name ASC";
        $db = new Database();
        //$rows contains the category records
        $rows = $db->getAll($sql);
        //----------------------------------
        return $rows;
    }

}
//class
?>