<?php
class Booking {
    public static function getSessionTokenHash() {
        if (!isset($_SESSION['booking_owner_token'])) {
            $_SESSION['booking_owner_token'] = bin2hex(random_bytes(32));
        }
        return hash('sha256', $_SESSION['booking_owner_token']);
    }

    public static function getCsrfToken() {
        if (!isset($_SESSION['booking_csrf_token'])) {
            $_SESSION['booking_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['booking_csrf_token'];
    }

    public static function createBooking(&$error = null) {
        if (!isset($_POST['createBooking'], $_POST['service_id'], $_POST['customer_name'],
            $_POST['customer_email'], $_POST['customer_phone'], $_POST['booking_date'])) {
            $error = 'Please complete all booking fields.';
            return false;
        }

        if (!isset($_POST['csrf_token'], $_SESSION['booking_csrf_token'])
            || !hash_equals($_SESSION['booking_csrf_token'], (string)$_POST['csrf_token'])) {
            $error = 'Your session expired. Please try again.';
            return false;
        }

        $serviceId = filter_var($_POST['service_id'], FILTER_VALIDATE_INT);
        if ($serviceId === false || $serviceId < 1) {
            $error = 'The selected service is not available.';
            return false;
        }

        $name = trim($_POST['customer_name']);
        if ($name === '' || !preg_match('/^.{1,100}$/us', $name)) {
            $error = 'Enter your name (up to 100 characters).';
            return false;
        }

        $email = filter_var(trim($_POST['customer_email']), FILTER_VALIDATE_EMAIL);
        if ($email === false || strlen($email) > 254) {
            $error = 'Enter a valid email address.';
            return false;
        }

        $phone = trim($_POST['customer_phone']);
        if (!preg_match('/^[0-9+().\-\s]{5,32}$/', $phone)) {
            $error = 'Enter a valid phone number.';
            return false;
        }

        $dateInput = (string)$_POST['booking_date'];
        $bookingDate = DateTimeImmutable::createFromFormat('!Y-m-d\TH:i', $dateInput);
        $dateErrors = DateTimeImmutable::getLastErrors();
        if ($bookingDate === false
            || ($dateErrors !== false && ($dateErrors['warning_count'] > 0 || $dateErrors['error_count'] > 0))
            || $bookingDate->format('Y-m-d\TH:i') !== $dateInput
            || $bookingDate <= new DateTimeImmutable('now')) {
            $error = 'Choose a future date and time.';
            return false;
        }

        $db = new Database();
        $service = $db->getOne(
            'SELECT service_id FROM services WHERE service_id = :service_id',
            array(':service_id' => $serviceId)
        );
        if (!$service) {
            $error = 'The selected service is not available.';
            return false;
        }

        $query = "INSERT INTO bookings
                    (user_id, session_token_hash, service_id, customer_name, customer_email, customer_phone, booking_date, status)
                  VALUES
                    (NULL, :session_token_hash, :service_id, :customer_name, :customer_email, :customer_phone, :booking_date, 'active')";
        return $db->executeRun($query, array(
            ':session_token_hash' => self::getSessionTokenHash(),
            ':service_id' => $serviceId,
            ':customer_name' => $name,
            ':customer_email' => $email,
            ':customer_phone' => $phone,
            ':booking_date' => $bookingDate->format('Y-m-d H:i:s')
        )) > 0;
    }

    public static function getBookingsByUser() {
        $query = "SELECT bookings.booking_id, bookings.service_id, bookings.customer_name,
                         bookings.customer_email, bookings.customer_phone, bookings.booking_date,
                         bookings.status, services.title, services.text, services.price
                  FROM bookings
                  LEFT JOIN services ON bookings.service_id = services.service_id
                  WHERE bookings.session_token_hash = :session_token_hash
                  ORDER BY bookings.booking_date DESC, bookings.booking_id DESC";
        $db = new Database();
        return $db->getAll($query, array(
            ':session_token_hash' => self::getSessionTokenHash()
        ));
    }

    public static function cancelBooking($bookingId, &$error = null) {
        if (!isset($_POST['csrf_token'], $_SESSION['booking_csrf_token'])
            || !hash_equals($_SESSION['booking_csrf_token'], (string)$_POST['csrf_token'])) {
            $error = 'Your session expired. Please try again.';
            return false;
        }

        $bookingId = filter_var($bookingId, FILTER_VALIDATE_INT);
        if ($bookingId === false || $bookingId < 1) {
            $error = 'Booking not found or it can no longer be cancelled.';
            return false;
        }

        $db = new Database();
        $affected = $db->executeRun(
            "UPDATE bookings
             SET status = 'cancelled'
             WHERE booking_id = :booking_id
               AND session_token_hash = :session_token_hash
               AND status = 'active'
               AND booking_date > NOW()",
            array(
                ':booking_id' => $bookingId,
                ':session_token_hash' => self::getSessionTokenHash()
            )
        );
        if ($affected < 1) {
            $error = 'Booking not found or it can no longer be cancelled.';
            return false;
        }
        return true;
    }
}
?>
