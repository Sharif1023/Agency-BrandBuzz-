<?php
declare(strict_types=1);
final class Blog extends Model {
    protected static string $table = 'blogs';
    protected static array $fillable = ['title','slug','excerpt','content','category','author','image','status','sort_order'];
}
