<?php
class Reviews {

    public static function getCsrfToken() {
        if (!isset($_SESSION['review_csrf_token'])) {
            $_SESSION['review_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['review_csrf_token'];
    }

    public static function insertReview($text, $serviceId, &$error = null) {
        if (!isset($_POST['csrf_token'], $_SESSION['review_csrf_token'])
            || !hash_equals($_SESSION['review_csrf_token'], (string)$_POST['csrf_token'])) {
            $error = 'Your session expired. Please try again.';
            return false;
        }

        $profile = UserAuth::getProfile();
        if (!$profile) {
            $error = 'Please log in to leave a review.';
            return false;
        }

        if (ReviewRateLimiter::consume()) {
            $error = 'Too many reviews submitted. Please try again in 15 minutes.';
            return false;
        }

        $serviceId = filter_var($serviceId, FILTER_VALIDATE_INT);
        if ($serviceId === false || $serviceId < 1) {
            $error = 'Invalid service.';
            return false;
        }

        if (!is_string($text)) {
            $error = 'Review text cannot be empty.';
            return false;
        }
        $text = trim($text);
        if ($text === '') {
            $error = 'Review text cannot be empty.';
            return false;
        }
        if (mb_strlen($text, 'UTF-8') > 2000) {
            $error = 'Review must be 2000 characters or fewer.';
            return false;
        }

        $db = new Database();
        return $db->executeRun(
            "INSERT INTO reviews (user_id, service_id, text, date)
             VALUES (:user_id, :service_id, :text, CURRENT_TIMESTAMP)",
            array(
                ':user_id'    => (int)$profile['user_id'],
                ':service_id' => $serviceId,
                ':text'       => $text,
            )
        ) > 0;
    }

    public static function getReviewsByServiceID($serviceId) {
        $query = "SELECT reviews.review_id, reviews.user_id, reviews.service_id,
                         reviews.text, reviews.date, users.username AS author_name
                  FROM reviews
                  LEFT JOIN users ON reviews.user_id = users.user_id
                  WHERE reviews.service_id = :service_id ORDER BY reviews.review_id DESC";
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