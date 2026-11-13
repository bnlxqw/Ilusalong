<!DOCTYPE html>
<html>
    <head>
        <title>Ilusalong — Beauty Salon</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Beauty services and care at Ilusalong">
        <link rel="stylesheet" type="text/css" href="style.css">
        <meta charset="utf-8">
    
    </head>
    <body>

    <header class="site-header">
      <nav class="nav-wrap" aria-label="Main navigation">
        <a class="brand" href="./">ILUSALONG</a>
        <ul class="topmenu">
        <li class="has-submenu"><a href="services">Services</a>
          <ul class="submenu">
          <?php
                Controller::AllCategory();
            ?>
          </ul>
        </li>
        <li><a href="registerForm">Register</a></li>
       </ul>
      </nav>
    </header>

    <main class="page-content">
            <?php
            if(isset($content)) {
                echo $content;
            }
            else{
                echo '<h1>Unable to load content</h1>';
            }
            ?>
    </main>

        <footer class="site-footer">© Ilusalong — Beauty begins with self-care</footer>
    </body>

</html>