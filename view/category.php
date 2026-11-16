<?php
echo "<li class='submenuunit'><a href='services'>" . language_text('All services') . "</a></li>";
foreach($arr as $value){
    echo "<li class='submenuunit'>
    <a href='category?category_id=".(int)$value['category_id']."'>".htmlspecialchars(localized_category_name($value), ENT_QUOTES, 'UTF-8')."</a>
    </li>
    ";
}
?>