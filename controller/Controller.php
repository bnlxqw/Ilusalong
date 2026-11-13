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
        include_once 'view/service-detail.php';
    }

        public static function error404() {
        include_once 'view/error404.php';
    }

        public static function InsertReview($text, $id) {
        Reviews::insertReview($text, $id);
        header('Location:service?service_id='.$id.'#reviews');
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
    
}