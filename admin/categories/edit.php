<?php
require_once __DIR__ . '/../../includes/functions.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    set_flash('danger', 'Invalid category ID.');
    redirect('admin/categories/index.php');
}

$category = find_category($id);

if (!$category) {
    set_flash('danger', 'Category not found.');
    redirect('admin/categories/index.php');
}

$pageTitle = 'Edit Category';
$errors = [];
$name = $category['name'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_valid_csrf('admin/categories/edit.php?id=' . $id);

    $name = trim((string) post_value('name'));
    $error = validate_required_string($name, 'Category name');

    if ($error) {
        $errors[] = $error;
    }

    if (!$errors) {
        $stmt = db()->prepare('UPDATE categories SET name = :name WHERE id = :id');
        $stmt->execute([
            'name' => $name,
            'id' => $id,
        ]);

        set_flash('success', 'Category updated successfully.');
        redirect('admin/categories/index.php');
    }
}

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Edit Category</h1>
    <a href="<?= e(base_url('admin/categories/index.php')) ?>" class="btn btn-outline-secondary">Back</a>
</div>

<?php if ($errors): ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $error): ?>
            <div><?= e($error) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= e($name) ?>" maxlength="255" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Category</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
