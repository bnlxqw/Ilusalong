<?php
class ViewBookings {
    public static function BookingForm($serviceId) {
        echo '<section class="booking-section" id="booking"><h2>' . language_text('Book this service') . '</h2>';
        self::FlashMessage();
        echo '<form class="booking-form" action="createBooking" method="post">
            <input type="hidden" name="service_id" value="' . (int)$serviceId . '">
            <input type="hidden" name="csrf_token" value="' .
                htmlspecialchars(Booking::getCsrfToken(), ENT_QUOTES, 'UTF-8') . '">
            <label for="booking-name">' . language_text('Name') . '</label>
            <input id="booking-name" name="customer_name" type="text" maxlength="100" autocomplete="name" required>
            <label for="booking-email">' . language_text('Email address') . '</label>
            <input id="booking-email" name="customer_email" type="email" maxlength="254" autocomplete="email" required>
            <label for="booking-phone">' . language_text('Phone number') . '</label>
            <input id="booking-phone" name="customer_phone" type="tel" maxlength="32" autocomplete="tel" required>
            <label for="booking-date">' . language_text('Date and time') . '</label>
            <input id="booking-date" name="booking_date" type="datetime-local" min="' .
                htmlspecialchars(date('Y-m-d\TH:i', time() + 60), ENT_QUOTES, 'UTF-8') . '" required>
            <button type="submit" name="createBooking">' . language_text('Book now') . '</button>
        </form></section>';
    }

    public static function MyBookings($arr) {
        echo '<section class="bookings-page"><h1>' . language_text('My bookings') . '</h1>';
        self::FlashMessage();
        if (!$arr) {
            echo '<p class="muted">' . language_text('You have no bookings yet.') . '</p></section>';
            return;
        }

        echo '<div class="table-wrap"><table class="bookings-table">
            <thead><tr>
                <th>' . language_text('Service') . '</th>
                <th>' . language_text('Date and time') . '</th>
                <th>' . language_text('Status') . '</th>
                <th>' . language_text('Action') . '</th>
            </tr></thead><tbody>';
        foreach ($arr as $booking) {
            $isFutureActive = $booking['status'] === 'active'
                && strtotime($booking['booking_date']) > time();
            $status = $booking['status'] === 'cancelled'
                ? language_text('Cancelled')
                : ($isFutureActive ? language_text('Active') : language_text('Past'));
            $title = !empty($booking['title'])
                ? localized_service_title($booking)
                : language_text('Service no longer available');
            echo '<tr>
                <td>' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</td>
                <td><time datetime="' . htmlspecialchars(date('c', strtotime($booking['booking_date'])), ENT_QUOTES, 'UTF-8') . '">' .
                    htmlspecialchars(date('d.m.Y H:i', strtotime($booking['booking_date'])), ENT_QUOTES, 'UTF-8') . '</time></td>
                <td>' . htmlspecialchars($status, ENT_QUOTES, 'UTF-8') . '</td>
                <td>';
            if ($isFutureActive) {
                echo '<form class="cancel-booking-form" action="cancelBooking" method="post">
                    <input type="hidden" name="booking_id" value="' . (int)$booking['booking_id'] . '">
                    <input type="hidden" name="csrf_token" value="' .
                        htmlspecialchars(Booking::getCsrfToken(), ENT_QUOTES, 'UTF-8') . '">
                    <button class="button-secondary" type="submit" name="cancelBooking">' .
                        language_text('Cancel booking') . '</button>
                </form>';
            } else {
                echo '—';
            }
            echo '</td></tr>';
        }
        echo '</tbody></table></div></section>';
    }

    private static function FlashMessage() {
        if (empty($_SESSION['booking_flash'])) {
            return;
        }

        $flash = $_SESSION['booking_flash'];
        unset($_SESSION['booking_flash']);
        $type = isset($flash['type']) && $flash['type'] === 'success' ? 'notice-success' : 'notice-error';
        $message = isset($flash['message']) ? $flash['message'] : '';
        echo '<p class="notice ' . $type . '">' .
            htmlspecialchars(language_text($message), ENT_QUOTES, 'UTF-8') . '</p>';
    }
}
?>
