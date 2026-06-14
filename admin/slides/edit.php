<?php
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/upload.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    set_flash('danger', 'Invalid slide ID.');
    redirect('admin/slides/index.php');
}

$slide = find_slide($id);

if (!$slide) {
    set_flash('danger', 'Slide not found.');
    redirect('admin/slides/index.php');
}

$pageTitle = 'Edit Slide';
$categories = get_all_categories();
$errors = [];
$categoryId = (int) $slide['category_id'];
$title = $slide['title'];
$description = $slide['description'];
$displayOrder = (string) $slide['display_order'];
$currentImage = $slide['image'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_valid_csrf('admin/slides/edit.php?id=' . $id);

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

    $imagePath = $upload['path'] ?: $currentImage;

    if (!$errors) {
        $stmt = db()->prepare(
            'UPDATE slides
             SET category_id = :category_id,
                 title = :title,
                 description = :description,
                 image = :image,
                 display_order = :display_order
             WHERE id = :id'
        );
        $stmt->execute([
            'category_id' => (int) $categoryId,
            'title' => $title,
            'description' => $description,
            'image' => $imagePath,
            'display_order' => (int) $displayOrderValue,
            'id' => $id,
        ]);

        if ($upload['path'] && $currentImage) {
            delete_uploaded_file($currentImage);
        }

        set_flash('success', 'Slide updated successfully.');
        redirect('admin/slides/index.php');
    } elseif ($upload['path']) {
        delete_uploaded_file($upload['path']);
    }
}

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Edit Slide</h1>
    <a href="<?= e(base_url('admin/slides/index.php')) ?>" class="btn btn-outline-secondary">Back</a>
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
                <?php if ($currentImage): ?>
                    <div class="mb-2">
                        <img src="<?= e(base_url($currentImage)) ?>" alt="<?= e($title) ?>" class="slide-thumb">
                    </div>
                <?php endif; ?>
                <input type="file" class="form-control" id="image" name="image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
            </div>
            <div class="mb-3">
                <label for="display_order" class="form-label">Display Order</label>
                <input type="number" class="form-control" id="display_order" name="display_order" value="<?= e($displayOrder) ?>">
            </div>
            <button type="submit" class="btn btn-primary">Update Slide</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
