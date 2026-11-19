<?php
class ReviewRateLimiter {
    private const WINDOW_MINUTES = 15;
    private const MAX_ATTEMPTS = 5;

    public static function consume() {
        $ip = isset($_SERVER['REMOTE_ADDR']) ? trim($_SERVER['REMOTE_ADDR']) : '';
        if (filter_var($ip, FILTER_VALIDATE_IP) === false) {
            $ip = 'unknown';
        }
        $bucketHash = hash('sha256', 'review|' . $ip);
        $db = new Database();
        $db->executeRun(
            'INSERT INTO review_rate_limits
                (bucket_hash, attempts, window_started_at, blocked_until)
             VALUES (:bucket_hash, 1, NOW(), NULL)
             ON DUPLICATE KEY UPDATE
                blocked_until = CASE
                    WHEN window_started_at < DATE_SUB(NOW(), INTERVAL ' . self::WINDOW_MINUTES . ' MINUTE)
                        THEN NULL
                    WHEN blocked_until > NOW()
                        THEN blocked_until
                    WHEN attempts + 1 > ' . self::MAX_ATTEMPTS . '
                        THEN DATE_ADD(NOW(), INTERVAL ' . self::WINDOW_MINUTES . ' MINUTE)
                    ELSE NULL
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
                )',
            array(':bucket_hash' => $bucketHash)
        );

        $result = $db->getOne(
            'SELECT blocked_until > NOW() AS is_blocked
             FROM review_rate_limits
             WHERE bucket_hash = :bucket_hash',
            array(':bucket_hash' => $bucketHash)
        );

        $db->executeRun(
            'DELETE FROM review_rate_limits
             WHERE window_started_at < DATE_SUB(NOW(), INTERVAL 1 DAY)
             LIMIT 100'
        );

        return $result && (int)$result['is_blocked'] === 1;
    }
}
?>
