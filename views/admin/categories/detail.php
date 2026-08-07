<?php
require_once "../../../dao/CategoryDAO.php";
$dao = new CategoryDAO();
$id = $_GET['id'] ?? 0;
$category = $dao->findById($id);

if (!$category) {
    echo "Không tìm thấy danh mục";
    exit;
}

$pageTitle = "Chi tiết danh mục";
ob_start();
?>
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 pt-4 pb-0">
            <h4 class="fw-bold text-secondary">Chi tiết danh mục #<?= $category->id ?></h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th width="20%">Hình ảnh</th>
                    <td>
                        <?php if ($category->image != "") { ?>
                            <img src="/MiniShop_TranNgocHai/uploads/categories/<?= $category->image ?>" class="img-thumbnail" width="150">
                        <?php } else { ?>
                            <span class="text-muted">No Image</span>
                        <?php } ?>
                    </td>
                </tr>
                <tr><th width="20%">ID</th><td><?= $category->id ?></td></tr>
                <tr><th>Tên danh mục</th><td class="text-primary fw-bold"><?= htmlspecialchars($category->name) ?></td></tr>
                <tr><th>Slug</th><td><?= htmlspecialchars($category->slug) ?></td></tr>
                <tr><th>Mô tả</th><td><?= nl2br(htmlspecialchars($category->description)) ?></td></tr>
                <tr><th>Trạng thái</th><td><?= $category->status == 1 ? '<span class="badge bg-success">Hiển thị</span>' : '<span class="badge bg-secondary">Ẩn</span>' ?></td></tr>
                <tr><th>Ngày tạo</th><td><?= $category->createdAt ?></td></tr>
                <tr><th>Ngày cập nhật</th><td><?= $category->updatedAt ?></td></tr>
            </table>
            <a href="index.php" class="btn btn-secondary mt-3">Quay lại</a>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>
