<?php
ob_start();
?>
<h1><?php echo language_text('All services'); ?></h1>
<br>

<?php
ViewServices::AllServices($arr);
$content = ob_get_clean();
include_once 'view/layout.php';

?>