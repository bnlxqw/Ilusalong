<?php ob_start(); ?>
<section class="admin-panel">
    <h1><?php echo language_text('Service categories'); ?></h1>
    <?php if (isset($added) && $added) { ?>
        <div class="alert alert-info"><?php echo language_text('Category added.'); ?></div>
    <?php } elseif (isset($error) && $error !== null) { ?>
        <div class="alert alert-warning"><?php echo htmlspecialchars(language_text($error), ENT_QUOTES, 'UTF-8'); ?></div>
    <?php } elseif (isset($added) && !$added) { ?>
        <div class="alert alert-warning"><?php echo language_text('Unable to add category.'); ?></div>
    <?php } ?>
    <?php if (isset($updated) && $updated) { ?>
        <div class="alert alert-info"><?php echo language_text('Category updated.'); ?></div>
    <?php } elseif (isset($updated) && !$updated) { ?>
        <div class="alert alert-warning"><?php echo htmlspecialchars($error !== null ? language_text($error) : language_text('Unable to update category.'), ENT_QUOTES, 'UTF-8'); ?></div>
    <?php } ?>
    <?php if (isset($deleted) && $deleted) { ?>
        <div class="alert alert-info"><?php echo language_text('Category deleted.'); ?></div>
    <?php } elseif (isset($deleted) && !$deleted) { ?>
        <div class="alert alert-warning"><?php echo htmlspecialchars($error !== null ? language_text($error) : language_text('Unable to delete category.'), ENT_QUOTES, 'UTF-8'); ?></div>
    <?php } ?>
    <h2><?php echo language_text('Add category'); ?></h2>
    <form method="POST" action="categoryAddResult" class="category-add-form">
        <div class="form-group">
            <label for="category-name"><?php echo language_text('Category name'); ?></label>
            <input id="category-name" type="text" name="name" class="form-control" maxlength="255" required>
        </div>
        <button type="submit" class="btn btn-primary" name="saveCategory"><?php echo language_text('Add category'); ?></button>
    </form>
    <?php if (!$arr) { ?>
        <p class="admin-empty"><?php echo language_text('No service categories have been added yet.'); ?></p>
    <?php } else { ?>
        <div class="table-responsive">
            <table class="table table-bordered admin-table">
                <thead>
                    <tr><th><?php echo language_text('Category ID'); ?></th><th><?php echo language_text('Name'); ?></th><th><?php echo language_text('Actions'); ?></th></tr>
                </thead>
                <tbody>
                    <?php foreach ($arr as $category) { ?>
                        <tr>
                            <td><?php echo (int)$category['category_id']; ?></td>
                            <td>
                                <form method="POST" action="categoryEditResult" class="category-inline-form">
                                    <input type="hidden" name="category_id" value="<?php echo (int)$category['category_id']; ?>">
                                    <input type="text" name="name" class="form-control" maxlength="255" required value="<?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?>" aria-label="<?php echo htmlspecialchars(language_text('Category name'), ENT_QUOTES, 'UTF-8'); ?>">
                                    <button type="submit" class="btn btn-primary" name="saveCategoryEdit"><?php echo language_text('Save changes'); ?></button>
                                </form>
                                <small class="help-block"><?php echo language_text('Displayed on the website:'); ?> <?php echo htmlspecialchars(localized_category_name($category), ENT_QUOTES, 'UTF-8'); ?></small>
                            </td>
                            <td>
                                <form method="POST" action="categoryDeleteResult" class="category-delete-form" onsubmit="return confirm('<?php echo htmlspecialchars(language_text('Delete this category?'), ENT_QUOTES, 'UTF-8'); ?>');">
                                    <input type="hidden" name="category_id" value="<?php echo (int)$category['category_id']; ?>">
                                    <button type="submit" class="btn btn-primary" name="deleteCategory"><?php echo language_text('Delete'); ?></button>
                                </form>
                            </td>
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
