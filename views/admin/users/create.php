<?php
require_once "../../../dao/UserDAO.php";
$dao = new UserDAO();
$errors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST["fullname"] ?? "");
    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");
    $role = $_POST["role"] ?? 0;
    $status = $_POST["status"] ?? 1;

    if ($fullname == "") $errors[] = "Họ tên không được rỗng.";
    if ($username == "") $errors[] = "Tên đăng nhập không được rỗng.";
    if (empty($errors)) {
        $u = new User($fullname, $username, $password, "", "", "", (int)$role, (int)$status);
        if ($dao->insert($u)) { header("Location: index.php"); exit; }
        else $errors[] = "Thêm thất bại!";
    }
}
$pageTitle = "Thêm quản trị viên"; ob_start();
?>
<div class="container mt-4"><div class="card shadow-sm border-0"><div class="card-body">
    <h4 class="fw-bold text-secondary mb-4">Thêm người dùng</h4>
    <?php if(!empty($errors)){ ?><div class="alert alert-danger"><ul><?php foreach($errors as $e) echo "<li>$e</li>"; ?></ul></div><?php } ?>
    <form method="POST">
        <div class="mb-3"><label>Họ tên</label><input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>"></div>
        <div class="mb-3"><label>Tên đăng nhập</label><input type="text" name="username" class="form-control" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"></div>
        <div class="mb-3"><label>Mật khẩu</label><input type="password" name="password" class="form-control"></div>
        <div class="mb-3">
            <label>Vai trò</label>
            <select name="role" class="form-select">
                <option value="1">Admin</option>
                <option value="0">Nhân viên</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="index.php" class="btn btn-secondary">Quay lại</a>
    </form>
</div></div></div>
<?php $content = ob_get_clean(); include "../layouts/master.php"; ?>
