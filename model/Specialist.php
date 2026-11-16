<?php
class Specialist {
    public static function getAllActive() {
        $db = new Database();
        return $db->getAll(
            'SELECT specialist_id, name, specialty, bio
             FROM specialists
             WHERE is_active = 1
             ORDER BY name ASC'
        );
    }

    public static function getAll() {
        $db = new Database();
        return $db->getAll(
            'SELECT specialist_id, name, specialty, bio, is_active
             FROM specialists
             ORDER BY name ASC'
        );
    }

    public static function getSpecialistById($specialistId) {
        $db = new Database();
        return $db->getOne(
            'SELECT specialist_id FROM specialists
             WHERE specialist_id = :specialist_id AND is_active = 1',
            array(':specialist_id' => (int)$specialistId)
        );
    }
}
?>
