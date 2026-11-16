<?php ob_start(); ?>
<section class="admin-panel">
    <h1><?php echo language_text('Service categories'); ?></h1>
    <?php if (!$arr) { ?>
        <p class="admin-empty"><?php echo language_text('No service categories have been added yet.'); ?></p>
    <?php } else { ?>
        <div class="table-responsive">
            <table class="table table-bordered admin-table">
                <thead>
                    <tr><th><?php echo language_text('Category ID'); ?></th><th><?php echo language_text('Name'); ?></th></tr>
                </thead>
                <tbody>
                    <?php foreach ($arr as $category) { ?>
                        <tr>
                            <td><?php echo (int)$category['category_id']; ?></td>
                            <td><?php echo htmlspecialchars(localized_category_name($category), ENT_QUOTES, 'UTF-8'); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
</section>
<?php
$content = ob_get_clean();
include 'viewAdmin/templates/layout.php';
?>
