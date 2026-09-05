<?php
declare(strict_types=1);
final class Service extends Model {
    protected static string $table = 'services';
    protected static array $fillable = ['title','slug','excerpt','content','icon','accent','status','sort_order'];
}
