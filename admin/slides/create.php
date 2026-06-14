<?php
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/upload.php';

$pageTitle = 'Create Slide';
$categories = get_all_categories();
$errors = [];
$categoryId = '';
$title = '';
$description = '';
$displayOrder = '0';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_valid_csrf('admin/slides/create.php');

    $categoryId = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);
    $title = trim((string) post_value('title'));
    $description = trim((string) post_value('description'));
    $displayOrder = (string) post_value('display_order', '0');
    $displayOrderValue = filter_input(INPUT_POST, 'display_order', FILTER_VALIDATE_INT);

    if (!$categoryId || !find_category((int) $categoryId)) {
        $errors[] = 'Please select a valid category.';
    }

    if ($title !== '' && mb_strlen($title) > 255) {
        $errors[] = 'Title may not exceed 255 characters.';
    }

    if ($displayOrderValue === false || $displayOrderValue === null) {
        $errors[] = 'Display order must be a valid number.';
    }

    $upload = handle_image_upload($_FILES['image'] ?? []);

    if ($upload['error']) {
        $errors[] = $upload['error'];
    }

    if (!$errors) {
        $stmt = db()->prepare(
            'INSERT INTO slides (category_id, title, description, image, display_order)
             VALUES (:category_id, :title, :description, :image, :display_order)'
        );
        $stmt->execute([
            'category_id' => (int) $categoryId,
            'title' => $title,
            'description' => $description,
            'image' => $upload['path'],
            'display_order' => (int) $displayOrderValue,
        ]);

        set_flash('success', 'Slide created successfully.');
        redirect('admin/slides/index.php');
    } elseif ($upload['path']) {
        delete_uploaded_file($upload['path']);
    }
}

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Create Slide</h1>
    <a href="<?= e(base_url('admin/slides/index.php')) ?>" class="btn btn-outline-secondary">Back</a>
</div>

<?php if (!$categories): ?>
    <div class="alert alert-warning">Create a category before adding slides.</div>
<?php endif; ?>

<?php if ($errors): ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $error): ?>
            <div><?= e($error) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <div class="mb-3">
                <label for="category_id" class="form-label">Category</label>
                <select class="form-select" id="category_id" name="category_id" required>
                    <option value="">Select category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= (int) $category['id'] ?>" <?= (int) $categoryId === (int) $category['id'] ? 'selected' : '' ?>>
                            <?= e($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?= e($title) ?>" maxlength="255">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4"><?= e($description) ?></textarea>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" class="form-control" id="image" name="image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
            </div>
            <div class="mb-3">
                <label for="display_order" class="form-label">Display Order</label>
                <input type="number" class="form-control" id="display_order" name="display_order" value="<?= e($displayOrder) ?>">
            </div>
            <button type="submit" class="btn btn-primary" <?= !$categories ? 'disabled' : '' ?>>Save Slide</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
