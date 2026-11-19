<?php
session_start();
include_once 'inc/Database.php';
include_once 'inc/ServiceImage.php';
include_once 'inc/Language.php';
require 'model/Category.php';
require 'model/Services.php';
require 'model/Reviews.php';
require 'model/Register.php';
require 'model/Booking.php';
require 'model/Specialist.php';
require 'model/UserAuth.php';
require 'inc/LoginAttemptLimiter.php';
require 'inc/ReviewRateLimiter.php';

include_once 'view/services.php';
include_once 'view/reviews.php';
include_once 'view/bookings.php';


include_once 'controller/Controller.php';
include_once 'route/routing.php';


echo $response;
?>