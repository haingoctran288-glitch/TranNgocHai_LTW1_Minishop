<?php
require_once "../../../dao/CustomerDAO.php";
$dao = new CustomerDAO();
$id = $_GET['id'] ?? 0;
$c = $dao->findById($id);
if (!$c) { echo "Không tìm thấy"; exit; }
$errors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST["fullname"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $address = trim($_POST["address"] ?? "");
    if ($fullname == "") $errors[] = "Họ tên không được rỗng.";
    if (empty($errors)) {
        $c->fullname = $fullname; $c->email = $email; $c->phone = $phone; $c->address = $address;
        if ($dao->update($c)) { header("Location: index.php"); exit; }
        else $errors[] = "Cập nhật thất bại!";
    }
}
$pageTitle = "Cập nhật khách hàng"; ob_start();
?>
<div class="container mt-4"><div class="card shadow-sm border-0"><div class="card-body">
    <h4 class="fw-bold text-secondary mb-4">Cập nhật khách hàng</h4>
    <?php if(!empty($errors)){ ?><div class="alert alert-danger"><ul><?php foreach($errors as $e) echo "<li>$e</li>"; ?></ul></div><?php } ?>
    <form method="POST">
        <div class="mb-3"><label>Họ tên</label><input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($c->fullname) ?>"></div>
        <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" value="<?= htmlspecialchars($c->email) ?>"></div>
        <div class="mb-3"><label>SĐT</label><input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($c->phone) ?>"></div>
        <div class="mb-3"><label>Địa chỉ</label><input type="text" name="address" class="form-control" value="<?= htmlspecialchars($c->address) ?>"></div>
        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="index.php" class="btn btn-secondary">Quay lại</a>
    </form>
</div></div></div>
<?php $content = ob_get_clean(); include "../layouts/master.php"; ?>
