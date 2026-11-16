<?php ob_start(); ?>
<h1><?php echo language_text('Log in'); ?></h1>
<?php if (isset($error) && $error !== null) { ?>
    <p class="notice notice-error"><?php echo htmlspecialchars(language_text($error), ENT_QUOTES, 'UTF-8'); ?></p>
<?php } ?>
<form class="register-form" method="POST" action="login">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(UserAuth::getCsrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
    <div class="form-group">
        <label for="login-email"><?php echo language_text('Email address'); ?></label>
        <input id="login-email" type="email" name="email" maxlength="50" autocomplete="username" required autofocus>
    </div>
    <div class="form-group">
        <label for="login-password"><?php echo language_text('Password'); ?></label>
        <input id="login-password" type="password" name="password" autocomplete="current-password" required>
    </div>
    <button type="submit"><?php echo language_text('Log in'); ?></button>
</form>
<p class="account-prompt"><?php echo language_text('Don’t have an account?'); ?> <a href="registerForm"><?php echo language_text('Register'); ?></a></p>
<?php
$content = ob_get_clean();
include_once 'view/layout.php';
?>
