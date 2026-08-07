<?php
require_once "../../../dao/ProductDAO.php";
require_once "../../../dao/CategoryDAO.php";
require_once "../../../dao/BrandDAO.php";

$dao = new ProductDAO();
$catDao = new CategoryDAO();
$brandDao = new BrandDAO();

$categories = $catDao->getAll();
$brands = $brandDao->getAll();
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = trim($_POST["productName"] ?? "");
    $slug = trim($_POST["slug"] ?? "");
    $categoryId = (int) ($_POST["categoryId"] ?? 0);
    $brandId = (int) ($_POST["brandId"] ?? 0);
    $price = (float) ($_POST["price"] ?? 0);
    $discountPrice = (float) ($_POST["discountPrice"] ?? 0);
    $quantity = (int) ($_POST["quantity"] ?? 0);
    $description = trim($_POST["description"] ?? "");
    $status = (int) ($_POST["status"] ?? 1);

    $fileName = $_FILES["image"]["name"] ?? "";
    $tmpName = $_FILES["image"]["tmp_name"] ?? "";
    $fileSize = $_FILES["image"]["size"] ?? 0;
    $errorUpload = $_FILES["image"]["error"] ?? 0;
    $image = "";

    if ($productName == "")
        $errors[] = "Tên sản phẩm không được để trống.";
    if ($slug == "")
        $errors[] = "Slug không được để trống.";
    if ($categoryId == 0)
        $errors[] = "Vui lòng chọn danh mục.";
    if ($brandId == 0)
        $errors[] = "Vui lòng chọn thương hiệu.";
    if ($price <= 0)
        $errors[] = "Giá bán phải lớn hơn 0.";
    if ($quantity < 0)
        $errors[] = "Số lượng không hợp lệ.";

    if ($fileName != "") {
        if ($errorUpload != UPLOAD_ERR_OK) {
            $errors[] = "Upload hình ảnh không thành công.";
        }
        $allowExtensions = ["jpg", "jpeg", "png", "gif", "webp"];
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if (!in_array($extension, $allowExtensions)) {
            $errors[] = "Chỉ cho phép file JPG, JPEG, PNG hoặc WEBP.";
        }
        $maxSize = 200 * 1024; // 200 KB
        if ($fileSize > $maxSize) {
            $errors[] = "Kích thước hình ảnh <= 200 KB.";
        }
    }

    if (empty($errors)) {
        if ($fileName != "") {
            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $image = time() . "_" . $slug . "." . $extension;
            $uploadPathDir = __DIR__ . "/../../../uploads/products";
            if (!is_dir($uploadPathDir))
                mkdir($uploadPathDir, 0777, true);
            $uploadPath = $uploadPathDir . "/" . $image;
            move_uploaded_file($tmpName, $uploadPath);
        }

        $p = new Product($categoryId, $brandId, $productName, $slug, $price, $discountPrice, $quantity, $image, $description, $status);
        if ($dao->insert($p)) {
            $lastInsertId = $dao->getConnection()->insert_id;

            // Upload ảnh
            if (!empty($_FILES["images"]["name"][0])) {
                foreach ($_FILES["images"]["name"] as $key => $name) {
                    if ($_FILES["images"]["error"][$key] == UPLOAD_ERR_OK) {
                        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                        if (in_array($ext, ["jpg", "jpeg", "png", "gif", "webp"]) && $_FILES["images"]["size"][$key] <= 200 * 1024) {
                            $galImage = time() . "_" . $key . "_" . $slug . "." . $ext;
                            $galPath = __DIR__ . "/../../../uploads/products/" . $galImage;
                            if (move_uploaded_file($_FILES["images"]["tmp_name"][$key], $galPath)) {
                                $dao->insertImage($lastInsertId, $galImage);
                            }
                        }
                    }
                }
            }

            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Thêm thất bại!";
        }
    }
}

$pageTitle = "Thêm sản phẩm";
ob_start();
?>
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 pt-4 pb-0">
            <h4 class="fw-bold text-secondary">Thêm mới sản phẩm</h4>
        </div>
        <div class="card-body">
            <?php if (!empty($errors)) { ?>
                <div class="alert alert-danger">
                    <ul><?php foreach ($errors as $err)
                        echo "<li>$err</li>"; ?></ul>
                </div>
            <?php } ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Tên sản phẩm</label>
                        <input type="text" name="productName" class="form-control"
                            value="<?= htmlspecialchars($_POST['productName'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control"
                            value="<?= htmlspecialchars($_POST['slug'] ?? '') ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Danh mục</label>
                        <select name="categoryId" class="form-select">
                            <option value="0">-- Chọn danh mục --</option>
                            <?php foreach ($categories as $item) { ?>
                                <option value="<?= $item->id ?>" <?= (isset($_POST['categoryId']) && $_POST['categoryId'] == $item->id) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($item->name) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Thương hiệu</label>
                        <select name="brandId" class="form-select">
                            <option value="0">-- Chọn thương hiệu --</option>
                            <?php foreach ($brands as $item) { ?>
                                <option value="<?= $item->id ?>" <?= (isset($_POST['brandId']) && $_POST['brandId'] == $item->id) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($item->brandname) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="text-center mb-3" id="preview"></div>
                        <label class="form-label"> Hình ảnh đại diện</label>
                        <input type="file" id="image" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-6">
                        <div class="text-center mb-3" id="preview_images"></div>
                        <label class="form-label"> Hình ảnh Gallery (Nhiều hình)</label>
                        <input type="file" id="images" name="images[]" class="form-control" accept="image/*" multiple>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Giá gốc</label>
                        <input type="number" name="price" class="form-control"
                            value="<?= htmlspecialchars($_POST['price'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Giá giảm (nếu có)</label>
                        <input type="number" name="discountPrice" class="form-control"
                            value="<?= htmlspecialchars($_POST['discountPrice'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Số lượng kho</label>
                        <input type="number" name="quantity" class="form-control"
                            value="<?= htmlspecialchars($_POST['quantity'] ?? '') ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" rows="4"
                        class="form-control"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label d-block">Trạng thái</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" value="1"
                            <?= (!isset($_POST['status']) || $_POST['status'] == 1) ? 'checked' : '' ?>>
                        <label class="form-check-label">Hiển thị</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" value="0" <?= (isset($_POST['status']) && $_POST['status'] == 0) ? 'checked' : '' ?>>
                        <label class="form-check-label">Ẩn</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Lưu</button>
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