CREATE TABLE IF NOT EXISTS specialists (
    specialist_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    specialty VARCHAR(120) NOT NULL,
    bio TEXT NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (specialist_id),
    KEY idx_specialists_active_name (is_active, name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE bookings
    ADD COLUMN specialist_id INT UNSIGNED NULL AFTER service_id,
    ADD KEY idx_bookings_specialist_date (specialist_id, booking_date),
    ADD CONSTRAINT fk_bookings_specialist
        FOREIGN KEY (specialist_id) REFERENCES specialists (specialist_id)
        ON DELETE SET NULL;
