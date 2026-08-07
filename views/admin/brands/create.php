<?php
require_once "../../../dao/BrandDAO.php";
$dao = new BrandDAO();
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $brandname = trim($_POST["brandname"] ?? "");
    $status = $_POST["status"] ?? 1;

    $fileName = $_FILES["image"]["name"] ?? "";
    $tmpName = $_FILES["image"]["tmp_name"] ?? "";
    $fileSize = $_FILES["image"]["size"] ?? 0;
    $errorUpload = $_FILES["image"]["error"] ?? 0;
    $image = null;

    if ($brandname == "") $errors[] = "Tên thương hiệu không được để trống.";

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
            $image = time() . "_" . str_replace(" ", "-", strtolower($brandname)) . "." . $extension;
            $uploadPathDir = __DIR__ . "/../../../uploads/brands";
            if (!is_dir($uploadPathDir)) mkdir($uploadPathDir, 0777, true);
            $uploadPath = $uploadPathDir . "/" . $image;
            move_uploaded_file($tmpName, $uploadPath);
        }

        $brand = new Brand($brandname, "", $image, null, (int)$status);
        if ($dao->insert($brand)) {
            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Thêm thất bại!";
        }
    }
}
$pageTitle = "Thêm thương hiệu";
ob_start();
?>
<div class="container mt-4"><div class="card shadow-sm border-0"><div class="card-body">
    <h4 class="fw-bold text-secondary mb-4">Thêm mới thương hiệu</h4>
    <?php if (!empty($errors)) { ?><div class="alert alert-danger"><ul><?php foreach($errors as $err) echo "<li>$err</li>"; ?></ul></div><?php } ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Tên thương hiệu</label>
            <input type="text" name="brandname" class="form-control" value="<?= htmlspecialchars($_POST['brandname'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <div class="text-center mb-3" id="preview"></div>
            <label class="form-label"> Hình ảnh thương hiệu</label>
            <input type="file" id="image" name="image" class="form-control" accept="image/*">
        </div>
        <div class="mb-3">
            <label class="form-label d-block">Trạng thái</label>
            <input type="radio" name="status" value="1" <?= (!isset($_POST['status']) || $_POST['status'] == 1) ? 'checked' : '' ?>> Hiển thị 
            <input type="radio" name="status" value="0" <?= (isset($_POST['status']) && $_POST['status'] == 0) ? 'checked' : '' ?>> Ẩn
        </div>
        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="index.php" class="btn btn-secondary">Quay lại</a>
    </form>
</div></div></div>
<?php $content = ob_get_clean(); include "../layouts/master.php"; ?>
