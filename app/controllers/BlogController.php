<?php
declare(strict_types=1);
final class BlogController {
    public static function index(): void {
        if ($slug = query('slug')) { $post = Blog::bySlug($slug); if (!$post) not_found(); public_page('blog/details',['post'=>$post,'active'=>'blog','title'=>$post['title'],'description'=>$post['excerpt'],'canonical'=>page_url('blog',['slug'=>$slug])]); return; }
        $search = substr(query('q'),0,100); $data = Blog::paginate(max(1,(int)query('page','1')),9,$search,true);
        public_page('blog/index',$data+['search'=>$search,'active'=>'blog','title'=>'Journal','canonical'=>page_url('blog')]);
    }
}
