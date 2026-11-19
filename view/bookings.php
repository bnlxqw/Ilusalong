<?php
class ViewBookings {
    public static function BookingForm($serviceId, $specialists, $bookedSlots) {
        echo '<section class="booking-section" id="booking"><h2>' . language_text('Book this service') . '</h2>';
        self::FlashMessage();
        $profile = UserAuth::getProfile();
        if (!$profile) {
            echo '<p class="notice">' . language_text('Please log in to book a service.') .
                ' <a href="login">' . language_text('Log in') . '</a> · ' .
                '<a href="registerForm">' . language_text('Register') . '</a></p></section>';
            return;
        }
        if (!$specialists) {
            echo '<p class="notice notice-error">' . language_text('No specialists are currently available for booking.') . '</p></section>';
            return;
        }
        echo '<form class="booking-form" action="createBooking" method="post">
            <input type="hidden" name="service_id" value="' . (int)$serviceId . '">
            <input type="hidden" name="csrf_token" value="' .
                htmlspecialchars(Booking::getCsrfToken(), ENT_QUOTES, 'UTF-8') . '">
            <label for="booking-specialist">' . language_text('Choose a specialist') . '</label>
            <select id="booking-specialist" name="specialist_id" required>
                <option value="">' . language_text('Select a specialist') . '</option>';
        foreach ($specialists as $specialist) {
            echo '<option value="' . (int)$specialist['specialist_id'] . '">' .
                htmlspecialchars($specialist['name'] . ' — ' . $specialist['specialty'], ENT_QUOTES, 'UTF-8') .
                '</option>';
        }
        echo '</select>';
        self::UpcomingAvailability($specialists, $bookedSlots);
        echo '
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

    private static function UpcomingAvailability($specialists, $bookedSlots) {
        if (!$specialists) {
            return;
        }

        $slotsBySpecialist = array();
        foreach ($bookedSlots as $slot) {
            $specialistId = (int)$slot['specialist_id'];
            $date = date('Y-m-d', strtotime($slot['booking_date']));
            if (!isset($slotsBySpecialist[$specialistId][$date])) {
                $slotsBySpecialist[$specialistId][$date] = array();
            }
            $slotsBySpecialist[$specialistId][$date][] = $slot['booking_date'];
        }

        echo '<details class="booking-availability" open><summary>' .
            language_text('See booked dates and times') . '</summary>';
        foreach ($specialists as $specialist) {
            $specialistId = (int)$specialist['specialist_id'];
            echo '<section class="booked-specialist-schedule"><h3>' .
                htmlspecialchars($specialist['name'] . ' — ' . $specialist['specialty'], ENT_QUOTES, 'UTF-8') .
                '</h3>';
            if (empty($slotsBySpecialist[$specialistId])) {
                echo '<p class="muted">' . language_text('No upcoming bookings.') . '</p>';
            } else {
                echo '<ul class="booked-day-list">';
                foreach ($slotsBySpecialist[$specialistId] as $date => $slots) {
                    echo '<li class="booked-day"><time datetime="' . htmlspecialchars($date, ENT_QUOTES, 'UTF-8') . '">' .
                        htmlspecialchars(date('d.m.Y', strtotime($date)), ENT_QUOTES, 'UTF-8') .
                        '</time><span class="booked-time-list">';
                    foreach ($slots as $slot) {
                        echo '<time class="booked-time" datetime="' .
                            htmlspecialchars(date('c', strtotime($slot)), ENT_QUOTES, 'UTF-8') . '">' .
                            htmlspecialchars(date('H:i', strtotime($slot)), ENT_QUOTES, 'UTF-8') .
                            '</time>';
                    }
                    echo '</span></li>';
                }
                echo '</ul>';
            }
            echo '</section>';
        }
        echo '</details>';
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
                <th>' . language_text('Specialist') . '</th>
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
                <td data-label="' . htmlspecialchars(language_text('Service'), ENT_QUOTES, 'UTF-8') . '">' .
                    htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</td>
                <td data-label="' . htmlspecialchars(language_text('Specialist'), ENT_QUOTES, 'UTF-8') . '">' .
                    htmlspecialchars(!empty($booking['specialist_name'])
                        ? $booking['specialist_name']
                        : language_text('Specialist no longer available'), ENT_QUOTES, 'UTF-8') . '</td>
                <td data-label="' . htmlspecialchars(language_text('Date and time'), ENT_QUOTES, 'UTF-8') . '"><time datetime="' .
                    htmlspecialchars(date('c', strtotime($booking['booking_date'])), ENT_QUOTES, 'UTF-8') . '">' .
                    htmlspecialchars(date('d.m.Y H:i', strtotime($booking['booking_date'])), ENT_QUOTES, 'UTF-8') . '</time></td>
                <td data-label="' . htmlspecialchars(language_text('Status'), ENT_QUOTES, 'UTF-8') . '">' .
                    htmlspecialchars($status, ENT_QUOTES, 'UTF-8') . '</td>
                <td data-label="' . htmlspecialchars(language_text('Action'), ENT_QUOTES, 'UTF-8') . '">';
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
