<?php
require_once "../../../dao/ProductDAO.php";
$dao = new ProductDAO();
$id = $_GET['id'] ?? 0;
$product = $dao->findById($id);

if (!$product) {
    echo "Không tìm thấy sản phẩm";
    exit;
}

$pageTitle = "Chi tiết sản phẩm";
ob_start();
?>
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 pt-4 pb-0">
            <h4 class="fw-bold text-secondary">Chi tiết sản phẩm #<?= $product->id ?></h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr><th width="20%">ID</th><td><?= $product->id ?></td></tr>
                <tr><th>Tên sản phẩm</th><td class="text-primary fw-bold"><?= htmlspecialchars($product->proname) ?></td></tr>
                <tr><th>Slug</th><td><?= htmlspecialchars($product->slug) ?></td></tr>
                <tr><th>Mã Danh mục</th><td><?= $product->categoryId ?></td></tr>
                <tr><th>Mã Thương hiệu</th><td><?= $product->brandId ?></td></tr>
                <tr><th>Giá bán</th><td class="text-danger fw-bold"><?= number_format($product->price, 0, ',', '.') ?>đ</td></tr>
                <tr><th>Giá giảm</th><td class="text-success fw-bold"><?= number_format($product->discountPrice, 0, ',', '.') ?>đ</td></tr>
                <tr><th>Số lượng kho</th><td><?= $product->quantity ?></td></tr>
                <tr><th>Mô tả</th><td><?= nl2br(htmlspecialchars($product->description)) ?></td></tr>
                <tr><th>Trạng thái</th><td><?= $product->status == 1 ? '<span class="badge bg-success">Hiển thị</span>' : '<span class="badge bg-secondary">Ẩn</span>' ?></td></tr>
                <tr><th>Ngày tạo</th><td><?= $product->createdAt ?></td></tr>
                <tr><th>Ngày cập nhật</th><td><?= $product->updatedAt ?></td></tr>
            </table>
            <a href="index.php" class="btn btn-secondary mt-3">Quay lại</a>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>
