<?php
require_once "../../../dao/OrderDAO.php";
$dao = new OrderDAO();

$id = $_GET['id'] ?? 0;
$order = $dao->findById($id);

if (!$order) {
    echo "Không tìm thấy đơn hàng";
    exit;
}

$orderDetails = $dao->getOrderDetails($id);

function getStatusText($status) {
    switch($status) {
        case 0: return 'Chờ xác nhận';
        case 1: return 'Đã xác nhận';
        case 2: return 'Đang giao';
        case 3: return 'Hoàn thành';
        case 4: return 'Đã hủy';
        default: return 'Không xác định';
    }
}

$pageTitle = "Chi tiết đơn hàng";
ob_start();
?>
<div class="container mt-4">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-0 pt-4 pb-0">
            <h4 class="fw-bold text-secondary">Thông tin đơn hàng #<?= htmlspecialchars($order->orderCode) ?></h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr><th width="25%">Mã hệ thống</th><td><?= $order->id ?></td></tr>
                <tr><th>Mã đơn</th><td class="text-success fw-bold"><?= htmlspecialchars($order->orderCode) ?></td></tr>
                <tr><th>Mã Khách hàng</th><td><?= $order->customerId ?></td></tr>
                <tr><th>Ngày đặt</th><td><?= date('d/m/Y H:i:s', strtotime($order->createdAt)) ?></td></tr>
                <tr><th>Ghi chú</th><td><?= nl2br(htmlspecialchars($order->note)) ?></td></tr>
                <tr><th>Trạng thái</th><td class="fw-bold text-primary"><?= getStatusText($order->status) ?></td></tr>
            </table>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 pt-4 pb-0">
            <h5 class="fw-bold text-secondary">Danh sách sản phẩm</h5>
        </div>
        <div class="card-body">
            <table class="table table-hover table-bordered text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>STT</th>
                        <th>Tên sản phẩm</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $stt = 1;
                    foreach ($orderDetails as $item) { 
                    ?>
                    <tr>
                        <td><?= $stt++ ?></td>
                        <td class="text-start fw-bold"><?= htmlspecialchars($item->productName ?? "Sản phẩm #".$item->productId) ?></td>
                        <td><?= number_format($item->price, 0, ',', '.') ?>đ</td>
                        <td><?= $item->quantity ?></td>
                        <td class="text-danger fw-bold"><?= number_format($item->subtotal, 0, ',', '.') ?>đ</td>
                    </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end fw-bold">Tổng cộng:</td>
                        <td class="text-danger fw-bold fs-5"><?= number_format($order->totalAmount, 0, ',', '.') ?>đ</td>
                    </tr>
                </tfoot>
            </table>
            <a href="index.php" class="btn btn-secondary mt-3">Quay lại</a>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>
