<?php
class Reviews{
    public static function insertReview($text, $serviceId)
    {
        $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
        $query = "INSERT INTO reviews (user_id, service_id, text, date)
                  VALUES (:user_id, :service_id, :text, CURRENT_TIMESTAMP)";
        $db = new Database();
        return $db->executeRun($query, array(
            ':user_id' => $userId,
            ':service_id' => (int)$serviceId,
            ':text' => $text
        ));
    }

    public static function getReviewsByServiceID($serviceId) {
        $query = "SELECT review_id, user_id, service_id, text, date FROM reviews
                  WHERE service_id = :service_id ORDER BY review_id DESC";
        $db = new Database();
        return $db->getAll($query, array(':service_id' => (int)$serviceId));
    }

    public static function getReviewsCountByServiceID($serviceId) {
        $query = "SELECT COUNT(review_id) AS count FROM reviews WHERE service_id = :service_id";
        $db = new Database();
        return $db->getOne($query, array(':service_id' => (int)$serviceId));
    }
}
?>