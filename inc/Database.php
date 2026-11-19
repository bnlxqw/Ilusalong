<?php
class database {
    private $conn = null;
    private $host;
    private $user;
    private $password;
    private $baseName;
    private $charset;

    function __construct() {
        $configFile = __DIR__ . '/../config/db.php';
        if (!is_file($configFile)) {
            error_log('Database config file not found: ' . $configFile);
            http_response_code(503);
            die('Service temporarily unavailable.');
        }
        $cfg = require $configFile;
        $this->host     = $cfg['host'];
        $this->user     = $cfg['user'];
        $this->password = $cfg['password'];
        $this->baseName = $cfg['dbname'];
        $this->charset  = $cfg['charset'] ?? 'utf8mb4';
        $this->connect();
    }

    function __destruct() {
        $this->disconnect();
    }

    function connect() {
        if (!$this->conn) {
            try {
                $dsn = 'mysql:host=' . $this->host
                     . ';dbname='    . $this->baseName
                     . ';charset='   . $this->charset;
                $this->conn = new PDO($dsn, $this->user, $this->password, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (Exception $e) {
                error_log('Database connection error: ' . $e->getMessage());
                http_response_code(503);
                die('Service temporarily unavailable.');
            }
        }
        return $this->conn;
    }

    function disconnect() {
        if ($this->conn) {
            $this->conn = null;
        }
    }

    function getOne($query, $params = array()) {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    function getAll($query, $params = array()) {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    function executeRun($query, $params = array()) {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    function beginTransaction() {
        return $this->conn->beginTransaction();
    }

    function commit() {
        return $this->conn->commit();
    }

    function rollBack() {
        return $this->conn->rollBack();
    }

    function inTransaction() {
        return $this->conn->inTransaction();
    }

    function lastInsertId() {
        return $this->conn->lastInsertId();
    }
}
?>