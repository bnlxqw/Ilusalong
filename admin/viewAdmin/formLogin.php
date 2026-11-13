<?php
if (modelAdmin::isAdminAuthenticated()) {
    header('Location: servicesAdmin');
    exit;
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ilusalong — Admin login</title>
    <link href="../style.css" rel="stylesheet">
    <link href="public/css/login.css" rel="stylesheet">
  </head>
  <body>
    <main class="login-page">
      <a class="login-brand" href="../">ILUSALONG</a>
      <form class="form-signin" action="login" method="POST">
        <h1 class="form-signin-heading">Admin sign in</h1>
        <p class="login-intro">Sign in to manage salon services.</p>
        <label for="email">Email</label>
        <input id="email" type="email" name="email" placeholder="Email address" autocomplete="username" required autofocus>
        <label for="password">Password</label>
        <input id="password" type="password" name="password" placeholder="Password" autocomplete="current-password" required>
        <button type="submit" name="btnLogin">Sign in</button>
        <?php if (isset($_SESSION['errorString'])) { ?>
            <p class="login-error">
            <?php
            echo htmlspecialchars($_SESSION['errorString'], ENT_QUOTES, 'UTF-8');
            unset($_SESSION['errorString']);
            ?>
            </p>
        <?php } ?>
        <p class="login-back"><a href="../">Back to website</a></p>
      </form>
    </main>
  </body>
</html>