<?php
require_once __DIR__ . '/../../includes/functions.php';

$pageTitle = 'Create Category';
$errors = [];
$name = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_valid_csrf('admin/categories/create.php');

    $name = trim((string) post_value('name'));
    $error = validate_required_string($name, 'Category name');

    if ($error) {
        $errors[] = $error;
    }

    if (!$errors) {
        $stmt = db()->prepare('INSERT INTO categories (name) VALUES (:name)');
        $stmt->execute(['name' => $name]);

        set_flash('success', 'Category created successfully.');
        redirect('admin/categories/index.php');
    }
}

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Create Category</h1>
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
            <button type="submit" class="btn btn-primary">Save Category</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
