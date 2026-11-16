<?php
class modelAdminCategory{

    public static function getCategoryList(){
        $sql = "SELECT category_id, name FROM category ORDER BY name ASC";
        $db = new Database();
        return $db->getAll($sql);
    }

    public static function addCategory(&$error = null) {
        if (!isset($_POST['saveCategory'], $_POST['name'])) {
            return false;
        }

        $name = trim($_POST['name']);
        if ($name === '' || !preg_match('/^.{1,255}$/us', $name)) {
            $error = 'Category name is required and must be 255 characters or fewer.';
            return false;
        }

        $db = new Database();
        $existing = $db->getOne(
            'SELECT category_id FROM category WHERE name = :name LIMIT 1',
            array(':name' => $name)
        );
        if ($existing) {
            $error = 'A category with this name already exists.';
            return false;
        }

        $inserted = $db->executeRun(
            'INSERT INTO category (name) VALUES (:name)',
            array(':name' => $name)
        );
        return $inserted > 0;
    }

    public static function editCategory(&$error = null) {
        if (!isset($_POST['saveCategoryEdit'], $_POST['category_id'], $_POST['name'])) {
            return false;
        }

        $categoryId = filter_var($_POST['category_id'], FILTER_VALIDATE_INT);
        $name = trim($_POST['name']);
        if ($categoryId === false || $categoryId < 1) {
            $error = 'Category not found.';
            return false;
        }
        if ($name === '' || !preg_match('/^.{1,255}$/us', $name)) {
            $error = 'Category name is required and must be 255 characters or fewer.';
            return false;
        }

        $db = new Database();
        $category = $db->getOne(
            'SELECT category_id, name FROM category WHERE category_id = :category_id',
            array(':category_id' => $categoryId)
        );
        if (!$category) {
            $error = 'Category not found.';
            return false;
        }
        $existing = $db->getOne(
            'SELECT category_id FROM category WHERE name = :name AND category_id <> :category_id LIMIT 1',
            array(':name' => $name, ':category_id' => $categoryId)
        );
        if ($existing) {
            $error = 'A category with this name already exists.';
            return false;
        }
        if ($category['name'] === $name) {
            return true;
        }

        return $db->executeRun(
            'UPDATE category SET name = :name WHERE category_id = :category_id',
            array(':name' => $name, ':category_id' => $categoryId)
        ) > 0;
    }

    public static function deleteCategory(&$error = null) {
        if (!isset($_POST['deleteCategory'], $_POST['category_id'])) {
            return false;
        }

        $categoryId = filter_var($_POST['category_id'], FILTER_VALIDATE_INT);
        if ($categoryId === false || $categoryId < 1) {
            $error = 'Category not found.';
            return false;
        }

        $db = new Database();
        $category = $db->getOne(
            'SELECT category_id FROM category WHERE category_id = :category_id',
            array(':category_id' => $categoryId)
        );
        if (!$category) {
            $error = 'Category not found.';
            return false;
        }

        $services = $db->getOne(
            'SELECT service_id FROM services WHERE category_id = :category_id LIMIT 1',
            array(':category_id' => $categoryId)
        );
        if ($services) {
            $error = 'Category cannot be deleted while it has services.';
            return false;
        }

        return $db->executeRun(
            'DELETE FROM category WHERE category_id = :category_id
             AND NOT EXISTS (SELECT 1 FROM services WHERE services.category_id = :category_id_check)',
            array(':category_id' => $categoryId, ':category_id_check' => $categoryId)
        ) > 0;
    }

}
?>