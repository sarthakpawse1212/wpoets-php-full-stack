<?php

declare(strict_types=1);

function handle_image_upload(array $file): array
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return ['path' => null, 'error' => null];
    }

    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        return ['path' => null, 'error' => 'Image upload failed. Please try again.'];
    }

    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    $tmpName = $file['tmp_name'] ?? '';

    if (!is_uploaded_file($tmpName)) {
        return ['path' => null, 'error' => 'Invalid uploaded file.'];
    }

    $mime = mime_content_type($tmpName);

    if (!isset($allowed[$mime])) {
        return ['path' => null, 'error' => 'Only JPG, JPEG, PNG, and WEBP images are allowed.'];
    }

    $uploadDir = __DIR__ . '/../uploads';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $filename = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];
    $destination = $uploadDir . DIRECTORY_SEPARATOR . $filename;

    if (!move_uploaded_file($tmpName, $destination)) {
        return ['path' => null, 'error' => 'Unable to save uploaded image.'];
    }

    return ['path' => 'uploads/' . $filename, 'error' => null];
}
