<?php
class ViewServices{

    public static function ServicesByCategory($arr) {
        foreach($arr as $value){
            self::ServiceCard($value);
        }
    }

    public static function AllServices($arr) {
        foreach($arr as $value){
            self::ServiceCard($value);
        }
    }

    private static function ServiceCard($service) {
        echo '<article class="service-card">';
        if (!empty($service['image'])) {
            echo '<img class="service-image" src="' .
                ServiceImage::dataUri($service['image']) . '" alt="' .
                htmlspecialchars(localized_service_title($service), ENT_QUOTES, 'UTF-8') . '">';
        }
        echo '<div class="service-card-content">';
        echo '<h2>' . htmlspecialchars(localized_service_title($service), ENT_QUOTES, 'UTF-8') . '</h2>';
        echo '<p>' . htmlspecialchars(localized_service_description($service), ENT_QUOTES, 'UTF-8') . '</p>';
        echo '<div class="service-card-footer"><strong class="service-price">' .
            htmlspecialchars((string)$service['price'], ENT_QUOTES, 'UTF-8') .
            ' €</strong><a class="button-link" href="service?service_id=' .
            (int)$service['service_id'] . '">' . language_text('View details') . '</a></div>';
        Controller::ReviewsCount($service['service_id']);
        echo '</div></article>';
    }

    public static function ReadService($service) {
        echo '<article class="service-detail">';
        if (!empty($service['image'])) {
            echo '<img class="service-detail-image" src="' .
                ServiceImage::dataUri($service['image']) . '" alt="' .
                htmlspecialchars(localized_service_title($service), ENT_QUOTES, 'UTF-8') . '">';
        }
        echo '<div class="service-detail-content"><h1>' .
            htmlspecialchars(localized_service_title($service), ENT_QUOTES, 'UTF-8') . '</h1>';
        echo '<p>' . htmlspecialchars(localized_service_description($service), ENT_QUOTES, 'UTF-8') . '</p>';
        echo '<p class="service-price">' . htmlspecialchars((string)$service['price'], ENT_QUOTES, 'UTF-8') . ' €</p>';
        echo '</div></article>';
    }
}