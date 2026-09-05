<?php
declare(strict_types=1);
final class ServiceController {
    public static function index(): void {
        if ($slug = query('slug')) { $service = Service::bySlug($slug); if (!$service) not_found(); public_page('services/details',['service'=>$service,'active'=>'services','title'=>$service['title'],'description'=>$service['excerpt'],'canonical'=>page_url('services',['slug'=>$slug])]); return; }
        public_page('services/index',['active'=>'services','title'=>'Our services','services'=>Service::published(),'canonical'=>page_url('services')]);
    }
}
