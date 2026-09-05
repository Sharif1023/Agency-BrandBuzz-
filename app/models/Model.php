<?php
declare(strict_types=1);
abstract class Model {
    protected static string $table;
    protected static array $fillable = [];
    public static function db(): PDO { return Database::connection(); }
    public static function find(int $id): ?array { $stmt = static::db()->prepare('SELECT * FROM ' . static::$table . ' WHERE id = ?'); $stmt->execute([$id]); return $stmt->fetch() ?: null; }
    public static function all(): array { return static::db()->query('SELECT * FROM ' . static::$table . ' ORDER BY id DESC')->fetchAll(); }
    public static function count(): int { return (int)static::db()->query('SELECT COUNT(*) FROM ' . static::$table)->fetchColumn(); }
    public static function create(array $data): int {
        $data = array_intersect_key($data, array_flip(static::$fillable));
        if (!$data) throw new InvalidArgumentException('No fields supplied.');
        $columns = array_keys($data);
        $stmt = static::db()->prepare('INSERT INTO ' . static::$table . ' (`' . implode('`,`', $columns) . '`) VALUES (' . implode(',', array_fill(0, count($columns), '?')) . ')');
        $stmt->execute(array_values($data)); return (int)static::db()->lastInsertId();
    }
    public static function update(int $id, array $data): void {
        $data = array_intersect_key($data, array_flip(static::$fillable));
        if (!$data) return;
        $sets = array_map(fn($key) => '`' . $key . '` = ?', array_keys($data));
        $stmt = static::db()->prepare('UPDATE ' . static::$table . ' SET ' . implode(', ', $sets) . ' WHERE id = ?');
        $stmt->execute([...array_values($data), $id]);
    }
    public static function delete(int $id): void { $stmt = static::db()->prepare('DELETE FROM ' . static::$table . ' WHERE id = ?'); $stmt->execute([$id]); }
    public static function slugExists(string $slug, int $except = 0): bool { $stmt = static::db()->prepare('SELECT COUNT(*) FROM ' . static::$table . ' WHERE slug = ? AND id <> ?'); $stmt->execute([$slug, $except]); return (int)$stmt->fetchColumn() > 0; }
    public static function published(): array { return static::db()->query('SELECT * FROM ' . static::$table . " WHERE status = 'published' ORDER BY sort_order ASC, id DESC")->fetchAll(); }
    public static function bySlug(string $slug): ?array { $stmt = static::db()->prepare('SELECT * FROM ' . static::$table . " WHERE slug = ? AND status = 'published'"); $stmt->execute([$slug]); return $stmt->fetch() ?: null; }
    public static function paginate(int $page = 1, int $perPage = 10, string $search = '', bool $public = false): array {
        $where = []; $values = [];
        if ($public) $where[] = "status = 'published'";
        if ($search !== '') { $where[] = 'title LIKE ?'; $values[] = '%' . $search . '%'; }
        $clause = $where ? ' WHERE ' . implode(' AND ', $where) : '';
        $count = static::db()->prepare('SELECT COUNT(*) FROM ' . static::$table . $clause); $count->execute($values); $total = (int)$count->fetchColumn();
        $pages = max(1, (int)ceil($total / $perPage)); $page = max(1, min($pages, $page)); $offset = ($page - 1) * $perPage;
        $stmt = static::db()->prepare('SELECT * FROM ' . static::$table . $clause . " ORDER BY id DESC LIMIT {$perPage} OFFSET {$offset}"); $stmt->execute($values);
        return ['items' => $stmt->fetchAll(), 'total' => $total, 'page' => $page, 'pages' => $pages];
    }
}
