CREATE TABLE IF NOT EXISTS specialist_services (
    specialist_id INT UNSIGNED NOT NULL,
    service_id INT NOT NULL,
    PRIMARY KEY (specialist_id, service_id),
    KEY idx_specialist_services_service (service_id),
    CONSTRAINT fk_specialist_services_specialist
        FOREIGN KEY (specialist_id) REFERENCES specialists (specialist_id)
        ON DELETE CASCADE,
    CONSTRAINT fk_specialist_services_service
        FOREIGN KEY (service_id) REFERENCES services (service_id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
