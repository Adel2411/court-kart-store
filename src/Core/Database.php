<?php

namespace App\Core;

use PDO;
use PDOException;
use Exception;

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
     * @param string $host Database host
     * @param string $dbname Database name
     * @param string $username Database username
     * @param string $password Database password
     * @param array $options PDO options
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
        $configPath = dirname(dirname(__DIR__)) . '/config/database.php';

        if (!file_exists($configPath)) {
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
     * @param string $sql SQL query with placeholders
     * @param array $params Parameters to bind
     * @return \PDOStatement The prepared and executed statement
     */
    public function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }

    public function fetchRow(string $sql, array $params = []): ?array
    {
        $stmt = $this->query($sql, $params);
        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    public function fetchRows(string $sql, array $params = []): array
    {
        $stmt = $this->query($sql, $params);
        $results = $stmt->fetchAll();

        return $results ?: [];
    }

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

    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    public function closeConnection(): void
    {
        $this->pdo = null;
        self::$instance = null;
    }
}
