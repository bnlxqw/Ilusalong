<?php
ob_start();
?>
<div class="hero">
    <p class="eyebrow">ILUSALONG · BEAUTY &amp; CARE</p>
    <h1>Make time for yourself</h1>
    <p>Professional care, a welcoming atmosphere, and attention to every detail.</p>
    <a class="button-link" href="services">Explore services</a>
</div>
<h2 class="section-title">Popular services</h2>
<br>
<?php
ViewServices::ServicesByCategory($arr);

$content = ob_get_clean();

include_once 'view/layout.php';
?>