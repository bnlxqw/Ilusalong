<?php ob_start(); ?>
<section class="profile-page">
    <h1><?php echo language_text('My profile'); ?></h1>
    <?php if (isset($error) && $error !== null) { ?>
        <p class="notice notice-error"><?php echo htmlspecialchars(language_text($error), ENT_QUOTES, 'UTF-8'); ?></p>
    <?php } ?>
    <div class="profile-card">
        <dl class="profile-details">
            <div>
                <dt><?php echo language_text('Name'); ?></dt>
                <dd><?php echo htmlspecialchars($profile['username'], ENT_QUOTES, 'UTF-8'); ?></dd>
            </div>
            <div>
                <dt><?php echo language_text('Email address'); ?></dt>
                <dd><?php echo htmlspecialchars($profile['email'], ENT_QUOTES, 'UTF-8'); ?></dd>
            </div>
        </dl>
        <a class="button-link" href="myBookings"><?php echo language_text('My bookings'); ?></a>
        <form class="profile-logout-form" method="POST" action="logout">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(UserAuth::getCsrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
            <button class="button-secondary" type="submit"><?php echo language_text('Log out'); ?></button>
        </form>
    </div>
</section>
<?php
$content = ob_get_clean();
include_once 'view/layout.php';
?>
