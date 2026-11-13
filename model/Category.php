<?php
class Category{


    public static function getAllCategory() {
        $query = "SELECT category_id, name FROM category ORDER BY name";
        $db = new Database();
        $arr = $db->getAll($query);
        return $arr;
    }
}