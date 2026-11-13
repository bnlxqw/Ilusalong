<?php ob_start() ?>

<h2>Salon services</h2>

<div class="container" style="min-height:400px;">
    <div style="margin:20px;">
        <a class="btn btn-primary" href="serviceAdd" role="button">Add service</a>
    </div>
    <div class="col-md-11">
        <table class='table table-bordered table-responsive'>
            <tr>
                <th width="10%">ID</th>
                <th width="70%">Service</th>
                <th width="20%"></th>
            </tr>
            
            <?php
            foreach($arr as $row){
                echo '<tr>';
                
                echo '<td>'.(int)$row['service_id'].'</td>     ';
                echo '<td><b>Name:</b> '.htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8').'<br>';
                echo '<b>Category: </b><i>'.htmlspecialchars((string)$row['name'], ENT_QUOTES, 'UTF-8').'</i>';
                echo '<br><b>Price: </b><i>'.htmlspecialchars((string)$row['price'], ENT_QUOTES, 'UTF-8').' €</i>';
                echo '</td>';
                echo '<td>';
                echo '<a href="serviceEdit?service_id='.(int)$row['service_id'].'">Edit <span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>';
                echo '<a href="serviceDel?service_id='.(int)$row['service_id'].'">Delete <span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>';
                echo '</td>     ';
                
                echo '</tr>';
            }
            ?>
        </table>
    </div>
</div>
<?php $content = ob_get_clean(); ?>

<?php include "viewAdmin/templates/layout.php"; ?>