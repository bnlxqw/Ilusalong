<?php
/**
 * Database configuration EXAMPLE.
 *
 * Copy this file to config/db.php and fill in your real credentials.
 * config/db.php is listed in .gitignore and must never be committed.
 *
 * Production checklist:
 *  - Create a dedicated MySQL user with minimal privileges (SELECT, INSERT, UPDATE, DELETE).
 *  - Never use 'root' or an empty password in production.
 *
 * Example:
 *   CREATE USER 'ilusalong_user'@'localhost' IDENTIFIED BY 'YourStrongPassword!';
 *   GRANT SELECT, INSERT, UPDATE, DELETE ON ilusalong.* TO 'ilusalong_user'@'localhost';
 *   FLUSH PRIVILEGES;
 */
return [
    'host'     => 'localhost',
    'dbname'   => 'ilusalong',
    'user'     => 'ilusalong_user',
    'password' => 'change_me',
    'charset'  => 'utf8mb4',
];
