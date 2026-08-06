<?php
require_once "../../../dao/CustomerDAO.php";
$dao = new CustomerDAO();
$id = $_GET['id'] ?? 0;
$c = $dao->findById($id);
if (!$c) { echo "Không tìm thấy"; exit; }
$pageTitle = "Chi tiết khách hàng"; ob_start();
?>
<div class="container mt-4"><div class="card shadow-sm border-0"><div class="card-body">
    <h4 class="fw-bold text-secondary mb-4">Chi tiết khách hàng #<?= $c->id ?></h4>
    <table class="table table-bordered">
        <tr><th width="20%">Họ tên</th><td class="text-primary fw-bold"><?= htmlspecialchars($c->fullname) ?></td></tr>
        <tr><th>Email</th><td><?= htmlspecialchars($c->email) ?></td></tr>
        <tr><th>SĐT</th><td><?= htmlspecialchars($c->phone) ?></td></tr>
        <tr><th>Địa chỉ</th><td><?= htmlspecialchars($c->address) ?></td></tr>
        <tr><th>Ngày tạo</th><td><?= $c->createdAt ?></td></tr>
    </table>
    <a href="index.php" class="btn btn-secondary mt-3">Quay lại</a>
</div></div></div>
<?php $content = ob_get_clean(); include "../layouts/master.php"; ?>
