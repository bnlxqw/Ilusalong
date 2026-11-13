<?php
class Services{

    public static function getLast10Services() {
        $query = "SELECT * FROM services ORDER BY service_id DESC LIMIT 3" ;
        $db = new Database();
        $arr = $db->getAll($query);
        return $arr;
    }

    public static function getAllServices() {
        $query = "SELECT * FROM services ORDER BY service_id DESC" ;
        $db = new Database();
        $arr = $db->getAll($query);
        return $arr;
    }

    public static function getServicesByCategoryID($id) {
        $query = "SELECT * FROM services WHERE category_id = :category_id ORDER BY service_id DESC";
        $db = new Database();
        $arr = $db->getAll($query, array(':category_id' => (int)$id));
        return $arr;
    }

    public static function getServiceByID($id) {
        $query = "SELECT * FROM services WHERE service_id = :service_id";
        $db = new Database();
        return $db->getOne($query, array(':service_id' => (int)$id));
    }
}
?>