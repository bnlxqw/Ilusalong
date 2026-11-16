<?php ob_start(); ?>
<h1><?php echo language_text('Create an account'); ?></h1>
<form class="register-form" method="POST" action="registerAnswer">
    <div class="form-group">
        <label for="name"><?php echo language_text('Name'); ?></label>
        <input id="name" type="text" name="name" required autofocus>
    </div>
    <div class="form-group">
        <label for="email"><?php echo language_text('Email address'); ?></label>
        <input id="email" type="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="password"><?php echo language_text('Password'); ?></label>
        <input id="password" type="password" name="password" required>
    </div>
    <div class="form-group">
        <label for="password-confirm"><?php echo language_text('Confirm password'); ?></label>
        <input id="password-confirm" type="password" name="confirm" required>
    </div>
    <button type="submit" name="save"><?php echo language_text('Register'); ?></button>
</form>
<?php
$content = ob_get_clean();
include_once 'view/layout.php';
?>
