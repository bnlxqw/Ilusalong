<?php
echo "<li class='submenuunit'><a href='services'>All services</a></li>";
foreach($arr as $value){
    echo "<li class='submenuunit'>
    <a href='category?category_id=".(int)$value['category_id']."'>".htmlspecialchars($value['name'], ENT_QUOTES, 'UTF-8')."</a>
    </li>
    ";
}
?>