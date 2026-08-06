<?php
require_once "../../../dao/BrandDAO.php";
$dao = new BrandDAO();
$id = $_GET['id'] ?? 0;
$brand = $dao->findById($id);

if (!$brand) { echo "Không tìm thấy"; exit; }
$pageTitle = "Chi tiết thương hiệu"; ob_start();
?>
<div class="container mt-4"><div class="card shadow-sm border-0"><div class="card-body">
    <h4 class="fw-bold text-secondary mb-4">Chi tiết thương hiệu #<?= $brand->id ?></h4>
    <table class="table table-bordered">
        <tr><th width="20%">ID</th><td><?= $brand->id ?></td></tr>
        <tr><th>Tên thương hiệu</th><td class="text-primary fw-bold"><?= htmlspecialchars($brand->brandname) ?></td></tr>
        <tr><th>Trạng thái</th><td><?= $brand->status == 1 ? "Hiển thị" : "Ẩn" ?></td></tr>
        <tr><th>Ngày tạo</th><td><?= $brand->createdAt ?></td></tr>
    </table>
    <a href="index.php" class="btn btn-secondary mt-3">Quay lại</a>
</div></div></div>
<?php $content = ob_get_clean(); include "../layouts/master.php"; ?>
