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
     */
    public static function getById($id): ?array
    {
        $db = Database::getInstance();

        $sql = 'SELECT * FROM users WHERE id = ?';

        return $db->fetchRow($sql, [$id]);
    }

    /**
     * Get count of all users
     */
    public static function getCount(): int
    {
        $db = Database::getInstance();

        $sql = 'SELECT COUNT(*) as count FROM users';
        $result = $db->fetchRow($sql);

        return (int) ($result['count'] ?? 0);
    }

    /**
     * Update user information
     */
    public static function update($id, array $data): bool
    {
        $db = Database::getInstance();

        $sql = 'UPDATE users SET 
                name = ?, 
                email = ?';

        $params = [
            $data['name'],
            $data['email'],
        ];

        if (isset($data['profile_image'])) {
            $sql .= ', profile_image = ?';
            $params[] = $data['profile_image'];
        }

        if (isset($data['password'])) {
            $sql .= ', password = ?';
            $params[] = $data['password'];
        }

        $sql .= ' WHERE id = ?';
        $params[] = $id;

        return $db->execute($sql, $params);
    }
}
