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

    public static function getActiveForService($serviceId) {
        $db = new Database();
        return $db->getAll(
            'SELECT specialists.specialist_id, specialists.name, specialists.specialty, specialists.bio
             FROM specialists
             INNER JOIN specialist_services
               ON specialist_services.specialist_id = specialists.specialist_id
             WHERE specialists.is_active = 1
               AND specialist_services.service_id = :service_id
             ORDER BY specialists.name ASC',
            array(':service_id' => (int)$serviceId)
        );
    }

    public static function getServicesForSpecialists($specialists) {
        if (!$specialists) {
            return array();
        }

        $placeholders = array();
        $params = array();
        foreach ($specialists as $index => $specialist) {
            $placeholder = ':specialist_' . $index;
            $placeholders[] = $placeholder;
            $params[$placeholder] = (int)$specialist['specialist_id'];
        }

        $db = new Database();
        $rows = $db->getAll(
            'SELECT specialist_services.specialist_id, services.service_id, services.title, services.price,
                    services.duration_minutes
             FROM specialist_services
             INNER JOIN services ON services.service_id = specialist_services.service_id
             WHERE specialist_services.specialist_id IN (' . implode(', ', $placeholders) . ')
             ORDER BY services.title ASC',
            $params
        );

        $servicesBySpecialist = array();
        foreach ($rows as $row) {
            $servicesBySpecialist[(int)$row['specialist_id']][] = $row;
        }
        return $servicesBySpecialist;
    }

    public static function offersService($specialistId, $serviceId) {
        $db = new Database();
        return (bool)$db->getOne(
            'SELECT specialists.specialist_id
             FROM specialists
             INNER JOIN specialist_services
               ON specialist_services.specialist_id = specialists.specialist_id
             WHERE specialists.specialist_id = :specialist_id
               AND specialists.is_active = 1
               AND specialist_services.service_id = :service_id',
            array(
                ':specialist_id' => (int)$specialistId,
                ':service_id' => (int)$serviceId
            )
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
