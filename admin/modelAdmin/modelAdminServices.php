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
        if (!isset($_POST['save'], $_POST['title'], $_POST['text'], $_POST['category_id'], $_POST['price'], $_POST['duration_minutes'])) {
            return false;
        }

        $duration = self::getDuration($error);
        if ($duration === false) {
            return false;
        }

        $userId = self::getAdminUserId();
        $image = ServiceImage::readUploadedImage('image', $error);
        if ($image === false) {
            return false;
        }

        $sql = "INSERT INTO services (category_id, user_id, title, text, image, price, duration_minutes)
                VALUES (:category_id, :user_id, :title, :text, :image, :price, :duration_minutes)";
        $db = new Database();
        return $db->executeRun($sql, array(
            ':category_id' => (int)$_POST['category_id'],
            ':user_id' => $userId,
            ':title' => $_POST['title'],
            ':text' => $_POST['text'],
            ':image' => $image === null ? '' : $image,
            ':price' => (float)$_POST['price'],
            ':duration_minutes' => $duration
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
        if (!isset($_POST['save'], $_POST['title'], $_POST['text'], $_POST['category_id'], $_POST['price'], $_POST['duration_minutes'])) {
            return false;
        }

        $duration = self::getDuration($error);
        if ($duration === false) {
            return false;
        }

        $userId = self::getAdminUserId();
        $image = ServiceImage::readUploadedImage('image', $error);
        if ($image === false) {
            return false;
        }

        $params = array(
            ':title' => $_POST['title'],
            ':text' => $_POST['text'],
            ':category_id' => (int)$_POST['category_id'],
            ':price' => (float)$_POST['price'],
            ':duration_minutes' => $duration,
            ':user_id' => $userId,
            ':service_id' => (int)$id
        );
        if ($image === null) {
            $sql = "UPDATE services SET title = :title, text = :text, category_id = :category_id,
                    price = :price, duration_minutes = :duration_minutes, user_id = :user_id
                    WHERE service_id = :service_id";
        } else {
            $sql = "UPDATE services SET title = :title, text = :text, category_id = :category_id,
                    price = :price, duration_minutes = :duration_minutes, image = :image, user_id = :user_id
                    WHERE service_id = :service_id";
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

    private static function getDuration(&$error) {
        $duration = isset($_POST['duration_minutes']) && is_string($_POST['duration_minutes'])
            ? filter_var($_POST['duration_minutes'], FILTER_VALIDATE_INT)
            : false;
        if ($duration === false || $duration < 1 || $duration > 660) {
            $error = 'Service duration must be between 1 and 660 minutes.';
            return false;
        }
        return $duration;
    }

}
?>