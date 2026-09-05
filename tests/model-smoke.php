<?php
declare(strict_types=1);
// Opt-in integration checks. Never run against a production database.
if (PHP_SAPI !== 'cli') { http_response_code(403); exit; }
require dirname(__DIR__) . '/app/bootstrap.php';
if (APP_ENV !== 'testing' || !str_ends_with(env('DB_DATABASE'), '_test')) {
    fwrite(STDERR, "Use APP_ENV=testing and a separate DB_DATABASE ending in _test. Import agency.sql there first.\n");
    exit(2);
}
set_exception_handler(function(Throwable $error): void { fwrite(STDERR, "FAIL: " . $error->getMessage() . PHP_EOL); exit(1); });
$created = []; $checks = 0;
function expect(bool $result, string $label): void {
    global $checks;
    if (!$result) throw new RuntimeException($label);
    $checks++; echo "PASS: {$label}\n";
}
try {
    $suffix = bin2hex(random_bytes(5));
    $data = ['title'=>'Smoke test','slug'=>'smoke-'.$suffix,'excerpt'=>'An integration test summary.','content'=>'A full paragraph for model integration testing.','status'=>'draft','sort_order'=>9999];
    foreach ([Service::class=>['icon'=>'search','accent'=>'yellow'], Project::class=>['category'=>'Test','client'=>'Test client','project_year'=>2026,'image'=>'images/agency.webp','website_url'=>''], Blog::class=>['category'=>'Test','author'=>'Test author','image'=>'images/agency.webp']] as $model=>$extra) {
        $id = $model::create($data+$extra); $created[] = [$model,$id];
        expect($model::find($id) !== null, $model . ' create/find');
        expect($model::bySlug($data['slug']) === null, $model . ' hides drafts');
        $model::update($id,['status'=>'published']);
        expect((int)$model::bySlug($data['slug'])['id'] === $id, $model . ' publishes content');
        expect($model::slugExists($data['slug']) && !$model::slugExists($data['slug'],$id), $model . ' detects slug conflicts');
    }
    $before = Contact::unread();
    $id = Contact::create(['name'=>'Smoke test','email'=>'smoke@example.test','phone'=>'','service'=>'Test','budget'=>'৳25,000–৳75,000','message'=>'Test enquiry only.','status'=>'new']); $created[] = [Contact::class,$id];
    expect(Contact::unread() === $before+1,'New contact is counted');
    Contact::update($id,['status'=>'read']);
    expect(Contact::unread() === $before,'Read contact leaves unread count');
    expect(Contact::find($id)['budget'] === '৳25,000–৳75,000','UTF-8 data round trip');
    expect(e('<script>') === '&lt;script&gt;','HTML is escaped');
    expect(!safe_external('javascript:alert(1)') && safe_external('https://example.com'),'External links restrict URL schemes');
    expect(!str_contains(paragraphs('<script>x</script>'),'<script>'),'Article paragraphs escape scripts');
    echo "{$checks} checks passed. Created test records are removed below.\n";
} finally {
    foreach(array_reverse($created) as [$model,$id]) $model::delete($id);
}
