<?php
require_once "../../../dao/CustomerDAO.php";
$dao = new CustomerDAO();
$errors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST["fullname"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $address = trim($_POST["address"] ?? "");
    if ($fullname == "") $errors[] = "Họ tên không được rỗng.";
    if (empty($errors)) {
        $c = new Customer($fullname, $phone, $email, $address, null);
        if ($dao->insert($c)) { header("Location: index.php"); exit; }
        else $errors[] = "Thêm thất bại!";
    }
}
$pageTitle = "Thêm khách hàng"; ob_start();
?>
<div class="container mt-4"><div class="card shadow-sm border-0"><div class="card-body">
    <h4 class="fw-bold text-secondary mb-4">Thêm khách hàng</h4>
    <?php if(!empty($errors)){ ?><div class="alert alert-danger"><ul><?php foreach($errors as $e) echo "<li>$e</li>"; ?></ul></div><?php } ?>
    <form method="POST">
        <div class="mb-3"><label>Họ tên</label><input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>"></div>
        <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"></div>
        <div class="mb-3"><label>SĐT</label><input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"></div>
        <div class="mb-3"><label>Địa chỉ</label><input type="text" name="address" class="form-control" value="<?= htmlspecialchars($_POST['address'] ?? '') ?>"></div>
        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="index.php" class="btn btn-secondary">Quay lại</a>
    </form>
</div></div></div>
<?php $content = ob_get_clean(); include "../layouts/master.php"; ?>
