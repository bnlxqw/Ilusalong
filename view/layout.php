<!DOCTYPE html>
<html lang="<?php echo current_language(); ?>">
    <head>
        <title><?php echo htmlspecialchars(language_text('Ilusalong — Beauty Salon'), ENT_QUOTES, 'UTF-8'); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="<?php echo htmlspecialchars(language_text('Beauty services and care at Ilusalong'), ENT_QUOTES, 'UTF-8'); ?>">
        <link rel="stylesheet" type="text/css" href="style.css?v=11">
        <meta charset="utf-8">
    
    </head>
    <body>

    <header class="site-header">
      <nav class="nav-wrap" aria-label="<?php echo htmlspecialchars(language_text('Main navigation'), ENT_QUOTES, 'UTF-8'); ?>">
        <a class="brand" href="./">ILUSALONG</a>
        <ul class="topmenu">
        <li class="has-submenu"><a href="services"><?php echo language_text('Services'); ?></a>
          <ul class="submenu">
          <?php
                Controller::AllCategory();
            ?>
          </ul>
        </li>
        <li><a href="specialists"><?php echo language_text('Specialists'); ?></a></li>
        <?php if (UserAuth::isAuthenticated()) { ?>
          <li><a href="myProfile"><?php echo language_text('My profile'); ?></a></li>
          <li>
            <form class="nav-logout-form" method="POST" action="logout">
              <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(UserAuth::getCsrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
              <button class="nav-logout-button" type="submit"><?php echo language_text('Log out'); ?></button>
            </form>
          </li>
        <?php } else { ?>
          <li><a href="registerForm"><?php echo language_text('Register'); ?></a></li>
          <li><a href="login"><?php echo language_text('Log in'); ?></a></li>
        <?php } ?>
        <li><a href="myBookings"><?php echo language_text('My bookings'); ?></a></li>
       </ul>
       <?php echo language_switcher(); ?>
      </nav>
    </header>

    <main class="page-content">
            <?php
            if(isset($content)) {
                echo $content;
            }
            else{
                echo '<h1>' . language_text('Unable to load content') . '</h1>';
            }
            ?>
    </main>

        <footer class="site-footer"><?php echo language_text('© Ilusalong — Beauty begins with self-care'); ?></footer>
    </body>

</html>