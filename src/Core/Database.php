<?php

namespace App\Core;

use Exception;
use mysqli;
use mysqli_stmt;
use mysqli_sql_exception;

class Database
{
    /** @var mysqli */
    private $mysqli;

    /** @var Database|null */
    private static $instance = null;

    /** @var array */
    private static $defaultOptions = [
        // mysqli options will be set in constructor
    ];

    /** @var array */
    private static $connectionParams = [];

    /**
     * @param  string  $host  Database host
     * @param  string  $dbname  Database name
     * @param  string  $username  Database username
     * @param  string  $password  Database password
     * @param  array  $options  MySQLi options
     *
     * @throws mysqli_sql_exception if connection fails
     */
    private function __construct(
        string $host,
        string $dbname,
        string $username,
        string $password,
        array $options = []
    ) {
        // Enable mysqli exception mode
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        
        try {
            $this->mysqli = new mysqli($host, $username, $password, $dbname);
            $this->mysqli->set_charset('utf8mb4');
            
            // Apply any custom options
            foreach ($options as $option => $value) {
                $this->mysqli->options($option, $value);
            }
        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception('Database connection failed: '.$e->getMessage(),
                (int) $e->getCode(), $e);
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            if (empty(self::$connectionParams)) {
                self::loadConfig();
            }

            self::$instance = new self(
                self::$connectionParams['host'],
                self::$connectionParams['dbname'],
                self::$connectionParams['username'],
                self::$connectionParams['password'],
                self::$connectionParams['options'] ?? []
            );
        }

        return self::$instance;
    }

    /**
     * Load database configuration from config file
     */
    public static function loadConfig(): void
    {
        $configPath = dirname(dirname(__DIR__)).'/config/database.php';

        if (! file_exists($configPath)) {
            throw new Exception('Database configuration file not found.');
        }

        self::$connectionParams = require $configPath;
    }

    private function __clone() {}

    public function __wakeup()
    {
        throw new Exception('Cannot unserialize singleton');
    }

    /**
     * @param  string  $sql  SQL query with placeholders
     * @param  array  $params  Parameters to bind
     * @return mysqli_stmt The prepared and executed statement
     */
    public function query(string $sql, array $params = []): mysqli_stmt
    {
        $stmt = $this->mysqli->prepare($sql);
        
        if (!empty($params)) {
            // Build types string for bind_param (s for string, i for integer, d for double, b for blob)
            $types = '';
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= 'i';
                } elseif (is_float($param)) {
                    $types .= 'd';
                } elseif (is_string($param)) {
                    $types .= 's';
                } else {
                    $types .= 'b'; // Default to blob
                }
            }
            
            // Dynamically bind parameters
            if (!empty($types)) {
                $bindParams = [$types];
                foreach ($params as $key => $value) {
                    $bindParams[] = &$params[$key];
                }
                call_user_func_array([$stmt, 'bind_param'], $bindParams);
            }
        }
        
        $stmt->execute();
        return $stmt;
    }

    public function fetchRow(string $sql, array $params = []): ?array
    {
        $stmt = $this->query($sql, $params);
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return $row === false ? null : $row;
    }

    public function fetchRows(string $sql, array $params = []): array
    {
        $stmt = $this->query($sql, $params);
        $result = $stmt->get_result();
        
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        
        $stmt->close();
        return $rows;
    }

    public function execute(string $sql, array $params = []): int
    {
        $stmt = $this->query($sql, $params);
        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        
        return $affectedRows;
    }

    public function getLastInsertId(?string $name = null): string
    {
        return (string)$this->mysqli->insert_id;
    }

    public function beginTransaction(): bool
    {
        return $this->mysqli->begin_transaction();
    }

    public function commit(): bool
    {
        return $this->mysqli->commit();
    }

    public function rollBack(): bool
    {
        return $this->mysqli->rollback();
    }

    public function getMysqli(): mysqli
    {
        return $this->mysqli;
    }

    public function closeConnection(): void
    {
        if ($this->mysqli) {
            $this->mysqli->close();
            $this->mysqli = null;
        }
        self::$instance = null;
    }
}
