<?php
declare(strict_types=1);
final class Database {
    private static ?PDO $connection = null;
    public static function connection(): PDO {
        if (self::$connection !== null) return self::$connection;
        $database = env('DB_DATABASE', 'brandbuzz_agency');
        $host = env('DB_HOST', '127.0.0.1');
        $port = (int) env('DB_PORT', '3306');
        if (!preg_match('/^[a-zA-Z0-9_\-]+$/', $database) || !preg_match('/^[a-zA-Z0-9.\-]+$/', $host)) throw new RuntimeException('Invalid database configuration.');
        self::$connection = new PDO("mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4", env('DB_USERNAME', 'root'), env('DB_PASSWORD'), [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        return self::$connection;
    }
}
