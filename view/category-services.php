<?php
ob_start();
?>
<h1><?php echo language_text('Category services'); ?></h1>
<br>
<?php ViewServices::ServicesByCategory($arr);

$content = ob_get_clean();

include_once 'view/layout.php';
?>