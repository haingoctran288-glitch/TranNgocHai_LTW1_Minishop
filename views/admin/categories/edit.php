<?php
require_once "../../../dao/CategoryDAO.php";
$dao = new CategoryDAO();
$id = $_GET['id'] ?? 0;
$category = $dao->findById($id);

if (!$category) {
    echo "Không tìm thấy danh mục";
    exit;
}

$errors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cateName = trim($_POST["cateName"] ?? "");
    $slug = trim($_POST["slug"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $status = $_POST["status"] ?? 1;

    if ($cateName == "") $errors[] = "Tên danh mục không được để trống.";
    if ($slug == "") $errors[] = "Slug không được để trống.";

    if (empty($errors)) {
        $category->name = $cateName;
        $category->slug = $slug;
        $category->description = $description;
        $category->status = (int)$status;
        
        if ($dao->update($category)) {
            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Cập nhật thất bại!";
        }
    }
}

$pageTitle = "Cập nhật danh mục";
ob_start();
?>
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 pt-4 pb-0">
            <h4 class="fw-bold text-secondary">Cập nhật danh mục</h4>
        </div>
        <div class="card-body">
            <?php if (!empty($errors)) { ?>
                <div class="alert alert-danger"><ul><?php foreach($errors as $err) echo "<li>$err</li>"; ?></ul></div>
            <?php } ?>
            <form method="POST">
                <input type="hidden" name="categoryId" value="<?= $category->id ?>">
                <div class="mb-3">
                    <label class="form-label">Tên danh mục</label>
                    <input type="text" name="cateName" class="form-control" value="<?= htmlspecialchars($category->name) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($category->slug) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" rows="5" class="form-control"><?= htmlspecialchars($category->description) ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label d-block">Trạng thái</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" value="1" <?= $category->status == 1 ? "checked" : "" ?>>
                        <label class="form-check-label">Hiển thị</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" value="0" <?= $category->status == 0 ? "checked" : "" ?>>
                        <label class="form-check-label">Ẩn</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <button type="reset" class="btn btn-warning">Làm mới</button>
                <a href="index.php" class="btn btn-secondary">Quay lại</a>
            </form>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>
