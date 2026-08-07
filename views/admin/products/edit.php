<?php
require_once "../../../dao/ProductDAO.php";
require_once "../../../dao/CategoryDAO.php";
require_once "../../../dao/BrandDAO.php";

$dao = new ProductDAO();
$catDao = new CategoryDAO();
$brandDao = new BrandDAO();

$id = $_GET['id'] ?? 0;
$product = $dao->findById($id);

if (!$product) {
    echo "Không tìm thấy sản phẩm";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btnDeleteGallery'])) {
    $imgId = $_POST['gallery_id'];
    $imgFile = $_POST['gallery_file'];
    $dao->deleteImage($imgId);
    $path = __DIR__ . "/../../../uploads/products/" . $imgFile;
    if (file_exists($path))
        unlink($path);
    header("Location: edit.php?id=" . $id);
    exit;
}

$categories = $catDao->getAll();
$brands = $brandDao->getAll();
$gallery = $dao->getImagesByProductId($id);
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['btnDeleteGallery'])) {
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
    $image = $product->image;

    if ($productName == "")
        $errors[] = "Tên sản phẩm không được để trống.";
    if ($slug == "")
        $errors[] = "Slug không được để trống.";

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
            $uploadPath = __DIR__ . "/../../../uploads/products/" . $image;

            if (!empty($product->image)) {
                $oldImage = __DIR__ . "/../../../uploads/products/" . $product->image;
                if (file_exists($oldImage))
                    unlink($oldImage);
            }
            if (!is_dir(dirname($uploadPath)))
                mkdir(dirname($uploadPath), 0777, true);
            move_uploaded_file($tmpName, $uploadPath);
        }

        $product->proname = $productName;
        $product->slug = $slug;
        $product->categoryId = $categoryId;
        $product->brandId = $brandId;
        $product->price = $price;
        $product->discountPrice = $discountPrice;
        $product->quantity = $quantity;
        $product->description = $description;
        $product->status = $status;
        $product->image = $image;

        if ($dao->update($product)) {
            // Upload ảnh
            if (!empty($_FILES["images"]["name"][0])) {
                foreach ($_FILES["images"]["name"] as $key => $name) {
                    if ($_FILES["images"]["error"][$key] == UPLOAD_ERR_OK) {
                        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                        if (in_array($ext, ["jpg", "jpeg", "png", "gif", "webp"]) && $_FILES["images"]["size"][$key] <= 200 * 1024) {
                            $galImage = time() . "_" . $key . "_" . $slug . "." . $ext;
                            $galPath = __DIR__ . "/../../../uploads/products/" . $galImage;
                            if (!is_dir(dirname($galPath)))
                                mkdir(dirname($galPath), 0777, true);
                            if (move_uploaded_file($_FILES["images"]["tmp_name"][$key], $galPath)) {
                                $dao->insertImage($product->id, $galImage);
                            }
                        }
                    }
                }
            }

            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Cập nhật thất bại!";
        }
    }
}

$pageTitle = "Cập nhật sản phẩm";
ob_start();
?>
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 pt-4 pb-0">
            <h4 class="fw-bold text-secondary">Cập nhật sản phẩm</h4>
        </div>
        <div class="card-body">
            <?php if (!empty($errors)) { ?>
                <div class="alert alert-danger">
                    <ul><?php foreach ($errors as $err)
                        echo "<li>$err</li>"; ?></ul>
                </div>
            <?php } ?>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $product->id ?>">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Tên sản phẩm</label>
                        <input type="text" name="productName" class="form-control"
                            value="<?= htmlspecialchars($product->proname) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control"
                            value="<?= htmlspecialchars($product->slug) ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Danh mục</label>
                        <select name="categoryId" class="form-select">
                            <option value="0">-- Chọn danh mục --</option>
                            <?php foreach ($categories as $item) { ?>
                                <option value="<?= $item->id ?>" <?= $item->id == $product->categoryId ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($item->name) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Thương hiệu</label>
                        <select name="brandId" class="form-select">
                            <option value="0">-- Chọn thương hiệu --</option>
                            <?php foreach ($brands as $item) { ?>
                                <option value="<?= $item->id ?>" <?= $item->id == $product->brandId ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($item->brandname) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label d-block">Hình ảnh hiện tại</label>
                        <?php if ($product->image) { ?>
                            <img src="/MiniShop_TranNgocHai/uploads/products/<?= $product->image ?>"
                                class="img-thumbnail mb-2" width="150" id="preview">
                        <?php } else { ?>
                            <div class="text-center mb-3" id="preview"></div>
                        <?php } ?>
                        <label class="form-label mt-2">Chọn ảnh đại diện mới</label>
                        <input type="file" id="image" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label d-block">Gallery hiện tại</label>
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            <?php foreach ($gallery as $g) { ?>
                                <div class="position-relative">
                                    <img src="/MiniShop_TranNgocHai/uploads/products/<?= $g['image'] ?>"
                                        class="img-thumbnail" width="80">
                                    <form method="POST" class="position-absolute top-0 end-0 m-0"
                                        onsubmit="return confirm('Xóa hình này?');">
                                        <input type="hidden" name="gallery_id" value="<?= $g['id'] ?>">
                                        <input type="hidden" name="gallery_file" value="<?= $g['image'] ?>">
                                        <button type="submit" name="btnDeleteGallery"
                                            class="btn btn-sm btn-danger py-0 px-1"><i
                                                class="fa-solid fa-xmark"></i></button>
                                    </form>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="text-center mb-2" id="preview_images"></div>
                        <label class="form-label mt-2">Thêm ảnh Gallery mới</label>
                        <input type="file" id="images" name="images[]" class="form-control" accept="image/*" multiple>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Giá gốc</label>
                        <input type="number" name="price" class="form-control" value="<?= $product->price ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Giá giảm (nếu có)</label>
                        <input type="number" name="discountPrice" class="form-control"
                            value="<?= $product->discountPrice ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Số lượng kho</label>
                        <input type="number" name="quantity" class="form-control" value="<?= $product->quantity ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" rows="4"
                        class="form-control"><?= htmlspecialchars($product->description) ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label d-block">Trạng thái</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" value="1" <?= $product->status == 1 ? 'checked' : '' ?>>
                        <label class="form-check-label">Hiển thị</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" value="0" <?= $product->status == 0 ? 'checked' : '' ?>>
                        <label class="form-check-label">Ẩn</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="index.php" class="btn btn-secondary">Quay lại</a>
            </form>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>