<?php
$host = explode('?', $_SERVER['REQUEST_URI']) [0];
$num=substr_count($host,'/');
$path = explode('/', $host)[$num];

if ($path == '' OR $path == 'index.php' )
{
    $response = controllerAdmin::formLoginSite();
}

elseif ($path == 'login')
{
    $response = controllerAdmin::loginAction();
}
elseif ($path == 'logout')
{
    $response = controllerAdmin::logoutAction();
}
elseif (!controllerAdmin::requireAdmin()) {
    $response = null;
}
elseif ($path == 'categoriesAdmin') {
    $response = controllerAdmin::categoriesList();
}
elseif ($path == 'servicesAdmin') {
    $response = controllerAdminServices::ServicesList();
}
elseif ($path == 'serviceAdd') {
    $response = controllerAdminServices::serviceAddForm();
}
elseif ($path == 'serviceAddResult') {
    $response = controllerAdminServices::serviceAddResult();
}
elseif ($path == 'serviceEdit' && isset($_GET['service_id'])) {
    $response = controllerAdminServices::serviceEditForm($_GET['service_id']);
}
elseif ($path == 'serviceEditResult' && isset($_GET['service_id'])) {
    $response = controllerAdminServices::serviceEditResult($_GET['service_id']);
}
elseif ($path == 'serviceDel' && isset($_GET['service_id'])) {
    $response = controllerAdminServices::serviceDeleteForm($_GET['service_id']);
}
elseif ($path == 'serviceDelResult' && isset($_GET['service_id'])) {
    $response = controllerAdminServices::serviceDeleteResult($_GET['service_id']);
}
else {
    $response = controllerAdmin::error404();
}