<?php
    $host = explode('?', $_SERVER['REQUEST_URI']) [0];
    $hum = substr_count($host, '/');
    $path = explode('/', $host)[$hum];

    
    if($path == '' OR $path == 'index' OR $path == 'index.php') {
        $response = Controller::StartSite();
    }

    elseif($path == 'services' || $path == 'all') {
        $response = Controller::AllServices();
    }
    elseif($path == 'specialists') {
        $response = Controller::AllSpecialists();
    }
    elseif($path == 'category' and isset($_GET['category_id'])) {
        $response = Controller::ServicesByCategoryID($_GET['category_id']);
    }
    elseif($path == 'service' and isset($_GET['service_id'])) {
        $response = Controller::ServiceByID($_GET['service_id']);
    }
    elseif($path == 'createBooking' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $response = Controller::createBooking();
    }
    elseif($path == 'myBookings') {
        $response = Controller::myBookings();
    }
    elseif($path == 'cancelBooking' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $response = Controller::cancelBooking();
    }
    elseif($path == 'insertreview' && isset($_POST['review'], $_POST['service_id']))
    {
        $response = Controller::InsertReview($_POST['review'], $_POST['service_id']);
    }

elseif ($path == 'registerForm' )
{

    $response = Controller::registerForm();
}
elseif ($path == 'registerAnswer' )
{

    $response = Controller::registerUser();
}

    else{
            $response = Controller::error404();
    }


?>