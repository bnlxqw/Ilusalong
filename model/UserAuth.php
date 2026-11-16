<?php
class UserAuth {
    public static function isAuthenticated() {
        return isset($_SESSION['customer_user_id'])
            && (int)$_SESSION['customer_user_id'] > 0;
    }

    public static function getCsrfToken() {
        if (!isset($_SESSION['customer_csrf_token'])) {
            $_SESSION['customer_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['customer_csrf_token'];
    }

    public static function login(&$error = null) {
        if (!isset($_POST['email'], $_POST['password'], $_POST['csrf_token'])
            || !isset($_SESSION['customer_csrf_token'])
            || !hash_equals($_SESSION['customer_csrf_token'], (string)$_POST['csrf_token'])) {
            $error = 'Your session expired. Please try again.';
            return false;
        }

        $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
        if ($email === false || strlen($email) > 50 || $_POST['password'] === '') {
            $error = 'Invalid email or password.';
            return false;
        }

        $db = new Database();
        $user = $db->getOne(
            "SELECT user_id, username, email, password, pass
             FROM users
             WHERE LOWER(email) = LOWER(:email) AND status = 'user'
             LIMIT 1",
            array(':email' => $email)
        );
        if (!$user || !self::passwordMatches($_POST['password'], $user)) {
            $error = 'Invalid email or password.';
            return false;
        }

        $passwordIsHashed = password_get_info($user['password'])['algo'] !== null;
        if (!$passwordIsHashed
            || password_needs_rehash($user['password'], PASSWORD_DEFAULT)
            || !empty($user['pass'])) {
            $db->executeRun(
                'UPDATE users SET password = :password, pass = :empty_pass WHERE user_id = :user_id',
                array(
                    ':password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                    ':empty_pass' => '',
                    ':user_id' => (int)$user['user_id']
                )
            );
        }

        session_regenerate_id(true);
        $_SESSION['customer_user_id'] = (int)$user['user_id'];
        $_SESSION['customer_username'] = $user['username'];
        $_SESSION['customer_email'] = $user['email'];
        $_SESSION['customer_csrf_token'] = bin2hex(random_bytes(32));
        return true;
    }

    public static function getProfile() {
        if (!self::isAuthenticated()) {
            return false;
        }

        $db = new Database();
        $profile = $db->getOne(
            "SELECT user_id, username, email
             FROM users
             WHERE user_id = :user_id AND status = 'user'",
            array(':user_id' => (int)$_SESSION['customer_user_id'])
        );
        if (!$profile) {
            unset(
                $_SESSION['customer_user_id'],
                $_SESSION['customer_username'],
                $_SESSION['customer_email'],
                $_SESSION['customer_csrf_token']
            );
        }
        return $profile;
    }

    public static function logout(&$error = null) {
        if (!isset($_POST['csrf_token'], $_SESSION['customer_csrf_token'])
            || !hash_equals($_SESSION['customer_csrf_token'], (string)$_POST['csrf_token'])) {
            $error = 'Your session expired. Please try again.';
            return false;
        }

        unset(
            $_SESSION['customer_user_id'],
            $_SESSION['customer_username'],
            $_SESSION['customer_email'],
            $_SESSION['customer_csrf_token']
        );
        session_regenerate_id(true);
        return true;
    }

    private static function passwordMatches($submittedPassword, $user) {
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
        return $passwordMatches;
    }
}
?>
