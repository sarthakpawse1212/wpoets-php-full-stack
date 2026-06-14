<?php
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json; charset=utf-8');

$stmt = db()->query('SELECT id, name FROM categories ORDER BY name ASC');
$categories = $stmt->fetchAll();

foreach ($categories as &$category) {
    $category['id'] = (int) $category['id'];
}

echo json_encode($categories, JSON_UNESCAPED_SLASHES);
