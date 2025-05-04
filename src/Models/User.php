<?php

namespace App\Models;

use App\Core\Database;

class User
{
    private $id;

    private $name;

    private $email;

    private $role;

    private $created_at;

    /**
     * Get all users from the database
     *
     * @param  int  $limit  Optional limit of users to fetch
     * @return array Array of users
     */
    public static function getAll($limit = 20): array
    {
        $db = Database::getInstance();

        $limit = (int) $limit;
        $sql = "SELECT * FROM users ORDER BY id DESC LIMIT $limit";

        return $db->fetchRows($sql, []);
    }

    /**
     * Get a user by their ID
     *
     * @param  int  $id  User ID
     * @return array|null User data or null if not found
     */
    public static function getById($id): ?array
    {
        $db = Database::getInstance();

        $sql = 'SELECT * FROM users WHERE id = ?';

        return $db->fetchRow($sql, [$id]);
    }

    /**
     * Get count of all users
     *
     * @return int Count of users
     */
    public static function getCount(): int
    {
        $db = Database::getInstance();

        $sql = 'SELECT COUNT(*) as count FROM users';
        $result = $db->fetchRow($sql);

        return (int) ($result['count'] ?? 0);
    }
}
