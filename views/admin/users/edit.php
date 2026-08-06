<?php
require_once "../../../dao/UserDAO.php";
$dao = new UserDAO();
$id = $_GET['id'] ?? 0;
$u = $dao->findById($id);
if (!$u) { echo "Không tìm thấy"; exit; }
$errors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST["fullname"] ?? "");
    $role = $_POST["role"] ?? 0;
    if ($fullname == "") $errors[] = "Họ tên không được rỗng.";
    if (empty($errors)) {
        $u->fullname = $fullname; $u->role = (int)$role;
        if ($dao->update($u)) { header("Location: index.php"); exit; }
        else $errors[] = "Cập nhật thất bại!";
    }
}
$pageTitle = "Cập nhật người dùng"; ob_start();
?>
<div class="container mt-4"><div class="card shadow-sm border-0"><div class="card-body">
    <h4 class="fw-bold text-secondary mb-4">Cập nhật người dùng</h4>
    <?php if(!empty($errors)){ ?><div class="alert alert-danger"><ul><?php foreach($errors as $e) echo "<li>$e</li>"; ?></ul></div><?php } ?>
    <form method="POST">
        <div class="mb-3"><label>Họ tên</label><input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($u->fullname) ?>"></div>
        <div class="mb-3">
            <label>Vai trò</label>
            <select name="role" class="form-select">
                <option value="1" <?= $u->role == 1 ? 'selected' : '' ?>>Admin</option>
                <option value="0" <?= $u->role == 0 ? 'selected' : '' ?>>Nhân viên</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="index.php" class="btn btn-secondary">Quay lại</a>
    </form>
</div></div></div>
<?php $content = ob_get_clean(); include "../layouts/master.php"; ?>
