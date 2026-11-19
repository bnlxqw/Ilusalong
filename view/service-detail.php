<?php
ob_start();
?>
<br>
<?php
ViewServices::ReadService($service);

echo "<br>";
ViewBookings::BookingForm($service['service_id'], $specialists, $bookedSlots);

echo "<br>";
Controller::ReviewsCountWithAnchor($service['service_id']);
Controller::Reviews($service['service_id']);

echo "<br>";
ViewReviews::ReviewsForm($service['service_id']);

$content = ob_get_clean();
include_once 'view/layout.php';

?>