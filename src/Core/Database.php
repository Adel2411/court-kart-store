<?php

namespace App\Core;

use Exception;
use PDO;
use PDOException;

class Database
{
    /** @var PDO */
    private $pdo;

    /** @var Database|null */
    private static $instance = null;

    /** @var array */
    private static $defaultOptions = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    /** @var array */
    private static $connectionParams = [];

    /**
     * @param  string  $host  Database host
     * @param  string  $dbname  Database name
     * @param  string  $username  Database username
     * @param  string  $password  Database password
     * @param  array  $options  PDO options
     *
     * @throws PDOException if connection fails
     */
    private function __construct(
        string $host,
        string $dbname,
        string $username,
        string $password,
        array $options = []
    ) {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

        try {
            $this->pdo = new PDO(
                $dsn,
                $username,
                $password,
                array_merge(self::$defaultOptions, $options)
            );
        } catch (PDOException $e) {
            throw new PDOException('Database connection failed: '.$e->getMessage(),
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

    /**
     * Private clone method to prevent cloning of the singleton instance.
     */
    private function __clone(): void {}

    /**
     * Prevent unserialization of the singleton instance.
     *
     * @throws Exception When attempting to unserialize singleton
     */
    public function __wakeup(): void
    {
        throw new Exception('Cannot unserialize singleton');
    }

    /**
     * @param  string  $sql  SQL query with placeholders
     * @param  array  $params  Parameters to bind
     * @return \PDOStatement The prepared and executed statement
     */
    public function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);

        if (! empty($params)) {
            foreach ($params as $key => $value) {
                $type = PDO::PARAM_STR;
                if (is_int($value)) {
                    $type = PDO::PARAM_INT;
                } elseif (is_bool($value)) {
                    $type = PDO::PARAM_BOOL;
                } elseif (is_null($value)) {
                    $type = PDO::PARAM_NULL;
                }

                if (is_int($key)) {
                    $stmt->bindValue($key + 1, $value, $type);
                } else {
                    $stmt->bindValue($key, $value, $type);
                }
            }
            $stmt->execute();
        } else {
            $stmt->execute();
        }

        return $stmt;
    }

    /**
     * Fetches a single row from the database.
     *
     * @param  string  $sql  SQL query with placeholders
     * @param  array  $params  Parameters to bind
     * @return array|null The fetched row as an associative array, or null if no row is found
     */
    public function fetchRow(string $sql, array $params = []): ?array
    {
        $stmt = $this->query($sql, $params);
        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    /**
     * Fetches multiple rows from the database.
     *
     * @param  string  $sql  SQL query with placeholders
     * @param  array  $params  Parameters to bind
     * @return array The fetched rows as an array of associative arrays
     */
    public function fetchRows(string $sql, array $params = []): array
    {
        $stmt = $this->query($sql, $params);
        $results = $stmt->fetchAll();

        return $results ?: [];
    }

    /**
     * Executes an SQL statement and returns the number of affected rows.
     *
     * @param  string  $sql  SQL query with placeholders
     * @param  array  $params  Parameters to bind
     * @return int The number of affected rows
     */
    public function execute(string $sql, array $params = []): int
    {
        $stmt = $this->query($sql, $params);

        return $stmt->rowCount();
    }

    public function getLastInsertId(?string $name = null): string
    {
        return $this->pdo->lastInsertId($name);
    }

    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    public function rollBack(): bool
    {
        return $this->pdo->rollBack();
    }

    /**
     * Check if a transaction is currently active
     *
     * @return bool True if a transaction is active, false otherwise
     */
    public function inTransaction(): bool
    {
        return $this->pdo->inTransaction();
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    public function closeConnection(): void
    {
        $this->pdo = null;
        self::$instance = null;
    }

    /**
     * Fetches a single column value from the database.
     *
     * @param  string  $sql  SQL query with placeholders
     * @param  array  $params  Parameters to bind
     * @return mixed|null The fetched value, or null if no row is found
     */
    public function fetchOne(string $sql, array $params = []): mixed
    {
        $stmt = $this->query($sql, $params);
        $value = $stmt->fetchColumn();

        return $value === false ? null : $value;
    }
}
