<?php
class modelAdminServices{

    public static function getServiceList() {
        $query = "SELECT services.*, category.name, users.username
                  FROM services
                  LEFT JOIN category ON services.category_id = category.category_id
                  LEFT JOIN users ON services.user_id = users.user_id
                  ORDER BY services.service_id DESC";
        $db = new Database();
        return $db->getAll($query);
    }

    public static function getServiceAdd(&$error = null) {
        if (!isset($_POST['save'], $_POST['title'], $_POST['text'], $_POST['category_id'], $_POST['price'])) {
            return false;
        }

        $userId = self::getAdminUserId();
        $image = self::getUploadedImage($error);
        if ($image === false) {
            return false;
        }

        $sql = "INSERT INTO services (category_id, user_id, title, text, image, price)
                VALUES (:category_id, :user_id, :title, :text, :image, :price)";
        $db = new Database();
        return $db->executeRun($sql, array(
            ':category_id' => (int)$_POST['category_id'],
            ':user_id' => $userId,
            ':title' => $_POST['title'],
            ':text' => $_POST['text'],
            ':image' => $image === null ? '' : $image,
            ':price' => (float)$_POST['price']
        )) > 0;
    }

    public static function getServiceDetail($id) {
        $query = "SELECT services.*, category.name, users.username
                  FROM services
                  LEFT JOIN category ON services.category_id = category.category_id
                  LEFT JOIN users ON services.user_id = users.user_id
                  WHERE services.service_id = :service_id";
        $db = new Database();
        return $db->getOne($query, array(':service_id' => (int)$id));
    }

    public static function getServiceEdit($id, &$error = null) {
        if (!isset($_POST['save'], $_POST['title'], $_POST['text'], $_POST['category_id'], $_POST['price'])) {
            return false;
        }

        $userId = self::getAdminUserId();
        $image = self::getUploadedImage($error);
        if ($image === false) {
            return false;
        }

        $params = array(
            ':title' => $_POST['title'],
            ':text' => $_POST['text'],
            ':category_id' => (int)$_POST['category_id'],
            ':price' => (float)$_POST['price'],
            ':user_id' => $userId,
            ':service_id' => (int)$id
        );
        if ($image === null) {
            $sql = "UPDATE services SET title = :title, text = :text, category_id = :category_id,
                    price = :price, user_id = :user_id WHERE service_id = :service_id";
        } else {
            $sql = "UPDATE services SET title = :title, text = :text, category_id = :category_id,
                    price = :price, image = :image, user_id = :user_id WHERE service_id = :service_id";
            $params[':image'] = $image;
        }
        $db = new Database();
        return $db->executeRun($sql, $params) > 0;
    }

    public static function getServiceDelete($id) {
        $test = false;
        if(isset($_POST['save'])) {
            $db = new Database();
            $serviceId = (int)$id;
            $db->executeRun("DELETE FROM reviews WHERE service_id = :service_id", array(':service_id' => $serviceId));
            $item = $db->executeRun("DELETE FROM services WHERE service_id = :service_id", array(':service_id' => $serviceId));
            if($item > 0) {
                $test = true;
            }
        }
        return $test;
    }

    private static function getAdminUserId() {
        if (!modelAdmin::isAdminAuthenticated()) {
            throw new RuntimeException('An authenticated administrator is required for service changes.');
        }
        return (int)$_SESSION['user_id'];
    }

    private static function getUploadedImage(&$error) {
        if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $error = 'The image upload failed. Please try again with a smaller image.';
            return false;
        }

        if ($_FILES['image']['size'] > 512 * 1024) {
            $error = 'The optimized image must be 512 KB or smaller. Select another image or reduce its size.';
            return false;
        }

        $allowedTypes = array('image/jpeg', 'image/png', 'image/gif', 'image/webp');
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['image']['tmp_name']);
        finfo_close($finfo);
        if (!in_array($mime, $allowedTypes, true)) {
            $error = 'Choose a valid JPEG, PNG, GIF, or WebP image.';
            return false;
        }

        $image = file_get_contents($_FILES['image']['tmp_name']);
        if ($image === false) {
            $error = 'The uploaded image could not be read.';
            return false;
        }
        return $image;
    }
}
?>