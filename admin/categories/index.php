<?php
require_once __DIR__ . '/../../includes/functions.php';

$pageTitle = 'Categories';
$stmt = db()->query(
    'SELECT categories.id, categories.name, categories.created_at, COUNT(slides.id) AS slides_count
     FROM categories
     LEFT JOIN slides ON slides.category_id = categories.id
     GROUP BY categories.id, categories.name, categories.created_at
     ORDER BY categories.name ASC'
);
$categories = $stmt->fetchAll();

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Categories</h1>
    <a href="<?= e(base_url('admin/categories/create.php')) ?>" class="btn btn-primary">Create Category</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
            <tr>
                <th>Name</th>
                <th>Slides</th>
                <th>Created</th>
                <th class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!$categories): ?>
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">No categories found.</td>
                </tr>
            <?php endif; ?>
            <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?= e($category['name']) ?></td>
                    <td><?= (int) $category['slides_count'] ?></td>
                    <td><?= e($category['created_at']) ?></td>
                    <td class="text-end">
                        <a href="<?= e(base_url('admin/categories/edit.php?id=' . (int) $category['id'])) ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                        <form method="post" action="<?= e(base_url('admin/categories/delete.php')) ?>" class="d-inline">
                            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                            <input type="hidden" name="id" value="<?= (int) $category['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger" data-confirm="Delete this category and all of its slides?">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
