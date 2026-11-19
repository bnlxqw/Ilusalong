<?php

class Controller {

    public static function StartSite() {
        $arr = Services::getLast10Services();
        include_once 'view/start.php';
    }

    public static function AllCategory() {
        $arr = Category::getAllCategory();
        include_once 'view/category.php';
    }

    public static function AllServices() {
        $arr = Services::getAllServices();
        include_once 'view/service-list.php';
    }

    public static function ServicesByCategoryID($id) {
        $arr = Services::getServicesByCategoryID($id);
        include_once 'view/category-services.php';
    }

    public static function ServiceByID($id) {
        $service = Services::getServiceByID($id);
        if (!$service) {
            self::error404();
            return;
        }
        $specialists = Specialist::getActiveForService($service['service_id']);
        $requestedSpecialistId = isset($_GET['specialist_id'])
            ? filter_var($_GET['specialist_id'], FILTER_VALIDATE_INT)
            : false;
        $selectedSpecialistId = 0;
        foreach ($specialists as $specialist) {
            if ($requestedSpecialistId !== false
                && (int)$specialist['specialist_id'] === (int)$requestedSpecialistId) {
                $selectedSpecialistId = (int)$specialist['specialist_id'];
                break;
            }
        }
        $bookedSlots = Booking::getUpcomingBookedSlots($specialists);
        include_once 'view/service-detail.php';
    }

    public static function AllSpecialists() {
        $arr = Specialist::getAllActive();
        $servicesBySpecialist = Specialist::getServicesForSpecialists($arr);
        ob_start();
        include 'view/specialists.php';
        $content = ob_get_clean();
        include_once 'view/layout.php';
    }

        public static function error404() {
        include_once 'view/error404.php';
    }

        public static function InsertReview($text, $id) {
        $error = null;
        $ok    = Reviews::insertReview($text, $id, $error);
        $_SESSION['review_flash'] = array(
            'type'    => $ok ? 'success' : 'error',
            'message' => $ok ? 'Review submitted.' : ($error ?? 'Could not submit review.'),
        );
        $serviceId = filter_var($id, FILTER_VALIDATE_INT);
        if ($serviceId && $serviceId > 0) {
            header('Location: service?service_id=' . $serviceId . '#reviews');
        } else {
            header('Location: services');
        }
        exit;
    }

        public static function Reviews($serviceId) {
            $arr = Reviews::getReviewsByServiceID($serviceId);
            ViewReviews::ReviewsByService($arr);
    }        

        public static function ReviewsCount($serviceId) {
            $arr = Reviews::getReviewsCountByServiceID($serviceId);
            ViewReviews::ReviewsCount($arr);
    }
    
        public static function ReviewsCountWithAnchor($serviceId) {
            $arr = Reviews::getReviewsCountByServiceID($serviceId);
            ViewReviews::ReviewsCountWithAnchor($arr);       
    }

    public static function registerForm()
    {
        include_once('view/formRegister.php');
    }
    public static function registerUser()
    {
        $result = Register::registerUser();

        include_once('view/answerRegister.php');
    }

    public static function loginForm() {
        if (UserAuth::isAuthenticated()) {
            header('Location: myProfile');
            exit;
        }
        if (isset($_SESSION['auth_flash'])) {
            $error = $_SESSION['auth_flash'];
            unset($_SESSION['auth_flash']);
        }
        include_once 'view/login.php';
    }

    public static function userLogin() {
        $error = null;
        if (UserAuth::login($error)) {
            header('Location: myProfile');
            exit;
        }
        include_once 'view/login.php';
    }

    public static function myProfile() {
        $profile = UserAuth::getProfile();
        if (!$profile) {
            header('Location: login');
            exit;
        }
        include_once 'view/profile.php';
    }

    public static function userLogout() {
        $error = null;
        if (!UserAuth::logout($error)) {
            if (!UserAuth::isAuthenticated()) {
                $_SESSION['auth_flash'] = $error;
                header('Location: login');
                exit;
            }
            $profile = UserAuth::getProfile();
            if (!$profile) {
                header('Location: login');
                exit;
            }
            include_once 'view/profile.php';
            return;
        }
        header('Location: ./');
        exit;
    }

    public static function myBookings() {
        if (!UserAuth::getProfile()) {
            header('Location: login');
            exit;
        }
        $arr = Booking::getBookingsByUser();
        ob_start();
        ViewBookings::MyBookings($arr);
        $content = ob_get_clean();
        include_once 'view/layout.php';
    }

    public static function createBooking() {
        $error = null;
        if (Booking::createBooking($error)) {
            $_SESSION['booking_flash'] = array(
                'type' => 'success',
                'message' => 'Booking confirmed.'
            );
            header('Location: myBookings');
            exit;
        }

        $_SESSION['booking_flash'] = array(
            'type' => 'error',
            'message' => $error === null ? 'Could not create booking.' : $error
        );
        $serviceId = isset($_POST['service_id']) ? (int)$_POST['service_id'] : 0;
        if ($serviceId > 0) {
            header('Location: service?service_id=' . $serviceId . '#booking');
        } else {
            header('Location: services');
        }
        exit;
    }

    public static function cancelBooking() {
        $error = null;
        $bookingId = isset($_POST['booking_id']) ? $_POST['booking_id'] : null;
        $cancelled = Booking::cancelBooking($bookingId, $error);
        $_SESSION['booking_flash'] = array(
            'type' => $cancelled ? 'success' : 'error',
            'message' => $cancelled
                ? 'Booking cancelled.'
                : ($error === null ? 'Could not cancel booking.' : $error)
        );
        header('Location: myBookings');
        exit;
    }
    
}