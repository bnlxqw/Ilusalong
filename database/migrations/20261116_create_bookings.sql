CREATE TABLE IF NOT EXISTS bookings (
    booking_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT NULL,
    session_token_hash CHAR(64) NOT NULL,
    service_id INT NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(254) NOT NULL,
    customer_phone VARCHAR(32) NOT NULL,
    booking_date DATETIME NOT NULL,
    status ENUM('active', 'cancelled') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (booking_id),
    KEY idx_bookings_session_date (session_token_hash, booking_date),
    KEY idx_bookings_service_date (service_id, booking_date),
    KEY idx_bookings_user_date (user_id, booking_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
