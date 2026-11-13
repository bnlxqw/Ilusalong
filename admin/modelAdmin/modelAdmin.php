<?php
class modelAdmin{
    public static function isAdminAuthenticated() {
        return isset($_SESSION['sessionId'], $_SESSION['user_id'], $_SESSION['status'])
            && $_SESSION['status'] === 'admin'
            && (int)$_SESSION['user_id'] > 0;
    }

    public static function userAuthentication() {
        if (self::isAdminAuthenticated()) {
            return true;
        }

        if (!isset($_POST['btnLogin'], $_POST['email'], $_POST['password'])
            || $_POST['email'] === '' || $_POST['password'] === '') {
            return false;
        }

        $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
        if ($email === false) {
            return false;
        }

        $sql = 'SELECT user_id, username, email, password, pass, status
                FROM users WHERE LOWER(email) = LOWER(:email) LIMIT 1';
        $db = new Database();
        $user = $db->getOne($sql, array(':email' => $email));

        if (!$user || $user['status'] !== 'admin') {
            return false;
        }

        $submittedPassword = $_POST['password'];
        $passwordIsHashed = password_get_info($user['password'])['algo'] !== null;
        $passwordMatches = $passwordIsHashed
            ? password_verify($submittedPassword, $user['password'])
            : hash_equals((string)$user['password'], $submittedPassword);

        if (!$passwordMatches && !empty($user['pass'])) {
            $legacyPasswordIsHashed = password_get_info($user['pass'])['algo'] !== null;
            $passwordMatches = $legacyPasswordIsHashed
                ? password_verify($submittedPassword, $user['pass'])
                : hash_equals((string)$user['pass'], $submittedPassword);
        }

        if (!$passwordMatches) {
            return false;
        }

        if (!$passwordIsHashed
            || password_needs_rehash($user['password'], PASSWORD_DEFAULT)
            || !empty($user['pass'])) {
            $update = 'UPDATE users SET password = :password, pass = :empty_pass WHERE user_id = :user_id';
            $db->executeRun($update, array(
                ':password' => password_hash($submittedPassword, PASSWORD_DEFAULT),
                ':empty_pass' => '',
                ':user_id' => (int)$user['user_id']
            ));
        }

        session_regenerate_id(true);
        $_SESSION['sessionId'] = session_id();
        $_SESSION['user_id'] = (int)$user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['status'] = $user['status'];
        return true;
    }

    public static function userLogout() {
        unset($_SESSION['sessionId']);
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        unset($_SESSION['status']);
        session_regenerate_id(true);
    }
}
?>