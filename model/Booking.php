<?php
class Booking {
    const SLOT_INTERVAL_MINUTES = 30;
    const MAX_PROCEDURE_DURATION_MINUTES = 660;

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
        $profile = UserAuth::getProfile();
        if (!$profile) {
            $error = 'Please log in to book a service.';
            return false;
        }

        if (!isset($_POST['createBooking'], $_POST['service_id'], $_POST['specialist_id'], $_POST['customer_name'],
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
        $specialistId = filter_var($_POST['specialist_id'], FILTER_VALIDATE_INT);
        if ($serviceId === false || $serviceId < 1 || $specialistId === false || $specialistId < 1) {
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

        $bookingTime = $bookingDate->format('H:i');
        if ($bookingTime < '08:00' || $bookingTime > '19:00') {
            $error = 'Choose a time between 08:00 and 19:00.';
            return false;
        }

        $db = new Database();
        $service = $db->getOne(
            'SELECT service_id, duration_minutes FROM services WHERE service_id = :service_id',
            array(':service_id' => $serviceId)
        );
        if (!$service) {
            $error = 'The selected service is not available.';
            return false;
        }
        $serviceDuration = (int)$service['duration_minutes'];
        if ($serviceDuration < 1 || $serviceDuration > self::MAX_PROCEDURE_DURATION_MINUTES) {
            $error = 'The selected service is not available.';
            return false;
        }
        $slotInterval = self::getSlotInterval($serviceDuration);
        $minutesFromOpening = ((int)$bookingDate->format('H') * 60) + (int)$bookingDate->format('i') - (8 * 60);
        if ($minutesFromOpening < 0
            || $minutesFromOpening % $slotInterval !== 0
            || ((int)$bookingDate->format('H') * 60) + (int)$bookingDate->format('i') + $slotInterval > 19 * 60) {
            $error = 'Choose one of the available time slots.';
            return false;
        }
        if (!Specialist::offersService($specialistId, $serviceId)) {
            $error = 'The selected specialist cannot provide this service.';
            return false;
        }

        $bookingDateSql = $bookingDate->format('Y-m-d H:i:s');
        $lockName = 'ilusalong_booking_' . substr(
            hash('sha256', $specialistId . '|' . $bookingDate->format('Y-m-d')),
            0,
            32
        );
        $lock = $db->getOne(
            'SELECT GET_LOCK(:lock_name, 5) AS acquired',
            array(':lock_name' => $lockName)
        );
        if (!$lock || (int)$lock['acquired'] !== 1) {
            $error = 'The booking slot is being reserved. Please try again.';
            return false;
        }

        try {
            $dayStart = $bookingDate->setTime(0, 0)->format('Y-m-d H:i:s');
            $dayEnd = $bookingDate->setTime(0, 0)->modify('+1 day')->format('Y-m-d H:i:s');
            $nearbyBookings = $db->getAll(
                "SELECT bookings.booking_date, bookings.duration_minutes
                 FROM bookings
                 WHERE bookings.specialist_id = :specialist_id
                   AND bookings.booking_date >= :day_start
                   AND bookings.booking_date < :day_end
                   AND bookings.status = 'active'",
                array(
                    ':specialist_id' => $specialistId,
                    ':day_start' => $dayStart,
                    ':day_end' => $dayEnd
                )
            );
            $requestedStart = $bookingDate->getTimestamp();
            $requestedEnd = $requestedStart + ($slotInterval * 60);
            foreach ($nearbyBookings as $nearbyBooking) {
                $busyStart = strtotime($nearbyBooking['booking_date']);
                $busyInterval = self::getSlotInterval((int)$nearbyBooking['duration_minutes']);
                $busyEnd = $busyStart + ($busyInterval * 60);
                if ($requestedStart < $busyEnd && $busyStart < $requestedEnd) {
                    $error = 'This specialist already has a booking during that time. Please choose another time.';
                    return false;
                }
            }

            $query = "INSERT INTO bookings
                        (user_id, session_token_hash, service_id, duration_minutes, specialist_id, customer_name, customer_email, customer_phone, booking_date, status)
                      VALUES
                        (:user_id, :session_token_hash, :service_id, :duration_minutes, :specialist_id, :customer_name, :customer_email, :customer_phone, :booking_date, 'active')";
            return $db->executeRun($query, array(
                ':user_id' => (int)$profile['user_id'],
                ':session_token_hash' => self::getSessionTokenHash(),
                ':service_id' => $serviceId,
                ':duration_minutes' => $serviceDuration,
                ':specialist_id' => $specialistId,
                ':customer_name' => $name,
                ':customer_email' => $email,
                ':customer_phone' => $phone,
                ':booking_date' => $bookingDateSql
            )) > 0;
        } finally {
            $db->getOne(
                'SELECT RELEASE_LOCK(:lock_name)',
                array(':lock_name' => $lockName)
            );
        }
    }

    public static function getBookingsByUser() {
        $profile = UserAuth::getProfile();
        if (!$profile) {
            return array();
        }

        $query = "SELECT bookings.booking_id, bookings.service_id, bookings.specialist_id,
                         specialists.name AS specialist_name, bookings.customer_name,
                         bookings.customer_email, bookings.customer_phone, bookings.booking_date,
                         bookings.status, services.title, services.text, services.price
                  FROM bookings
                  LEFT JOIN services ON bookings.service_id = services.service_id
                  LEFT JOIN specialists ON bookings.specialist_id = specialists.specialist_id
                  WHERE bookings.user_id = :user_id
                     OR (bookings.user_id IS NULL AND bookings.session_token_hash = :session_token_hash)
                  ORDER BY bookings.booking_date DESC, bookings.booking_id DESC";
        $db = new Database();
        return $db->getAll($query, array(
            ':user_id' => (int)$profile['user_id'],
            ':session_token_hash' => self::getSessionTokenHash()
        ));
    }

    public static function getUpcomingBookedSlots($specialists) {
        if (!$specialists) {
            return array();
        }

        $placeholders = array();
        $params = array();
        foreach ($specialists as $index => $specialist) {
            $placeholder = ':specialist_' . $index;
            $placeholders[] = $placeholder;
            $params[$placeholder] = (int)$specialist['specialist_id'];
        }

        $db = new Database();
        return $db->getAll(
            "SELECT bookings.specialist_id, bookings.booking_date, bookings.duration_minutes
             FROM bookings
             WHERE bookings.specialist_id IN (" . implode(', ', $placeholders) . ")
               AND bookings.status = 'active'
               AND bookings.booking_date > NOW()
             ORDER BY bookings.specialist_id ASC, bookings.booking_date ASC",
            $params
        );
    }

    public static function getSlotInterval($durationMinutes) {
        $durationMinutes = max(1, (int)$durationMinutes);
        return (int)(ceil($durationMinutes / self::SLOT_INTERVAL_MINUTES) * self::SLOT_INTERVAL_MINUTES);
    }

    public static function cancelBooking($bookingId, &$error = null) {
        $profile = UserAuth::getProfile();
        if (!$profile) {
            $error = 'Please log in to view your bookings.';
            return false;
        }

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
               AND (
                    user_id = :user_id
                    OR (user_id IS NULL AND session_token_hash = :legacy_session_token_hash)
               )
               AND status = 'active'
               AND booking_date > NOW()",
            array(
                ':booking_id' => $bookingId,
                ':user_id' => (int)$profile['user_id'],
                ':legacy_session_token_hash' => self::getSessionTokenHash()
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
