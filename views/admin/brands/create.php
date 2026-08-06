<?php
require_once "../../../dao/BrandDAO.php";
$dao = new BrandDAO();
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $brandname = trim($_POST["brandname"] ?? "");
    $status = $_POST["status"] ?? 1;

    if ($brandname == "") $errors[] = "Tên thương hiệu không được để trống.";

    if (empty($errors)) {
        $brand = new Brand($brandname, "", null, null, (int)$status);
        if ($dao->insert($brand)) {
            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Thêm thất bại!";
        }
    }
}
$pageTitle = "Thêm thương hiệu";
ob_start();
?>
<div class="container mt-4"><div class="card shadow-sm border-0"><div class="card-body">
    <h4 class="fw-bold text-secondary mb-4">Thêm mới thương hiệu</h4>
    <?php if (!empty($errors)) { ?><div class="alert alert-danger"><ul><?php foreach($errors as $err) echo "<li>$err</li>"; ?></ul></div><?php } ?>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Tên thương hiệu</label>
            <input type="text" name="brandname" class="form-control" value="<?= htmlspecialchars($_POST['brandname'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label class="form-label d-block">Trạng thái</label>
            <input type="radio" name="status" value="1" <?= (!isset($_POST['status']) || $_POST['status'] == 1) ? 'checked' : '' ?>> Hiển thị 
            <input type="radio" name="status" value="0" <?= (isset($_POST['status']) && $_POST['status'] == 0) ? 'checked' : '' ?>> Ẩn
        </div>
        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="index.php" class="btn btn-secondary">Quay lại</a>
    </form>
</div></div></div>
<?php $content = ob_get_clean(); include "../layouts/master.php"; ?>
