<?php
ob_start();

if(isset($result)){
    if($result[0]==true)
    {
        ?>
        <section class="notice notice-success">
            <strong>Account created.</strong>
            <a href="./">Home</a>
        </section>
        <?php
    }
    else if($result[0]==false)
    {
        ?>
        <section class="notice notice-error">
            <strong>Registration failed.</strong>
            <?php echo htmlspecialchars($result[1], ENT_QUOTES, 'UTF-8'); ?>
            <a href="registerForm">Back to registration</a>
        </section>
        <?php
    }
}
?>

<?php $content = ob_get_clean(); ?>

<?php include "view/layout.php"; ?>