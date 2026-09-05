<?php
declare(strict_types=1);
final class Contact extends Model {
    protected static string $table = 'contacts';
    protected static array $fillable = ['name','email','phone','service','budget','message','status'];
    public static function unread(): int { return (int)self::db()->query("SELECT COUNT(*) FROM contacts WHERE status = 'new'")->fetchColumn(); }
    public static function listing(int $page, string $status = '', string $search = ''): array {
        $conditions = []; $args = [];
        if (in_array($status,['new','read','archived'],true)) { $conditions[] = 'status = ?'; $args[] = $status; }
        if ($search !== '') { $conditions[] = '(name LIKE ? OR email LIKE ? OR message LIKE ?)'; $args = [...$args, ...array_fill(0,3,'%' . $search . '%')]; }
        $where = $conditions ? ' WHERE ' . implode(' AND ', $conditions) : '';
        $stmt = self::db()->prepare('SELECT COUNT(*) FROM contacts' . $where); $stmt->execute($args); $total = (int)$stmt->fetchColumn();
        $pages = max(1,(int)ceil($total/12)); $page = max(1,min($page,$pages)); $offset = ($page - 1)*12;
        $stmt = self::db()->prepare('SELECT * FROM contacts' . $where . ' ORDER BY id DESC LIMIT 12 OFFSET ' . $offset); $stmt->execute($args);
        return ['items'=>$stmt->fetchAll(),'page'=>$page,'pages'=>$pages,'total'=>$total];
    }
}
