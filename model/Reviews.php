<?php
class Reviews {

    public static function getCsrfToken() {
        if (!isset($_SESSION['review_csrf_token'])) {
            $_SESSION['review_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['review_csrf_token'];
    }

    public static function insertReview($text, $serviceId, &$error = null) {
        // CSRF check
        if (!isset($_POST['csrf_token'], $_SESSION['review_csrf_token'])
            || !hash_equals($_SESSION['review_csrf_token'], (string)$_POST['csrf_token'])) {
            $error = 'Your session expired. Please try again.';
            return false;
        }

        // Validate service ID
        $serviceId = filter_var($serviceId, FILTER_VALIDATE_INT);
        if ($serviceId === false || $serviceId < 1) {
            $error = 'Invalid service.';
            return false;
        }

        // Validate review text
        $text = trim($text);
        if ($text === '') {
            $error = 'Review text cannot be empty.';
            return false;
        }
        if (mb_strlen($text, 'UTF-8') > 2000) {
            $error = 'Review must be 2000 characters or fewer.';
            return false;
        }

        $userId = isset($_SESSION['customer_user_id']) ? (int)$_SESSION['customer_user_id'] : null;

        $db = new Database();
        return $db->executeRun(
            "INSERT INTO reviews (user_id, service_id, text, date)
             VALUES (:user_id, :service_id, :text, CURRENT_TIMESTAMP)",
            array(
                ':user_id'    => $userId,
                ':service_id' => $serviceId,
                ':text'       => $text,
            )
        ) > 0;
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