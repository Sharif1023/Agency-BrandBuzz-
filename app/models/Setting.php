<?php
declare(strict_types=1);
final class Setting {
    private static ?array $cache = null;
    public static function all(): array { if (self::$cache === null) self::$cache = Database::connection()->query('SELECT setting_key, setting_value FROM settings')->fetchAll(PDO::FETCH_KEY_PAIR); return self::$cache; }
    public static function get(string $key, string $default = ''): string { return (string)(self::all()[$key] ?? $default); }
    public static function save(array $settings): void {
        $db = Database::connection(); $db->beginTransaction();
        try { $stmt = $db->prepare('INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)'); foreach ($settings as $key => $value) $stmt->execute([$key, (string)$value]); $db->commit(); self::$cache = null; }
        catch(Throwable $e) { $db->rollBack(); throw $e; }
    }
}
