<?php
ob_start();

if(isset($result)){
    if($result[0]==true)
    {
        ?>
        <section class="notice notice-success">
            <strong><?php echo language_text('Account created.'); ?></strong>
            <a href="login"><?php echo language_text('Log in'); ?></a>
        </section>
        <?php
    }
    else if($result[0]==false)
    {
        ?>
        <section class="notice notice-error">
            <strong><?php echo language_text('Registration failed.'); ?></strong>
            <?php echo nl2br(htmlspecialchars(language_text(strip_tags($result[1])), ENT_QUOTES, 'UTF-8')); ?>
            <a href="registerForm"><?php echo language_text('Back to registration'); ?></a>
        </section>
        <?php
    }
}
?>

<?php $content = ob_get_clean(); ?>

<?php include "view/layout.php"; ?>