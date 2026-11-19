<?php ob_start(); ?>

<div class="container" style="min-height:400px;">
<div class="col-md-11">

<h2><?php echo language_text('Add service'); ?></h2>

<?php
if (isset($uploadError) && $uploadError !== null) {
    echo '<div class="alert alert-warning">' . htmlspecialchars(language_text($uploadError), ENT_QUOTES, 'UTF-8') . '</div>';
}
if (isset($test)) {
    if ($test == true) {
        echo '<div class="alert alert-info">';
        echo '<strong>' . language_text('Service added.') . ' </strong><a href="servicesAdmin">' . language_text('Back to services') . '</a>';
        echo '</div>';
    } else if ($test == false) {
        echo '<div class="alert alert-warning">';
        echo '<strong>' . language_text('Unable to add service.') . ' </strong><a href="servicesAdmin">' . language_text('Back to services') . '</a>';
        echo '</div>';
    }
} else {
?>

<form method="POST" action="serviceAddResult" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(modelAdmin::getCsrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
    <table class="table table-bordered">
        <tr>
            <td><?php echo language_text('Service name'); ?></td>
            <td><input type="text" name="title" class="form-control" required></td>
        </tr>
        <tr>
            <td><?php echo language_text('Description'); ?></td>
            <td><textarea rows="5" name="text" class="form-control" required></textarea></td>
        </tr>
        <tr>
            <td><?php echo language_text('Category'); ?></td>
            <td>
                <select name="category_id" class="form-control" required>
                <?php
                if (isset($arr) && is_array($arr)) {
                    foreach ($arr as $row) {
                        echo '<option value="' . (int)$row['category_id'] . '">' . htmlspecialchars(localized_category_name($row), ENT_QUOTES, 'UTF-8') . '</option>';
                    }
                }
                ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><?php echo language_text('Price (€)'); ?></td>
            <td><input type="number" name="price" class="form-control" min="0" step="0.01" required></td>
        </tr>
        <tr>
            <td><?php echo language_text('Duration (minutes)'); ?></td>
            <td><input type="number" name="duration_minutes" class="form-control" min="1" max="660" step="1" value="30" required></td>
        </tr>
        <!-- image -->
        <tr>
            <td><?php echo language_text('Image'); ?></td>
            <td>
                <div>
                    <input type="file" name="image" accept="image/*" style="color:black;">
                    <small class="help-block"><?php echo language_text('Optional. JPEG, PNG, GIF, or WebP. Images are optimized for upload (maximum 512 KB after optimization).'); ?></small>
                    <span class="image-upload-status" aria-live="polite"></span>
                </div>
            </td>
        </tr>
        <!-- end image -->
        <tr>
            <td colspan="2">
                <button type="submit" class="btn btn-primary" name="save">
                    <span class="glyphicon glyphicon-plus"></span> <?php echo language_text('Save'); ?>
                </button>
                <a href="servicesAdmin" class="btn btn-large btn-success">
                    <i class="glyphicon glyphicon-backward"></i> &nbsp;<?php echo language_text('Back to list'); ?>
                </a>
            </td>
        </tr>
    </table>
</form>

<?php
}
?>

</div>
</div>

<?php 
$content = ob_get_clean(); 
include "viewAdmin/templates/layout.php"; 
?>