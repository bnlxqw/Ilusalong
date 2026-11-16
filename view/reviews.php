<?php
class ViewReviews{
    public static function ReviewsForm($serviceId) {
    echo '<form class="review-form" action="insertreview" method="post">
    <input type="hidden" name="service_id" value="' . (int)$serviceId . '">
        <label for="review">' . language_text('Your review') . '</label>
        <textarea id="review" name="review" rows="4" required></textarea>
    <button class="button-link" type="submit">' . language_text('Submit review') . '</button></form>';
}

public static function ReviewsByService($arr)
{
    echo '<section class="reviews" id="reviews"><h2>' . language_text('Reviews') . '</h2>';
    if ($arr) {
    foreach($arr as $value) {
        echo '<article class="review"><p>' .
            htmlspecialchars($value['text'], ENT_QUOTES, 'UTF-8') .
            '</p><time>' . htmlspecialchars($value['date'], ENT_QUOTES, 'UTF-8') . '</time></article>';
    }
    } else {
        echo '<p class="muted">' . language_text('No reviews yet. Be the first!') . '</p>';
    }
    echo '</section>';
}

public static function ReviewsCountWithAnchor($value) {
    if (!empty($value['count'])) {
        echo '<a class="review-count" href="#reviews">' . language_text('Reviews') . ' (' . (int)$value['count'] . ')</a>';
    }
}

public static function ReviewsCount($value) {
    if (!empty($value['count'])) {
        echo '<span class="review-count">' . language_text('Reviews') . ' (' . (int)$value['count'] . ')</span>';
    }
}
}