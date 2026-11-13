<?php
ob_start();
?>
<h1>Page not found</h1>
<?php
$content = ob_get_clean();
include_once 'view/layout.php';
?>