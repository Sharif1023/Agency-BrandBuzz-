<?php
declare(strict_types=1);
final class User extends Model {
    protected static string $table = 'users';
    protected static array $fillable = ['name','email','password','role','session_version','session_version'];
    public static function byEmail(string $email): ?array { $stmt = self::db()->prepare('SELECT * FROM users WHERE email = ?'); $stmt->execute([$email]); return $stmt->fetch() ?: null; }
}
