<?php
session_start();
include_once 'inc/Database.php';
include_once 'inc/ServiceImage.php';
require 'model/Category.php';
require 'model/Services.php';
require 'model/Reviews.php';
require 'model/Register.php';

include_once 'view/services.php';
include_once 'view/reviews.php';


include_once 'controller/Controller.php';
include_once 'route/routing.php';


echo $response;
?>