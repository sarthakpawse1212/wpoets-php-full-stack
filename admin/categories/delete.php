<?php
require_once __DIR__ . '/../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    set_flash('danger', 'Invalid delete request.');
    redirect('admin/categories/index.php');
}

require_valid_csrf('admin/categories/index.php');

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    set_flash('danger', 'Invalid category ID.');
    redirect('admin/categories/index.php');
}

$category = find_category($id);

if (!$category) {
    set_flash('danger', 'Category not found.');
    redirect('admin/categories/index.php');
}

$stmt = db()->prepare('SELECT image FROM slides WHERE category_id = :category_id');
$stmt->execute(['category_id' => $id]);
$images = $stmt->fetchAll();

$delete = db()->prepare('DELETE FROM categories WHERE id = :id');
$delete->execute(['id' => $id]);

foreach ($images as $image) {
    delete_uploaded_file($image['image'] ?? null);
}

set_flash('success', 'Category deleted successfully.');
redirect('admin/categories/index.php');
