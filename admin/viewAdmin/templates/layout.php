<!DOCTYPE html>
<html lang="<?php echo current_language(); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars(language_text('Ilusalong — Admin dashboard'), ENT_QUOTES, 'UTF-8'); ?></title>
    <link href="public/css/bootstrap.css" rel="stylesheet">
    <link href="../style.css" rel="stylesheet">
    <link href="public/css/mystyle.css?v=2" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="public/css/font-awesome.min.css">
    <!-- SCRIPT -->
    <script src="public/js/jquery.min.js"></script>
    <script src="public/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="public/js/ajaxupload.3.5.js"></script>
    <script defer src="public/js/service-image-upload.js"></script>
</head>
<body>
    <header class="site-header">
        <nav class="nav-wrap admin-nav" aria-label="<?php echo htmlspecialchars(language_text('Admin navigation'), ENT_QUOTES, 'UTF-8'); ?>">
        <?php
        if (isset($_SESSION["user_id"]) && isset($_SESSION["sessionId"])) {
        ?>
            <a class="brand" href="../">ILUSALONG</a>
            <ul class="topmenu">
                <li><a href="./"><?php echo language_text('Dashboard'); ?></a></li>
                <li><a href="categoriesAdmin"><?php echo language_text('Categories'); ?></a></li>
                <li><a href="servicesAdmin"><?php echo language_text('Services'); ?></a></li>
                <li><span class="admin-user"><?php echo htmlspecialchars($_SESSION["username"], ENT_QUOTES, "UTF-8"); ?></span></li>
                <li><a href="logout"><?php echo language_text('Log out'); ?></a></li>
            </ul>
            <?php echo language_switcher(); ?>
        <?php
        }
        ?>
        </nav>
    </header>
    <main id="content" class="page-content admin-content">
            <?php echo $content; ?>
    </main>
    <footer class="site-footer"><?php echo language_text('© Ilusalong · Admin dashboard'); ?></footer>
</body>
</html>