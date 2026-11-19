<?php
if (modelAdmin::isAdminAuthenticated()) {
    header('Location: servicesAdmin');
    exit;
}
?>
<!DOCTYPE html>
<html lang="<?php echo current_language(); ?>">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars(language_text('Ilusalong — Admin dashboard'), ENT_QUOTES, 'UTF-8'); ?></title>
    <link href="../style.css" rel="stylesheet">
    <link href="public/css/login.css" rel="stylesheet">
  </head>
  <body>
    <main class="login-page">
      <a class="login-brand" href="../">ILUSALONG</a>
      <form class="form-signin" action="login" method="POST">
        <h1 class="form-signin-heading"><?php echo language_text('Admin sign in'); ?></h1>
        <p class="login-intro"><?php echo language_text('Sign in to manage salon services.'); ?></p>
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(modelAdmin::getCsrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
        <label for="email"><?php echo language_text('Email'); ?></label>
        <input id="email" type="email" name="email" placeholder="<?php echo htmlspecialchars(language_text('Email address'), ENT_QUOTES, 'UTF-8'); ?>" autocomplete="username" required autofocus>
        <label for="password"><?php echo language_text('Password'); ?></label>
        <input id="password" type="password" name="password" placeholder="<?php echo htmlspecialchars(language_text('Password'), ENT_QUOTES, 'UTF-8'); ?>" autocomplete="current-password" required>
        <button type="submit" name="btnLogin"><?php echo language_text('Sign in'); ?></button>
        <?php if (isset($_SESSION['errorString'])) { ?>
            <p class="login-error">
            <?php
            echo htmlspecialchars(language_text($_SESSION['errorString']), ENT_QUOTES, 'UTF-8');
            unset($_SESSION['errorString']);
            ?>
            </p>
        <?php } ?>
        <p class="login-back"><a href="../"><?php echo language_text('Back to website'); ?></a></p>
      </form>
      <?php echo language_switcher(); ?>
    </main>
  </body>
</html>