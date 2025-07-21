<?php

namespace App\Modules\Posts;

use App\Core\Database;
use PDO;

class PostModel
{
    protected static string $table = 'posts';

    public static function getAll(): array
    {
        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT * FROM " . self::$table);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find(int $id): ?array
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM " . self::$table . " WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function create(array $data): int
    {
        $pdo = Database::connect();

        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO " . self::$table . " ($columns) VALUES ($placeholders)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_values($data));

        return $pdo->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $pdo = Database::connect();

        // Filtrar campos nulos ou vazios
        $data = array_filter($data, fn($value) => $value !== null && $value !== '');

        if (empty($data)) {
            return false;
        }

        $setClause = implode(' = ?, ', array_keys($data)) . ' = ?';

        $sql = "UPDATE " . self::$table . " SET $setClause WHERE id = ?";

        $stmt = $pdo->prepare($sql);
        $values = array_values($data);
        $values[] = $id;

        return $stmt->execute($values);
    }

    public static function delete(int $id): bool
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("DELETE FROM " . self::$table . " WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function getPostsByCategory(string $category): array
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM " . self::$table . " WHERE category = ?");
        $stmt->execute([$category]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllCategories(): array
    {
        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT DISTINCT category FROM " . self::$table . " WHERE category IS NOT NULL");
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }
}
