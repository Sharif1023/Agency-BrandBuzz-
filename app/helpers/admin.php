<?php
declare(strict_types=1);
function admin_start(string $title, string $section, string $subtitle = ''): void {
    $adminUser = $GLOBALS['adminUser'];
    require ROOT_PATH . '/admin/components/header.php';
    require ROOT_PATH . '/admin/components/sidebar.php';
    echo '<div class="admin-main"><header class="bg-white border-b border-gray-200 px-6 lg:px-10 py-5 flex items-center justify-between gap-5"><div class="flex items-center gap-4"><button class="lg:hidden p-2" data-admin-toggle aria-controls="admin-sidebar" aria-expanded="false" aria-label="Toggle admin menu">' . icon('menu') . '</button><p class="text-sm text-muted">Your creative workspace</p></div><a class="btn btn-neutral btn-small" href="' . e(page_url()) . '" target="_blank" rel="noopener">View website ' . icon('eye','w-4 h-4') . '</a></header><main id="main-content" class="px-5 lg:px-10 py-8 max-w-[1440px] mx-auto"><div class="mb-8"><p class="eyebrow">BrandBuzz / ' . e(ucfirst($section)) . '</p><h1 class="text-3xl font-bold tracking-tight">' . e($title) . '</h1>' . ($subtitle ? '<p class="text-muted mt-3">' . e($subtitle) . '</p>' : '') . '</div>';
    foreach(flashes() as $message) echo '<div role="status" class="flash flash-' . e($message['type']) . '">' . e($message['message']) . '</div>';
}
function admin_end(): void { echo '</main><footer class="px-6 lg:px-10 py-6 text-xs text-muted">BrandBuzz CMS · Made for your next big idea.</footer></div></body></html>'; }
function admin_errors(array $errors): void { if (!$errors) return; echo '<div class="flash flash-error" role="alert"><p class="font-semibold">Please fix the following:</p><ul class="list-disc pl-5 mt-2">'; foreach($errors as $error) echo '<li>' . e($error) . '</li>'; echo '</ul></div>'; }
function admin_field(string $name, string $label, mixed $value = '', string $type = 'text', bool $required = false, int $max = 190, string $help = ''): void {
    $req = $required ? ' required' : '';
    echo '<div><label for="' . e($name) . '">' . e($label) . ($required ? ' <span class="text-brand-600">*</span>' : '') . '</label>';
    if ($type === 'textarea') echo '<textarea id="' . e($name) . '" name="' . e($name) . '" maxlength="' . $max . '"' . $req . '>' . e($value) . '</textarea>';
    else echo '<input id="' . e($name) . '" name="' . e($name) . '" type="' . e($type) . '" value="' . e($value) . '" maxlength="' . $max . '"' . $req . ($name==='title'?' data-slug-source':'') . ($name==='slug'?' data-slug-target':'') . '>';
    if ($help) echo '<p class="field-help">' . e($help) . '</p>'; echo '</div>';
}
function admin_select(string $name, string $label, array $options, string $value): void {
    echo '<div><label for="' . e($name) . '">' . e($label) . '</label><select id="' . e($name) . '" name="' . e($name) . '">';
    foreach($options as $key=>$text) echo '<option value="' . e($key) . '"' . ($value===(string)$key?' selected':'') . '>' . e($text) . '</option>'; echo '</select></div>';
}
function admin_image(string $field, string $label, ?string $path): void {
    echo '<div><label for="' . e($field) . '">' . e($label) . '</label><input type="file" name="' . e($field) . '" id="' . e($field) . '" accept="image/jpeg,image/png,image/webp,image/gif" data-image-input="preview-' . e($field) . '"><p class="field-help">JPG, PNG, WebP or GIF · Maximum ' . (UPLOAD_LIMIT/1048576) . ' MB. Leave empty to keep the current image.</p><img id="preview-' . e($field) . '" class="file-preview" src="' . e(media($path)) . '" alt="Current image"' . ($path?'':' hidden') . '></div>';
}
