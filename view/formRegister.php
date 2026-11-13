<?php ob_start(); ?>
<h1>Create an account</h1>
<form class="register-form" method="POST" action="registerAnswer">
    <div class="form-group">
        <label for="name">Name</label>
        <input id="name" type="text" name="name" required autofocus>
    </div>
    <div class="form-group">
        <label for="email">Email address</label>
        <input id="email" type="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input id="password" type="password" name="password" required>
    </div>
    <div class="form-group">
        <label for="password-confirm">Confirm password</label>
        <input id="password-confirm" type="password" name="confirm" required>
    </div>
    <button type="submit" name="save">Register</button>
</form>
<?php
$content = ob_get_clean();
include_once 'view/layout.php';
?>
