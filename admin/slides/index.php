<?php
require_once __DIR__ . '/../../includes/functions.php';

$pageTitle = 'Slides';
$stmt = db()->query(
    'SELECT slides.id, slides.title, slides.description, slides.image, slides.display_order, slides.created_at, categories.name AS category_name
     FROM slides
     INNER JOIN categories ON categories.id = slides.category_id
     ORDER BY categories.name ASC, slides.display_order ASC, slides.id DESC'
);
$slides = $stmt->fetchAll();

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Slides</h1>
    <a href="<?= e(base_url('admin/slides/create.php')) ?>" class="btn btn-primary">Create Slide</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Category</th>
                <th>Order</th>
                <th>Created</th>
                <th class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!$slides): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">No slides found.</td>
                </tr>
            <?php endif; ?>
            <?php foreach ($slides as $slide): ?>
                <tr>
                    <td>
                        <?php if ($slide['image']): ?>
                            <img src="<?= e(base_url($slide['image'])) ?>" alt="<?= e($slide['title']) ?>" class="slide-thumb">
                        <?php else: ?>
                            <span class="text-muted">No image</span>
                        <?php endif; ?>
                    </td>
                    <td><?= e($slide['title']) ?></td>
                    <td><?= e($slide['category_name']) ?></td>
                    <td><?= (int) $slide['display_order'] ?></td>
                    <td><?= e($slide['created_at']) ?></td>
                    <td class="text-end">
                        <a href="<?= e(base_url('admin/slides/edit.php?id=' . (int) $slide['id'])) ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                        <form method="post" action="<?= e(base_url('admin/slides/delete.php')) ?>" class="d-inline">
                            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                            <input type="hidden" name="id" value="<?= (int) $slide['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger" data-confirm="Delete this slide?">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
