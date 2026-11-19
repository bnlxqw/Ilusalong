<?php
class ViewReviews {

    public static function ReviewsForm($serviceId) {
        $flash = '';
        if (!empty($_SESSION['review_flash'])) {
            $f    = $_SESSION['review_flash'];
            unset($_SESSION['review_flash']);
            $type = isset($f['type']) && $f['type'] === 'success' ? 'notice-success' : 'notice-error';
            $flash = '<p class="notice ' . $type . '">'
                   . htmlspecialchars(language_text($f['message'] ?? ''), ENT_QUOTES, 'UTF-8')
                   . '</p>';
        }

        if (!UserAuth::getProfile()) {
            echo $flash
               . '<p class="review-login-prompt">'
               . language_text('Please log in to leave a review.')
               . ' <a href="login">' . language_text('Log in') . '</a>'
               . ' · <a href="registerForm">' . language_text('Register') . '</a>'
               . '</p>';
            return;
        }

        echo '<form class="review-form" action="insertreview" method="post">'
           . $flash
           . '<input type="hidden" name="service_id" value="' . (int)$serviceId . '">'
           . '<input type="hidden" name="csrf_token" value="'
           .     htmlspecialchars(Reviews::getCsrfToken(), ENT_QUOTES, 'UTF-8') . '">'
           . '<label for="review">' . language_text('Your review') . '</label>'
           . '<textarea id="review" name="review" rows="4" maxlength="2000" required></textarea>'
           . '<button class="button-link" type="submit">' . language_text('Submit review') . '</button>'
           . '</form>';
    }

    public static function ReviewsByService($arr) {
        echo '<section class="reviews" id="reviews"><h2>' . language_text('Reviews') . '</h2>';
        if ($arr) {
            foreach ($arr as $value) {
                $author = !empty($value['author_name'])
                    ? $value['author_name']
                    : language_text('Former guest');
                echo '<article class="review"><p class="review-author">'
                   . htmlspecialchars($author, ENT_QUOTES, 'UTF-8')
                   . '</p><p>'
                   . htmlspecialchars($value['text'], ENT_QUOTES, 'UTF-8')
                   . '</p><time>'
                   . htmlspecialchars($value['date'], ENT_QUOTES, 'UTF-8')
                   . '</time></article>';
            }
        } else {
            echo '<p class="muted">' . language_text('No reviews yet. Be the first!') . '</p>';
        }
        echo '</section>';
    }

    public static function ReviewsCountWithAnchor($value) {
        if (!empty($value['count'])) {
            echo '<a class="review-count" href="#reviews">'
               . language_text('Reviews') . ' (' . (int)$value['count'] . ')</a>';
        }
    }

    public static function ReviewsCount($value) {
        if (!empty($value['count'])) {
            echo '<span class="review-count">'
               . language_text('Reviews') . ' (' . (int)$value['count'] . ')</span>';
        }
    }
}
