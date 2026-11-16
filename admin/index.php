<?php
session_start();
    require_once '../inc/database.php';
    require_once '../inc/ServiceImage.php';
    require_once '../inc/Language.php';

    include_once("modelAdmin/modelAdmin.php");
    include_once("modelAdmin/modelAdminServices.php");
    include_once("modelAdmin/modelAdminCategory.php");
    include_once("modelAdmin/modelAdminSpecialists.php");


    include_once("controllerAdmin/controllerAdmin.php");
    include_once("controllerAdmin/controllerAdminServices.php");

    include('routeAdmin/routingAdmin.php');//!!!!

    echo $response;