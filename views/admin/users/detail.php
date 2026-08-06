<?php
require_once "../../../dao/UserDAO.php";
$dao = new UserDAO();
$id = $_GET['id'] ?? 0;
$u = $dao->findById($id);
if (!$u) { echo "Không tìm thấy"; exit; }
$pageTitle = "Chi tiết người dùng"; ob_start();
?>
<div class="container mt-4"><div class="card shadow-sm border-0"><div class="card-body">
    <h4 class="fw-bold text-secondary mb-4">Chi tiết người dùng #<?= $u->id ?></h4>
    <table class="table table-bordered">
        <tr><th width="20%">Họ tên</th><td class="text-primary fw-bold"><?= htmlspecialchars($u->fullname) ?></td></tr>
        <tr><th>Username</th><td><?= htmlspecialchars($u->username) ?></td></tr>
        <tr><th>Vai trò</th><td><?= $u->role == 1 ? "Admin" : "Nhân viên" ?></td></tr>
        <tr><th>Trạng thái</th><td><?= $u->status == 1 ? "Hoạt động" : "Bị khóa" ?></td></tr>
        <tr><th>Ngày tạo</th><td><?= $u->createdAt ?></td></tr>
    </table>
    <a href="index.php" class="btn btn-secondary mt-3">Quay lại</a>
</div></div></div>
<?php $content = ob_get_clean(); include "../layouts/master.php"; ?>
