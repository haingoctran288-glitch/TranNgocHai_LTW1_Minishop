<?php
require_once "../../../dao/BrandDAO.php";
$dao = new BrandDAO();
$id = $_GET['id'] ?? 0;
$brand = $dao->findById($id);

if (!$brand) { echo "Không tìm thấy"; exit; }
$errors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $brandname = trim($_POST["brandname"] ?? "");
    $status = $_POST["status"] ?? 1;

    if ($brandname == "") $errors[] = "Tên không được để trống.";
    if (empty($errors)) {
        $brand->brandname = $brandname;
        $brand->status = (int)$status;
        if ($dao->update($brand)) { header("Location: index.php"); exit; }
        else $errors[] = "Cập nhật thất bại!";
    }
}
$pageTitle = "Cập nhật thương hiệu"; ob_start();
?>
<div class="container mt-4"><div class="card shadow-sm border-0"><div class="card-body">
    <h4 class="fw-bold text-secondary mb-4">Cập nhật thương hiệu</h4>
    <?php if(!empty($errors)){ ?><div class="alert alert-danger"><ul><?php foreach($errors as $e) echo "<li>$e</li>"; ?></ul></div><?php } ?>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $brand->id ?>">
        <div class="mb-3">
            <label class="form-label">Tên thương hiệu</label>
            <input type="text" name="brandname" class="form-control" value="<?= htmlspecialchars($brand->brandname) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label d-block">Trạng thái</label>
            <input type="radio" name="status" value="1" <?= $brand->status == 1 ? "checked" : "" ?>> Hiển thị 
            <input type="radio" name="status" value="0" <?= $brand->status == 0 ? "checked" : "" ?>> Ẩn
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="index.php" class="btn btn-secondary">Quay lại</a>
    </form>
</div></div></div>
<?php $content = ob_get_clean(); include "../layouts/master.php"; ?>
