<?php
require_once "../../../dao/OrderDAO.php";
$dao = new OrderDAO();

$id = $_GET['id'] ?? 0;
$order = $dao->findById($id);

if (!$order) {
    echo "Không tìm thấy đơn hàng";
    exit;
}

$errors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = (int)($_POST["status"] ?? 0);
    
    if ($dao->updateStatus($order->id, $status)) {
        header("Location: index.php");
        exit;
    } else {
        $errors[] = "Cập nhật trạng thái thất bại!";
    }
}

$pageTitle = "Cập nhật trạng thái đơn hàng";
ob_start();
?>
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 pt-4 pb-0">
            <h4 class="fw-bold text-secondary">Cập nhật trạng thái đơn hàng: <?= htmlspecialchars($order->orderCode) ?></h4>
        </div>
        <div class="card-body">
            <?php if (!empty($errors)) { ?>
                <div class="alert alert-danger"><ul><?php foreach($errors as $err) echo "<li>$err</li>"; ?></ul></div>
            <?php } ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold">Trạng thái mới</label>
                    <select name="status" class="form-select w-50">
                        <option value="0" <?= $order->status == 0 ? 'selected' : '' ?>>Chờ xác nhận</option>
                        <option value="1" <?= $order->status == 1 ? 'selected' : '' ?>>Đã xác nhận</option>
                        <option value="2" <?= $order->status == 2 ? 'selected' : '' ?>>Đang giao</option>
                        <option value="3" <?= $order->status == 3 ? 'selected' : '' ?>>Hoàn thành</option>
                        <option value="4" <?= $order->status == 4 ? 'selected' : '' ?>>Đã hủy</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                <a href="index.php" class="btn btn-secondary">Quay lại</a>
            </form>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>
