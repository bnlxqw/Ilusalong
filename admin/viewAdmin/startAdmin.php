<?php ob_start() ?>
<article>
<div id="main" class="container">
    <h1>Salon admin dashboard</h1>
    <div class="row">
        <p>Manage services and salon categories.</p>
        <p><a class="btn btn-primary" href="servicesAdmin">Manage services</a>
           <a class="btn btn-default" href="categoriesAdmin">View categories</a></p>
    </div>
</div>
</article>

<?php $content = ob_get_clean(); ?>
<?php include "viewAdmin/templates/layout.php";