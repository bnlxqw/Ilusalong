<?php
class modelAdminSpecialists {
    public static function getSpecialistList() {
        $db = new Database();
        return $db->getAll(
            'SELECT specialist_id, name, specialty, bio, is_active
             FROM specialists
             ORDER BY name ASC'
        );
    }

    public static function getServiceOptions() {
        $db = new Database();
        return $db->getAll(
            'SELECT service_id, title FROM services ORDER BY title ASC'
        );
    }

    public static function getSpecialistServiceAssignments() {
        $db = new Database();
        $rows = $db->getAll(
            'SELECT specialist_id, service_id FROM specialist_services'
        );
        $assignments = array();
        foreach ($rows as $row) {
            $assignments[(int)$row['specialist_id']][] = (int)$row['service_id'];
        }
        return $assignments;
    }

    public static function addSpecialist(&$error = null) {
        if (!isset($_POST['addSpecialist'], $_POST['name'], $_POST['specialty'], $_POST['bio'])) {
            return false;
        }

        $data = self::validateForm($error);
        if ($data === false) {
            return false;
        }

        $serviceIds = $data['service_ids'];
        unset($data['service_ids']);
        $db = new Database();
        if (!$db->beginTransaction()) {
            $error = 'Unable to save specialist.';
            return false;
        }

        try {
            if ($db->executeRun(
                'INSERT INTO specialists (name, specialty, bio, is_active)
                 VALUES (:name, :specialty, :bio, 1)',
                $data
            ) < 1) {
                $error = 'Unable to save specialist.';
                return false;
            }

            $specialistId = (int)$db->lastInsertId();
            if ($specialistId < 1 || !self::replaceServiceAssignments($db, $specialistId, $serviceIds)) {
                $error = 'Unable to save specialist.';
                return false;
            }
            if (!$db->commit()) {
                $error = 'Unable to save specialist.';
                return false;
            }
            return true;
        } finally {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
        }
    }

    public static function editSpecialist(&$error = null) {
        if (!isset($_POST['saveSpecialist'], $_POST['specialist_id'], $_POST['name'], $_POST['specialty'], $_POST['bio'])) {
            return false;
        }

        $specialistId = filter_var($_POST['specialist_id'], FILTER_VALIDATE_INT);
        if ($specialistId === false || $specialistId < 1) {
            $error = 'Specialist not found.';
            return false;
        }

        $data = self::validateForm($error);
        if ($data === false) {
            return false;
        }

        $db = new Database();
        $existing = $db->getOne(
            'SELECT name, specialty, bio FROM specialists WHERE specialist_id = :specialist_id',
            array(':specialist_id' => $specialistId)
        );
        if (!$existing) {
            $error = 'Specialist not found.';
            return false;
        }

        $serviceIds = $data['service_ids'];
        unset($data['service_ids']);
        $data[':specialist_id'] = $specialistId;
        if (!$db->beginTransaction()) {
            $error = 'Unable to save specialist.';
            return false;
        }

        try {
            $db->executeRun(
                'UPDATE specialists
                 SET name = :name, specialty = :specialty, bio = :bio
                 WHERE specialist_id = :specialist_id',
                $data
            );
            if (!self::replaceServiceAssignments($db, $specialistId, $serviceIds)) {
                $error = 'Unable to save specialist.';
                return false;
            }
            if (!$db->commit()) {
                $error = 'Unable to save specialist.';
                return false;
            }
            return true;
        } finally {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
        }
    }

    public static function deleteSpecialist(&$error = null) {
        if (!isset($_POST['deleteSpecialist'], $_POST['specialist_id'])) {
            return false;
        }

        $specialistId = filter_var($_POST['specialist_id'], FILTER_VALIDATE_INT);
        if ($specialistId === false || $specialistId < 1) {
            $error = 'Specialist not found.';
            return false;
        }

        $db = new Database();
        return $db->executeRun(
            'DELETE FROM specialists WHERE specialist_id = :specialist_id',
            array(':specialist_id' => $specialistId)
        ) > 0;
    }

    private static function validateForm(&$error) {
        $name = trim($_POST['name']);
        $specialty = trim($_POST['specialty']);
        $bio = trim($_POST['bio']);

        if ($name === '' || !preg_match('/^.{1,100}$/us', $name)) {
            $error = 'Specialist name is required and must be 100 characters or fewer.';
            return false;
        }
        if ($specialty === '' || !preg_match('/^.{1,120}$/us', $specialty)) {
            $error = 'Specialty is required and must be 120 characters or fewer.';
            return false;
        }
        if (!preg_match('/^.{0,2000}$/us', $bio)) {
            $error = 'Biography must be 2000 characters or fewer.';
            return false;
        }

        $serviceIds = isset($_POST['service_ids']) && is_array($_POST['service_ids'])
            ? $_POST['service_ids']
            : array();
        $uniqueServiceIds = array();
        foreach ($serviceIds as $serviceId) {
            if (!is_scalar($serviceId)) {
                $error = 'Select valid services.';
                return false;
            }
            $serviceId = filter_var($serviceId, FILTER_VALIDATE_INT);
            if ($serviceId === false || $serviceId < 1) {
                $error = 'Select valid services.';
                return false;
            }
            $uniqueServiceIds[(int)$serviceId] = (int)$serviceId;
        }
        if (!$uniqueServiceIds) {
            $error = 'Select at least one service.';
            return false;
        }

        $placeholders = array();
        $params = array();
        foreach (array_values($uniqueServiceIds) as $index => $serviceId) {
            $placeholder = ':service_' . $index;
            $placeholders[] = $placeholder;
            $params[$placeholder] = $serviceId;
        }
        $db = new Database();
        $validServices = $db->getAll(
            'SELECT service_id FROM services WHERE service_id IN (' . implode(', ', $placeholders) . ')',
            $params
        );
        if (count($validServices) !== count($uniqueServiceIds)) {
            $error = 'Select valid services.';
            return false;
        }

        return array(
            ':name' => $name,
            ':specialty' => $specialty,
            ':bio' => $bio,
            'service_ids' => array_values($uniqueServiceIds)
        );
    }

    private static function replaceServiceAssignments($db, $specialistId, $serviceIds) {
        $db->executeRun(
            'DELETE FROM specialist_services WHERE specialist_id = :specialist_id',
            array(':specialist_id' => $specialistId)
        );
        foreach ($serviceIds as $serviceId) {
            if ($db->executeRun(
                'INSERT INTO specialist_services (specialist_id, service_id)
                 VALUES (:specialist_id, :service_id)',
                array(
                    ':specialist_id' => $specialistId,
                    ':service_id' => $serviceId
                )
            ) < 1) {
                return false;
            }
        }
        return true;
    }
}
?>
