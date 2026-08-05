<?php
require_once "../../dao/BaseDAO.php";
require_once "../../dao/ProductDAO.php";
require_once "../../dao/OrderDAO.php";

$baseDAO = new BaseDAO();
$productDAO = new ProductDAO();
$orderDAO = new OrderDAO();

// Đếm tổng số
$totalCategories = $baseDAO->countTotal("categories");
$totalBrands = $baseDAO->countTotal("brands");
$totalProducts = $baseDAO->countTotal("products");
$totalCustomers = $baseDAO->countTotal("customers");
$totalOrders = $baseDAO->countTotal("orders");

// Lấy danh sách mới nhất
$latestProducts = $productDAO->getLatestProducts();
$latestOrders = $orderDAO->getLatestOrders();

$pageTitle = "Dashboard";
ob_start();
?>

<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="stat-card bg-primary shadow-sm text-center">
            <h3><?= $totalProducts ?></h3>
            <p class="m-0"><i class="fa-solid fa-box"></i> Sản phẩm</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card bg-success shadow-sm text-center">
            <h3><?= $totalOrders ?></h3>
            <p class="m-0"><i class="fa-solid fa-cart-shopping"></i> Đơn hàng</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card bg-warning shadow-sm text-center text-dark">
            <h3><?= $totalCustomers ?></h3>
            <p class="m-0"><i class="fa-solid fa-users"></i> Khách hàng</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card bg-danger shadow-sm text-center">
            <h3><?= $totalCategories ?></h3>
            <p class="m-0"><i class="fa-solid fa-list"></i> Danh mục</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 pt-4 pb-0">
                <h5 class="fw-bold text-secondary">5 Sản phẩm mới nhất</h5>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Mã</th>
                            <th>Tên SP</th>
                            <th>Giá gốc</th>
                            <th>Giá giảm</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($latestProducts as $p) { ?>
                        <tr>
                            <td>#<?= $p->id ?></td>
                            <td class="text-primary fw-bold"><?= htmlspecialchars($p->proname) ?></td>
                            <td><?= number_format($p->price, 0, ',', '.') ?>đ</td>
                            <td class="text-danger fw-bold"><?= number_format($p->discountPrice, 0, ',', '.') ?>đ</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 pt-4 pb-0">
                <h5 class="fw-bold text-secondary">5 Đơn hàng mới nhất</h5>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($latestOrders as $o) { ?>
                        <tr>
                            <td class="fw-bold text-success"><?= htmlspecialchars($o->orderCode) ?></td>
                            <td><?= htmlspecialchars($o->customerName) ?></td>
                            <td><?= date('d/m/Y', strtotime($o->createdAt)) ?></td>
                            <td class="text-danger fw-bold"><?= number_format($o->totalAmount, 0, ',', '.') ?>đ</td>
                            <td>
                                <?php if ($o->status == 0) { ?>
                                    <span class="badge bg-warning text-dark">Chờ xử lý</span>
                                <?php } elseif ($o->status == 1) { ?>
                                    <span class="badge bg-success">Hoàn thành</span>
                                <?php } else { ?>
                                    <span class="badge bg-danger">Hủy</span>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include "layouts/master.php";
?>
