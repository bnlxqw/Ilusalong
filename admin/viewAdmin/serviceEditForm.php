<?php ob_start(); ?>

<div class="container" style="min-height:400px;">
<div class="col-md-11">
<h2><?php echo language_text('Edit service'); ?></h2>
<?php
if (isset($uploadError) && $uploadError !== null) {
    echo '<div class="alert alert-warning">' . htmlspecialchars(language_text($uploadError), ENT_QUOTES, 'UTF-8') . '</div>';
}
if(isset($test)){
    if($test==true)
    {
?>
        <div class="alert alert-info">
            <strong><?php echo language_text('Service updated.'); ?> </strong><a href="servicesAdmin"><?php echo language_text('Back to services'); ?></a>
        </div>
<?php
    }
    else if($test==false)
    {
?>
        <div class="alert alert-warning">
            <strong><?php echo language_text('Unable to update service.'); ?></strong> <a href="servicesAdmin"><?php echo language_text('Back to services'); ?></a>
        </div>
<?php
    }
}
else{
?>
<form method='POST' action="serviceEditResult?service_id=<?php echo (int)$id; ?>" enctype="multipart/form-data">
    <table class='table table-bordered'>
        <tr>
            <td><?php echo language_text('Service name'); ?></td>
            <td>
                <input type='text' name='title' class='form-control' required value="<?php echo htmlspecialchars($detail['title'], ENT_QUOTES, 'UTF-8'); ?>">
                <small class="help-block"><?php echo language_text('Displayed on the website:'); ?> <?php echo htmlspecialchars(localized_service_title($detail), ENT_QUOTES, 'UTF-8'); ?></small>
            </td>
        </tr>
        <tr>
            <td><?php echo language_text('Description'); ?></td>
            <td>
                <textarea rows="5" name="text" class='form-control' required><?php echo htmlspecialchars($detail['text'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                <small class="help-block"><?php echo language_text('Displayed on the website:'); ?> <?php echo htmlspecialchars(localized_service_description($detail), ENT_QUOTES, 'UTF-8'); ?></small>
            </td>
        </tr>
        <tr>
            <td><?php echo language_text('Category'); ?></td>
            <td>
                <select name="category_id" class="form-control">
                    <?php
                    foreach($arr as $row){
                        echo '<option value="'.(int)$row['category_id'].'"';
                        if($row['category_id']==$detail['category_id']) echo ' selected';
                        echo '>'.htmlspecialchars(localized_category_name($row), ENT_QUOTES, 'UTF-8').'</option>';
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><?php echo language_text('Price (€)'); ?></td>
            <td><input type="number" name="price" class="form-control" min="0" step="0.01" required value="<?php echo htmlspecialchars((string)$detail['price'], ENT_QUOTES, 'UTF-8'); ?>"></td>
        </tr>
        <!-- image-->
        <tr>
            <td><?php echo language_text('Current image'); ?></td>
            <td><div>
            <?php if (!empty($detail['image'])) echo '<img src="'.ServiceImage::dataUri($detail['image']).'" width="150" alt="">'; ?>
            </div></td>
        </tr>
        <tr>
            <td><?php echo language_text('Image'); ?></td>
            <td><div>
                <input type="file" name="image" accept="image/*" style="color:black;">
                <small class="help-block"><?php echo language_text('Optional. JPEG, PNG, GIF, or WebP. Images are optimized for upload (maximum 512 KB after optimization).'); ?></small>
                <span class="image-upload-status" aria-live="polite"></span>
            </div></td>
        </tr>
        <!-- end image-->
        <tr>
            <td colspan="2">
                <button type="submit" class="btn btn-primary" name="save">
                    <span class="glyphicon glyphicon-plus"></span> <?php echo language_text('Save changes'); ?>
                </button>
                <a href="servicesAdmin" class="btn btn-large btn-success">
                    <i class="glyphicon glyphicon-backward"></i> &nbsp;<?php echo language_text('Back to list'); ?></a>
            </td>
        </tr>
    </table>
</form>
<?php
}
?>
</div>
</div>
<?php $content = ob_get_clean(); ?>
<?php include "viewAdmin/templates/layout.php"; ?>