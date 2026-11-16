<?php ob_start() ?>
<article>
<div id="main" class="container">
    <h1><?php echo language_text('Salon admin dashboard'); ?></h1>
    <div class="row">
        <p><?php echo language_text('Manage services and salon categories.'); ?></p>
        <p><a class="btn btn-primary" href="servicesAdmin"><?php echo language_text('Manage services'); ?></a>
           <a class="btn btn-default" href="categoriesAdmin"><?php echo language_text('View categories'); ?></a></p>
    </div>
</div>
</article>

<?php $content = ob_get_clean(); ?>
<?php include "viewAdmin/templates/layout.php";