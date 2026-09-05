<?php
declare(strict_types=1);
function upload_image(string $field, ?string $existing = null): ?string {
    if (!isset($_FILES[$field]) || ($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) return $existing;
    $file = $_FILES[$field];
    if (!is_array($file) || !isset($file['tmp_name'], $file['size'], $file['error']) || is_array($file['error']) || $file['error'] !== UPLOAD_ERR_OK) throw new InvalidArgumentException('The image could not be uploaded. Check the server upload size limit.');
    if ($file['size'] > UPLOAD_LIMIT || $file['size'] < 1 || !is_uploaded_file($file['tmp_name'])) throw new InvalidArgumentException('Choose a valid image smaller than ' . (UPLOAD_LIMIT / 1048576) . ' MB.');
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $extension = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'][$mime] ?? null;
    $dimensions = @getimagesize($file['tmp_name']);
    if (!$extension || !$dimensions || $dimensions[0] > 8000 || $dimensions[1] > 8000 || $dimensions[0] * $dimensions[1] > 24000000) throw new InvalidArgumentException('Use a JPG, PNG, WebP or GIF image, up to 24 megapixels and 8000px per side.');
    if (!is_dir(UPLOAD_DIR) && !mkdir(UPLOAD_DIR, 0755, true)) throw new RuntimeException('The upload directory is unavailable.');
    $name = bin2hex(random_bytes(20)) . '.' . $extension;
    if (!move_uploaded_file($file['tmp_name'], UPLOAD_DIR . $name)) throw new RuntimeException('Could not save the uploaded image. Check folder permissions.');
    return 'uploads/' . $name;
}
function delete_upload(?string $path): void {
    if ($path && preg_match('#^uploads/[a-f0-9]{40}\.(jpg|png|webp|gif)$#', $path)) { $file = ROOT_PATH . '/public_assets/' . $path; if (is_file($file)) unlink($file); }
}
