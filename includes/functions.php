<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function base_url(string $path = ''): string
{
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    $basePath = preg_replace('#/(admin|api)(/.*)?$#', '', $scriptDir);
    $basePath = rtrim((string) $basePath, '/');

    return ($basePath === '' ? '' : $basePath) . '/' . ltrim($path, '/');
}

function redirect(string $path): never
{
    header('Location: ' . base_url($path));
    exit;
}

function set_flash(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function get_flash(): ?array
{
    if (empty($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verify_csrf_token(?string $token): bool
{
    return is_string($token)
        && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

function require_valid_csrf(string $redirectPath): void
{
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        set_flash('danger', 'Your session expired. Please try again.');
        redirect($redirectPath);
    }
}

function post_value(string $key, mixed $default = ''): mixed
{
    return $_POST[$key] ?? $default;
}

function validate_required_string(string $value, string $fieldName, int $maxLength = 255): ?string
{
    $value = trim($value);

    if ($value === '') {
        return $fieldName . ' is required.';
    }

    if (mb_strlen($value) > $maxLength) {
        return $fieldName . ' may not exceed ' . $maxLength . ' characters.';
    }

    return null;
}

function get_all_categories(): array
{
    $stmt = db()->query('SELECT id, name, created_at FROM categories ORDER BY name ASC');
    return $stmt->fetchAll();
}

function find_category(int $id): ?array
{
    $stmt = db()->prepare('SELECT id, name, created_at FROM categories WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $category = $stmt->fetch();

    return $category ?: null;
}

function find_slide(int $id): ?array
{
    $stmt = db()->prepare(
        'SELECT slides.*, categories.name AS category_name
         FROM slides
         INNER JOIN categories ON categories.id = slides.category_id
         WHERE slides.id = :id'
    );
    $stmt->execute(['id' => $id]);
    $slide = $stmt->fetch();

    return $slide ?: null;
}

function delete_uploaded_file(?string $path): void
{
    if (!$path) {
        return;
    }

    $fullPath = realpath(__DIR__ . '/../' . ltrim($path, '/'));
    $uploadsDir = realpath(__DIR__ . '/../uploads');

    if ($fullPath && $uploadsDir && str_starts_with($fullPath, $uploadsDir) && is_file($fullPath)) {
        unlink($fullPath);
    }
}
