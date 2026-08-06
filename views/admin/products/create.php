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
    $categoryId = (int)($_POST["categoryId"] ?? 0);
    $brandId = (int)($_POST["brandId"] ?? 0);
    $price = (float)($_POST["price"] ?? 0);
    $discountPrice = (float)($_POST["discountPrice"] ?? 0);
    $quantity = (int)($_POST["quantity"] ?? 0);
    $description = trim($_POST["description"] ?? "");
    $status = (int)($_POST["status"] ?? 1);

    if($productName == "") $errors[] = "Tên sản phẩm không được để trống.";
    if($slug == "") $errors[] = "Slug không được để trống.";
    if($categoryId == 0) $errors[] = "Vui lòng chọn danh mục.";
    if($brandId == 0) $errors[] = "Vui lòng chọn thương hiệu.";
    if($price <= 0) $errors[] = "Giá bán phải lớn hơn 0.";
    if($quantity < 0) $errors[] = "Số lượng không hợp lệ.";

    if (empty($errors)) {
        $p = new Product($categoryId, $brandId, $productName, $slug, $price, $discountPrice, $quantity, null, $description, $status);
        if ($dao->insert($p)) {
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
                <div class="alert alert-danger"><ul><?php foreach($errors as $err) echo "<li>$err</li>"; ?></ul></div>
            <?php } ?>
            <form method="POST">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Tên sản phẩm</label>
                        <input type="text" name="productName" class="form-control" value="<?= htmlspecialchars($_POST['productName'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($_POST['slug'] ?? '') ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Danh mục</label>
                        <select name="categoryId" class="form-select">
                            <option value="0">-- Chọn danh mục --</option>
                            <?php foreach($categories as $item) { ?>
                                <option value="<?= $item->id ?>" <?= (isset($_POST['categoryId']) && $_POST['categoryId'] == $item->id) ? 'selected' : '' ?>><?= $item->name ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Thương hiệu</label>
                        <select name="brandId" class="form-select">
                            <option value="0">-- Chọn thương hiệu --</option>
                            <?php foreach($brands as $item) { ?>
                                <option value="<?= $item->id ?>" <?= (isset($_POST['brandId']) && $_POST['brandId'] == $item->id) ? 'selected' : '' ?>><?= $item->brandname ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Giá gốc</label>
                        <input type="number" name="price" class="form-control" value="<?= htmlspecialchars($_POST['price'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Giá giảm (nếu có)</label>
                        <input type="number" name="discountPrice" class="form-control" value="<?= htmlspecialchars($_POST['discountPrice'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Số lượng kho</label>
                        <input type="number" name="quantity" class="form-control" value="<?= htmlspecialchars($_POST['quantity'] ?? '') ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" rows="4" class="form-control"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label d-block">Trạng thái</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" value="1" <?= (!isset($_POST['status']) || $_POST['status'] == 1) ? 'checked' : '' ?>>
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
