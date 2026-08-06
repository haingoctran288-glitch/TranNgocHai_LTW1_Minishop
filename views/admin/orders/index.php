<?php
require_once "../../../dao/OrderDAO.php";
$dao = new OrderDAO();

$keyword = trim($_GET["keyword"] ?? "");
$orders = $dao->getAll($keyword);

function getStatusBadge($status) {
    switch($status) {
        case 0: return '<span class="badge bg-warning text-dark">Chờ xác nhận</span>';
        case 1: return '<span class="badge bg-info text-dark">Đã xác nhận</span>';
        case 2: return '<span class="badge bg-primary">Đang giao</span>';
        case 3: return '<span class="badge bg-success">Hoàn thành</span>';
        case 4: return '<span class="badge bg-danger">Đã hủy</span>';
        default: return '<span class="badge bg-secondary">Không xác định</span>';
    }
}

$pageTitle = "Danh sách đơn hàng";
ob_start();
?>
<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-0 pt-4 pb-0">
        <h5 class="fw-bold text-secondary">Danh sách đơn hàng</h5>
    </div>
    <div class="card-body">
        <form class="row mb-3" method="GET">
            <div class="col-md-4">
                <input type="text" name="keyword" class="form-control" placeholder="Nhập mã đơn, tên khách hàng..." value="<?= htmlspecialchars($keyword) ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            </div>
        </form>

        <?php if(empty($orders)) { ?>
            <div class="alert alert-warning">Không tìm thấy dữ liệu.</div>
        <?php } else { ?>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Khách hàng</th>
                        <th>Nhân viên</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Chức năng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $item) { ?>
                    <tr>
                        <td class="fw-bold text-success"><?= htmlspecialchars($item->orderCode) ?></td>
                        <td><?= htmlspecialchars($item->customerName) ?></td>
                        <td><?= htmlspecialchars($item->userName ?? 'Chưa phân công') ?></td>
                        <td><?= date('d/m/Y', strtotime($item->createdAt)) ?></td>
                        <td class="text-danger fw-bold"><?= number_format($item->totalAmount, 0, ',', '.') ?>đ</td>
                        <td><?= getStatusBadge($item->status) ?></td>
                        <td>
                            <a href="detail.php?id=<?= $item->id ?>" class="btn btn-sm btn-info text-white"><i class="fa-solid fa-eye"></i></a>
                            <a href="edit.php?id=<?= $item->id ?>" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen-to-square"></i> Cập nhật</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
</div>
<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>
