<?php
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json; charset=utf-8');

$categories = db()->query('SELECT id, name FROM categories ORDER BY name ASC')->fetchAll();
$stmt = db()->prepare(
    'SELECT id, title, description, image, display_order
     FROM slides
     WHERE category_id = :category_id
     ORDER BY display_order ASC, id ASC'
);

$response = [];

foreach ($categories as $category) {
    $stmt->execute(['category_id' => (int) $category['id']]);
    $slides = $stmt->fetchAll();

    foreach ($slides as &$slide) {
        $slide['id'] = (int) $slide['id'];
        $slide['display_order'] = (int) $slide['display_order'];
    }

    $response[] = [
        'category' => [
            'id' => (int) $category['id'],
            'name' => $category['name'],
        ],
        'slides' => $slides,
    ];
}

echo json_encode($response, JSON_UNESCAPED_SLASHES);
