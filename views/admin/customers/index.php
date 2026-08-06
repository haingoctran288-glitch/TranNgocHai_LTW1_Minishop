<?php
require_once "../../../dao/CustomerDAO.php";
$dao = new CustomerDAO();
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btnDelete'])) {
    if (($id = $_POST['id'] ?? 0) > 0) { $dao->delete($id); header("Location: index.php"); exit; }
}
$keyword = trim($_GET["keyword"] ?? "");
$customers = $dao->getAll($keyword);
$pageTitle = "Danh sách khách hàng"; ob_start();
?>
<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-0 pt-4 pb-0 d-flex justify-content-between">
        <h5 class="fw-bold text-secondary">Danh sách khách hàng</h5>
        <a href="create.php" class="btn btn-success">Thêm mới</a>
    </div>
    <div class="card-body">
        <form class="row mb-3" method="GET">
            <div class="col-md-4"><input type="text" name="keyword" class="form-control" placeholder="Tìm kiếm..." value="<?= htmlspecialchars($keyword) ?>"></div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary">Tìm kiếm</button></div>
        </form>
        <?php if(empty($customers)) { echo "<div class='alert alert-warning'>Không tìm thấy.</div>"; } else { ?>
            <table class="table table-hover">
                <thead><tr><th>ID</th><th>Họ tên</th><th>Email</th><th>SĐT</th><th>Chức năng</th></tr></thead>
                <tbody>
                    <?php foreach ($customers as $item) { ?>
                    <tr>
                        <td><?= $item->id ?></td>
                        <td class="fw-bold"><?= htmlspecialchars($item->fullname) ?></td>
                        <td><?= htmlspecialchars($item->email) ?></td>
                        <td><?= htmlspecialchars($item->phone) ?></td>
                        <td>
                            <a href="detail.php?id=<?= $item->id ?>" class="btn btn-sm btn-info text-white"><i class="fa-solid fa-eye"></i></a>
                            <a href="edit.php?id=<?= $item->id ?>" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen"></i></a>
                            <form method="POST" onsubmit="return confirm('Xóa?');" class="d-inline">
                                <input type="hidden" name="id" value="<?= $item->id ?>">
                                <button type="submit" name="btnDelete" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
</div>
<?php $content = ob_get_clean(); include "../layouts/master.php"; ?>
