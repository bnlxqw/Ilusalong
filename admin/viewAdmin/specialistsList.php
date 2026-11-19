<?php ob_start(); ?>
<section class="admin-panel">
    <h1><?php echo language_text('Specialists'); ?></h1>
    <?php if (isset($saved) && $saved) { ?>
        <div class="alert alert-info"><?php echo language_text('Specialist saved.'); ?></div>
    <?php } elseif (isset($deleted) && $deleted) { ?>
        <div class="alert alert-info"><?php echo language_text('Specialist deleted.'); ?></div>
    <?php } elseif (isset($error) && $error !== null) { ?>
        <div class="alert alert-warning"><?php echo htmlspecialchars(language_text($error), ENT_QUOTES, 'UTF-8'); ?></div>
    <?php } elseif (isset($saved) && !$saved) { ?>
        <div class="alert alert-warning"><?php echo language_text('Unable to save specialist.'); ?></div>
    <?php } elseif (isset($deleted) && !$deleted) { ?>
        <div class="alert alert-warning"><?php echo language_text('Unable to delete specialist.'); ?></div>
    <?php } ?>

    <h2><?php echo language_text('Add specialist'); ?></h2>
    <form method="POST" action="specialistAddResult" class="specialist-admin-form">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(modelAdmin::getCsrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
        <label for="specialist-name"><?php echo language_text('Name'); ?></label>
        <input id="specialist-name" class="form-control" type="text" name="name" maxlength="100" required>
        <label for="specialist-specialty"><?php echo language_text('Specialty'); ?></label>
        <input id="specialist-specialty" class="form-control" type="text" name="specialty" maxlength="120" required>
        <label for="specialist-bio"><?php echo language_text('Biography'); ?></label>
        <textarea id="specialist-bio" class="form-control" name="bio" maxlength="2000" rows="4"></textarea>
        <fieldset class="specialist-service-options">
            <legend><?php echo language_text('Services provided'); ?></legend>
            <?php foreach ($services as $service) { ?>
                <label class="specialist-service-option">
                    <input type="checkbox" name="service_ids[]" value="<?php echo (int)$service['service_id']; ?>">
                    <span><?php echo htmlspecialchars(localized_service_title($service), ENT_QUOTES, 'UTF-8'); ?></span>
                </label>
            <?php } ?>
        </fieldset>
        <button class="btn btn-primary" type="submit" name="addSpecialist"><?php echo language_text('Add specialist'); ?></button>
    </form>

    <?php if (!$arr) { ?>
        <p class="admin-empty"><?php echo language_text('No specialists have been added yet.'); ?></p>
    <?php } else { ?>
        <div class="table-responsive">
            <table class="table table-bordered admin-table specialist-admin-table">
                <thead><tr>
                    <th><?php echo language_text('Name'); ?></th>
                    <th><?php echo language_text('Specialty'); ?></th>
                    <th><?php echo language_text('Biography'); ?></th>
                    <th><?php echo language_text('Services provided'); ?></th>
                    <th><?php echo language_text('Actions'); ?></th>
                </tr></thead>
                <tbody>
                    <?php foreach ($arr as $specialist) { ?>
                        <?php $assignedServiceIds = isset($serviceAssignments[(int)$specialist['specialist_id']])
                            ? $serviceAssignments[(int)$specialist['specialist_id']]
                            : array(); ?>
                        <tr>
                            <td data-label="<?php echo htmlspecialchars(language_text('Name'), ENT_QUOTES, 'UTF-8'); ?>">
                                <form id="specialist-edit-<?php echo (int)$specialist['specialist_id']; ?>" method="POST" action="specialistEditResult" class="specialist-edit-form">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(modelAdmin::getCsrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
                                    <input type="hidden" name="specialist_id" value="<?php echo (int)$specialist['specialist_id']; ?>">
                                </form>
                                <input form="specialist-edit-<?php echo (int)$specialist['specialist_id']; ?>" class="form-control" type="text" name="name" maxlength="100" required value="<?php echo htmlspecialchars($specialist['name'], ENT_QUOTES, 'UTF-8'); ?>" aria-label="<?php echo htmlspecialchars(language_text('Name'), ENT_QUOTES, 'UTF-8'); ?>">
                            </td>
                            <td data-label="<?php echo htmlspecialchars(language_text('Specialty'), ENT_QUOTES, 'UTF-8'); ?>">
                                <input form="specialist-edit-<?php echo (int)$specialist['specialist_id']; ?>" class="form-control" type="text" name="specialty" maxlength="120" required value="<?php echo htmlspecialchars($specialist['specialty'], ENT_QUOTES, 'UTF-8'); ?>" aria-label="<?php echo htmlspecialchars(language_text('Specialty'), ENT_QUOTES, 'UTF-8'); ?>">
                            </td>
                            <td data-label="<?php echo htmlspecialchars(language_text('Biography'), ENT_QUOTES, 'UTF-8'); ?>">
                                <textarea form="specialist-edit-<?php echo (int)$specialist['specialist_id']; ?>" class="form-control" name="bio" maxlength="2000" rows="3" aria-label="<?php echo htmlspecialchars(language_text('Biography'), ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($specialist['bio'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </td>
                            <td data-label="<?php echo htmlspecialchars(language_text('Services provided'), ENT_QUOTES, 'UTF-8'); ?>">
                                <fieldset class="specialist-service-options">
                                    <legend class="sr-only"><?php echo language_text('Services provided'); ?></legend>
                                    <?php foreach ($services as $service) { ?>
                                        <label class="specialist-service-option">
                                            <input form="specialist-edit-<?php echo (int)$specialist['specialist_id']; ?>" type="checkbox" name="service_ids[]" value="<?php echo (int)$service['service_id']; ?>"<?php echo in_array((int)$service['service_id'], $assignedServiceIds, true) ? ' checked' : ''; ?>>
                                            <span><?php echo htmlspecialchars(localized_service_title($service), ENT_QUOTES, 'UTF-8'); ?></span>
                                        </label>
                                    <?php } ?>
                                </fieldset>
                            </td>
                            <td data-label="<?php echo htmlspecialchars(language_text('Actions'), ENT_QUOTES, 'UTF-8'); ?>">
                                <button form="specialist-edit-<?php echo (int)$specialist['specialist_id']; ?>" class="btn btn-primary specialist-save-button" type="submit" name="saveSpecialist" aria-label="<?php echo htmlspecialchars(language_text('Save changes'), ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars(language_text('Save changes'), ENT_QUOTES, 'UTF-8'); ?>"><span class="action-label"><?php echo language_text('Save changes'); ?></span><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span></button>
                                <form method="POST" action="specialistDeleteResult" class="specialist-delete-form" onsubmit="return confirm('<?php echo htmlspecialchars(language_text('Delete this specialist?'), ENT_QUOTES, 'UTF-8'); ?>');">
                                    <input type="hidden" name="specialist_id" value="<?php echo (int)$specialist['specialist_id']; ?>">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(modelAdmin::getCsrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
                                    <button class="btn btn-primary specialist-delete-button" type="submit" name="deleteSpecialist" aria-label="<?php echo htmlspecialchars(language_text('Delete'), ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars(language_text('Delete'), ENT_QUOTES, 'UTF-8'); ?>"><span class="action-label"><?php echo language_text('Delete'); ?></span><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
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
