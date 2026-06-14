<?php
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json; charset=utf-8');

$categoryId = filter_input(INPUT_GET, 'category_id', FILTER_VALIDATE_INT);

if (!$categoryId) {
    http_response_code(422);
    echo json_encode(['error' => 'A valid category_id query parameter is required.']);
    exit;
}

$stmt = db()->prepare(
    'SELECT id, title, description, image, display_order
     FROM slides
     WHERE category_id = :category_id
     ORDER BY display_order ASC, id ASC'
);
$stmt->execute(['category_id' => $categoryId]);
$slides = $stmt->fetchAll();

foreach ($slides as &$slide) {
    $slide['id'] = (int) $slide['id'];
    $slide['display_order'] = (int) $slide['display_order'];
}

echo json_encode($slides, JSON_UNESCAPED_SLASHES);
