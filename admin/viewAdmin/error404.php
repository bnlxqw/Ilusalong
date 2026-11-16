<?php ob_start() ?>
<h2><?php echo language_text('Error 404'); ?></h2>
<article>

    <h3><?php echo language_text('404 - Page Not Found'); ?></h3>
<p><?php echo language_text('The page you are looking for is not found.'); ?></p>

</article>
<?php $content = ob_get_clean(); ?>

<?php include "viewAdmin/templates/layout.php";