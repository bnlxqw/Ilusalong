<?php ob_start(); ?>

<div class="container" style="min-height:400px;">
<div class="col-md-11">
<h2>Edit service</h2>
<?php
if (isset($uploadError) && $uploadError !== null) {
    echo '<div class="alert alert-warning">' . htmlspecialchars($uploadError, ENT_QUOTES, 'UTF-8') . '</div>';
}
if(isset($test)){
    if($test==true)
    {
?>
        <div class="alert alert-info">
            <strong>Service updated. </strong><a href="servicesAdmin">Back to services</a>
        </div>
<?php
    }
    else if($test==false)
    {
?>
        <div class="alert alert-warning">
            <strong>Unable to update service.</strong> <a href="servicesAdmin">Back to services</a>
        </div>
<?php
    }
}
else{
?>
<form method='POST' action="serviceEditResult?service_id=<?php echo (int)$id; ?>" enctype="multipart/form-data">
    <table class='table table-bordered'>
        <tr>
            <td>Service name</td>
            <td><input type='text' name='title' class='form-control' required value="<?php echo htmlspecialchars($detail['title'], ENT_QUOTES, 'UTF-8'); ?>"></td>
        </tr>
        <tr>
            <td>Description</td>
            <td><textarea rows="5" name="text" class='form-control' required><?php echo htmlspecialchars($detail['text'], ENT_QUOTES, 'UTF-8'); ?></textarea></td>
        </tr>
        <tr>
            <td>Category</td>
            <td>
                <select name="category_id" class="form-control">
                    <?php
                    foreach($arr as $row){
                        echo '<option value="'.(int)$row['category_id'].'"';
                        if($row['category_id']==$detail['category_id']) echo ' selected';
                        echo '>'.htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8').'</option>';
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Price (€)</td>
            <td><input type="number" name="price" class="form-control" min="0" step="0.01" required value="<?php echo htmlspecialchars((string)$detail['price'], ENT_QUOTES, 'UTF-8'); ?>"></td>
        </tr>
        <!-- image-->
        <tr>
            <td>Current image</td>
            <td><div>
            <?php if (!empty($detail['image'])) echo '<img src="'.ServiceImage::dataUri($detail['image']).'" width="150" alt="">'; ?>
            </div></td>
        </tr>
        <tr>
            <td>Image</td>
            <td><div>
                <input type="file" name="image" accept="image/*" style="color:black;">
                <small class="help-block">Optional. JPEG, PNG, GIF, or WebP. Images are optimized for upload (maximum 512 KB after optimization).</small>
                <span class="image-upload-status" aria-live="polite"></span>
            </div></td>
        </tr>
        <!-- end image-->
        <tr>
            <td colspan="2">
                <button type="submit" class="btn btn-primary" name="save">
                    <span class="glyphicon glyphicon-plus"></span> Save changes
                </button>
                <a href="servicesAdmin" class="btn btn-large btn-success">
                    <i class="glyphicon glyphicon-backward"></i> &nbsp;Back to list</a>
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