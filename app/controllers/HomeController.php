<?php
declare(strict_types=1);
final class HomeController {
    public static function index(): void { public_page('home/index',['active'=>'index','services'=>Service::published(),'canonical'=>page_url()]); }
    public static function about(): void { public_page('about/index',['active'=>'about','title'=>'About us','canonical'=>page_url('about')]); }
}
