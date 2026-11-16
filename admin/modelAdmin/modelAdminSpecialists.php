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

    public static function addSpecialist(&$error = null) {
        if (!isset($_POST['addSpecialist'], $_POST['name'], $_POST['specialty'], $_POST['bio'])) {
            return false;
        }

        $data = self::validateForm($error);
        if ($data === false) {
            return false;
        }

        $db = new Database();
        return $db->executeRun(
            'INSERT INTO specialists (name, specialty, bio, is_active)
             VALUES (:name, :specialty, :bio, 1)',
            $data
        ) > 0;
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
        if ($existing['name'] === $data[':name']
            && $existing['specialty'] === $data[':specialty']
            && $existing['bio'] === $data[':bio']) {
            return true;
        }

        $data[':specialist_id'] = $specialistId;
        return $db->executeRun(
            'UPDATE specialists
             SET name = :name, specialty = :specialty, bio = :bio
             WHERE specialist_id = :specialist_id',
            $data
        ) > 0;
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

        return array(':name' => $name, ':specialty' => $specialty, ':bio' => $bio);
    }
}
?>
