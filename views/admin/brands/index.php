<?php
require_once "../../../dao/BrandDAO.php";
$dao = new BrandDAO();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btnDelete'])) {
    $id = $_POST['id'] ?? 0;
    if ($id > 0) {
        $dao->delete($id);
        header("Location: index.php");
        exit;
    }
}

$keyword = trim($_GET["keyword"] ?? "");
$brands = $dao->getAll($keyword);

$pageTitle = "Danh sách thương hiệu";
ob_start();
?>
<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold text-secondary">Danh sách thương hiệu</h5>
        <a href="create.php" class="btn btn-success"><i class="fa-solid fa-plus"></i> Thêm mới</a>
    </div>
    <div class="card-body">
        <form class="row mb-3" method="GET">
            <div class="col-md-4">
                <input type="text" name="keyword" class="form-control" placeholder="Nhập tên thương hiệu..." value="<?= htmlspecialchars($keyword) ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            </div>
        </form>
        <?php if(empty($brands)) { ?>
            <div class="alert alert-warning">Không tìm thấy dữ liệu.</div>
        <?php } else { ?>
            <table class="table table-hover">
                <thead><tr><th>Hình ảnh</th><th>ID</th><th>Tên thương hiệu</th><th>Trạng thái</th><th>Chức năng</th></tr></thead>
                <tbody>
                    <?php foreach ($brands as $item) { ?>
                    <tr>
                        <td>
                            <?php if ($item->image != "") { ?>
                                <img src="/MiniShop_TranNgocHai/uploads/brands/<?= $item->image ?>" alt="<?= htmlspecialchars($item->brandname) ?>" class="img-thumbnail" width="80">
                            <?php } else { ?>
                                <span class="text-muted">No Image</span>
                            <?php } ?>
                        </td>
                        <td><?= $item->id ?></td>
                        <td class="fw-bold"><?= htmlspecialchars($item->brandname) ?></td>
                        <td><?= $item->status == 1 ? '<span class="badge bg-success">Hiển thị</span>' : '<span class="badge bg-secondary">Ẩn</span>' ?></td>
                        <td>
                            <a href="detail.php?id=<?= $item->id ?>" class="btn btn-sm btn-info text-white"><i class="fa-solid fa-eye"></i></a>
                            <a href="edit.php?id=<?= $item->id ?>" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>
                            <form method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa?');" class="d-inline">
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
