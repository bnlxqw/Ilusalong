<!DOCTYPE html>
<html>
<head>
    <title>Ilusalong — Admin dashboard</title>
    <link href="public/css/bootstrap.css" rel="stylesheet">
    <link href="../style.css" rel="stylesheet">
    <link href="public/css/mystyle.css" rel="stylesheet">
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
        <nav class="nav-wrap admin-nav" aria-label="Admin navigation">
        <?php
        if (isset($_SESSION["user_id"]) && isset($_SESSION["sessionId"])) {
        ?>
            <a class="brand" href="../">ILUSALONG</a>
            <ul class="topmenu">
                <li><a href="./">Dashboard</a></li>
                <li><a href="categoriesAdmin">Categories</a></li>
                <li><a href="servicesAdmin">Services</a></li>
                <li><span class="admin-user"><?php echo htmlspecialchars($_SESSION["username"], ENT_QUOTES, "UTF-8"); ?></span></li>
                <li><a href="logout">Log out</a></li>
            </ul>
        <?php
        }
        ?>
        </nav>
    </header>
    <main id="content" class="page-content admin-content">
            <?php echo $content; ?>
    </main>
    <footer class="site-footer">© Ilusalong · Admin dashboard</footer>
</body>
</html>