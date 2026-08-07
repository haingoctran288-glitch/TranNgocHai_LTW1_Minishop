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

    $fileName = $_FILES["image"]["name"] ?? "";
    $tmpName = $_FILES["image"]["tmp_name"] ?? "";
    $fileSize = $_FILES["image"]["size"] ?? 0;
    $errorUpload = $_FILES["image"]["error"] ?? 0;
    $image = $category->image;

    if ($cateName == "") $errors[] = "Tên danh mục không được để trống.";
    if ($slug == "") $errors[] = "Slug không được để trống.";

    if ($fileName != "") {
        if ($errorUpload != UPLOAD_ERR_OK) {
            $errors[] = "Upload hình ảnh không thành công.";
        }
        $allowExtensions = ["jpg", "jpeg", "png", "gif", "webp"];
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if (!in_array($extension, $allowExtensions)) {
            $errors[] = "Chỉ cho phép file JPG, JPEG, PNG hoặc WEBP.";
        }
        if ($fileSize > 200 * 1024) {
            $errors[] = "Kích thước hình ảnh <= 200 KB.";
        }
    }

    if (empty($errors)) {
        if ($fileName != "") {
            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $image = time() . "_" . $slug . "." . $extension;
            $uploadPathDir = __DIR__ . "/../../../uploads/categories";
            if (!is_dir($uploadPathDir)) mkdir($uploadPathDir, 0777, true);
            $uploadPath = $uploadPathDir . "/" . $image;
            
            if (!empty($category->image)) {
                $oldImage = __DIR__ . "/../../../uploads/categories/" . $category->image;
                if (file_exists($oldImage)) unlink($oldImage);
            }
            if (!is_dir(dirname($uploadPath))) mkdir(dirname($uploadPath), 0777, true);
            move_uploaded_file($tmpName, $uploadPath);
        }

        $category->name = $cateName;
        $category->slug = $slug;
        $category->description = $description;
        $category->status = (int)$status;
        $category->image = $image;
        
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
            <form method="POST" enctype="multipart/form-data">
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
                    <label class="form-label d-block">Hình ảnh hiện tại</label>
                    <?php if($category->image){ ?>
                        <img src="/MiniShop_TranNgocHai/uploads/categories/<?= $category->image ?>" class="img-thumbnail mb-2" width="150" id="preview">
                    <?php } else { ?>
                        <div class="text-center mb-3" id="preview"></div>
                    <?php } ?>
                    <label class="form-label mt-2"> Chọn hình ảnh mới</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
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
