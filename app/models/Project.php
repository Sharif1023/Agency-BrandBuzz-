<?php
declare(strict_types=1);
final class Project extends Model {
    protected static string $table = 'projects';
    protected static array $fillable = ['title','slug','excerpt','content','category','client','project_year','image','website_url','status','sort_order'];
}
