ALTER TABLE services
    ADD COLUMN duration_minutes SMALLINT UNSIGNED NOT NULL DEFAULT 30 AFTER price;

ALTER TABLE bookings
    ADD COLUMN duration_minutes SMALLINT UNSIGNED NOT NULL DEFAULT 30 AFTER service_id;

UPDATE bookings
INNER JOIN services ON services.service_id = bookings.service_id
SET bookings.duration_minutes = services.duration_minutes;
