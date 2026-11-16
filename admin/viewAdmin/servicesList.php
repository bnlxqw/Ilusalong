<?php ob_start() ?>

<h2><?php echo language_text('Salon services'); ?></h2>

<div class="container" style="min-height:400px;">
    <div style="margin:20px;">
        <a class="btn btn-primary" href="serviceAdd" role="button"><?php echo language_text('Add service'); ?></a>
    </div>
    <div class="col-md-11 services-table-wrap">
        <table class='table table-bordered'>
            <thead>
                <tr>
                    <th class="service-id-heading"><?php echo language_text('ID'); ?></th>
                    <th class="service-name-heading"><?php echo language_text('Service'); ?></th>
                    <th class="service-actions-heading"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach($arr as $row){
                    echo '<tr>';

                    echo '<td data-label="'.htmlspecialchars(language_text('ID'), ENT_QUOTES, 'UTF-8').'">'.(int)$row['service_id'].'</td>';
                    echo '<td data-label="'.htmlspecialchars(language_text('Service'), ENT_QUOTES, 'UTF-8').'"><b>'.language_text('Name:').'</b> '.htmlspecialchars(localized_service_title($row), ENT_QUOTES, 'UTF-8').'<br>';
                    echo '<b>'.language_text('Description').':</b> '.htmlspecialchars(localized_service_description($row), ENT_QUOTES, 'UTF-8').'<br>';
                    echo '<b>'.language_text('Category:').'</b> <i>'.htmlspecialchars(localized_category_name($row), ENT_QUOTES, 'UTF-8').'</i>';
                    echo '<br><b>'.language_text('Price:').'</b> <i>'.htmlspecialchars((string)$row['price'], ENT_QUOTES, 'UTF-8').' €</i>';
                    echo '</td>';
                    echo '<td data-label="'.htmlspecialchars(language_text('Actions'), ENT_QUOTES, 'UTF-8').'">';
                    echo '<a href="serviceEdit?service_id='.(int)$row['service_id'].'" aria-label="'.htmlspecialchars(language_text('Edit'), ENT_QUOTES, 'UTF-8').'" title="'.htmlspecialchars(language_text('Edit'), ENT_QUOTES, 'UTF-8').'"><span class="action-label">'.language_text('Edit').' </span><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>';
                    echo '<a href="serviceDel?service_id='.(int)$row['service_id'].'" aria-label="'.htmlspecialchars(language_text('Delete'), ENT_QUOTES, 'UTF-8').'" title="'.htmlspecialchars(language_text('Delete'), ENT_QUOTES, 'UTF-8').'"><span class="action-label">'.language_text('Delete').' </span><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>';
                    echo '</td>';

                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php $content = ob_get_clean(); ?>

<?php include "viewAdmin/templates/layout.php"; ?>