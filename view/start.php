<?php
ob_start();
?>
<div class="hero">
    <p class="eyebrow"><?php echo language_text('ILUSALONG · BEAUTY &amp; CARE'); ?></p>
    <h1><?php echo language_text('Make time for yourself'); ?></h1>
    <p><?php echo language_text('Professional care, a welcoming atmosphere, and attention to every detail.'); ?></p>
    <a class="button-link" href="services"><?php echo language_text('Explore services'); ?></a>
</div>
<h2 class="section-title"><?php echo language_text('Popular services'); ?></h2>
<br>
<?php
ViewServices::ServicesByCategory($arr);

$content = ob_get_clean();

include_once 'view/layout.php';
?>