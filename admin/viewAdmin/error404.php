<?php ob_start() ?>
<h2>Error 404</h2>
<article>

    <h3>404 - Page Not Found</h3>
<p>The page you are looking for is not found.</p>

</article>
<?php $content = ob_get_clean(); ?>

<?php include "viewAdmin/templates/layout.php";