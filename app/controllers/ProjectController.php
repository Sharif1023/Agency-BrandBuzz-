<?php
declare(strict_types=1);
final class ProjectController {
    public static function index(): void {
        if ($slug = query('slug')) { $project = Project::bySlug($slug); if (!$project) not_found(); public_page('portfolio/details',['project'=>$project,'active'=>'portfolio','title'=>$project['title'],'description'=>$project['excerpt'],'canonical'=>page_url('portfolio',['slug'=>$slug])]); return; }
        $projects = Project::published(); $categories = array_values(array_unique(array_column($projects,'category'))); sort($categories); $category = query('category');
        if ($category !== '') $projects = array_values(array_filter($projects,fn($item)=>$item['category'] === $category));
        public_page('portfolio/index',compact('projects','categories','category')+['active'=>'portfolio','title'=>'Our work','canonical'=>page_url('portfolio')]);
    }
}
