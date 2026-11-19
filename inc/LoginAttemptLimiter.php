<?php
class LoginAttemptLimiter {
    private const WINDOW_MINUTES = 15;
    private const ACCOUNT_IP_LIMIT = 5;
    private const IP_LIMIT = 30;

    public static function isBlocked($context, $email) {
        $keys = self::getBucketKeys($context, $email);
        $db = new Database();
        $result = $db->getOne(
            'SELECT COUNT(*) AS blocked_count
             FROM login_attempt_limits
             WHERE bucket_hash IN (:ip_key, :account_ip_key)
               AND blocked_until > NOW()',
            array(
                ':ip_key' => $keys['ip'],
                ':account_ip_key' => $keys['account_ip']
            )
        );
        return (int)$result['blocked_count'] > 0;
    }

    public static function recordFailure($context, $email) {
        $keys = self::getBucketKeys($context, $email);
        $db = new Database();
        $sql = 'INSERT INTO login_attempt_limits
                    (bucket_hash, scope, attempts, window_started_at, blocked_until)
                VALUES (:bucket_hash, :scope, 1, NOW(), NULL)
                ON DUPLICATE KEY UPDATE
                    blocked_until = CASE
                        WHEN window_started_at < DATE_SUB(NOW(), INTERVAL ' . self::WINDOW_MINUTES . ' MINUTE) THEN NULL
                        WHEN attempts + 1 >= IF(scope = \'ip\', ' . self::IP_LIMIT . ', ' . self::ACCOUNT_IP_LIMIT . ')
                            THEN DATE_ADD(NOW(), INTERVAL ' . self::WINDOW_MINUTES . ' MINUTE)
                        ELSE blocked_until
                    END,
                    attempts = IF(
                        window_started_at < DATE_SUB(NOW(), INTERVAL ' . self::WINDOW_MINUTES . ' MINUTE),
                        1,
                        attempts + 1
                    ),
                    window_started_at = IF(
                        window_started_at < DATE_SUB(NOW(), INTERVAL ' . self::WINDOW_MINUTES . ' MINUTE),
                        NOW(),
                        window_started_at
                    )';

        foreach (array('ip' => $keys['ip'], 'account_ip' => $keys['account_ip']) as $scope => $key) {
            $db->executeRun($sql, array(':bucket_hash' => $key, ':scope' => $scope));
        }

        $db->executeRun(
            'DELETE FROM login_attempt_limits
             WHERE window_started_at < DATE_SUB(NOW(), INTERVAL 1 DAY)
             LIMIT 500'
        );
    }

    public static function clearAccountFailures($context, $email) {
        $keys = self::getBucketKeys($context, $email);
        $db = new Database();
        $db->executeRun(
            'DELETE FROM login_attempt_limits WHERE bucket_hash = :bucket_hash',
            array(':bucket_hash' => $keys['account_ip'])
        );
    }

    private static function getBucketKeys($context, $email) {
        $ip = isset($_SERVER['REMOTE_ADDR']) ? trim($_SERVER['REMOTE_ADDR']) : '';
        if (filter_var($ip, FILTER_VALIDATE_IP) === false) {
            $ip = 'unknown';
        }
        $normalizedEmail = strtolower(trim($email));

        return array(
            'ip' => hash('sha256', $context . '|ip|' . $ip),
            'account_ip' => hash('sha256', $context . '|account-ip|' . $normalizedEmail . '|' . $ip)
        );
    }
}
?>
