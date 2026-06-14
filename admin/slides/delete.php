<?php
require_once __DIR__ . '/../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    set_flash('danger', 'Invalid delete request.');
    redirect('admin/slides/index.php');
}

require_valid_csrf('admin/slides/index.php');

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    set_flash('danger', 'Invalid slide ID.');
    redirect('admin/slides/index.php');
}

$slide = find_slide($id);

if (!$slide) {
    set_flash('danger', 'Slide not found.');
    redirect('admin/slides/index.php');
}

$stmt = db()->prepare('DELETE FROM slides WHERE id = :id');
$stmt->execute(['id' => $id]);

delete_uploaded_file($slide['image'] ?? null);

set_flash('success', 'Slide deleted successfully.');
redirect('admin/slides/index.php');
