<?php
$pageTitle = "Quản lý Đơn hàng";
ob_start();
?>
<div class="alert alert-info">
    Giao diện Quản lý Đơn hàng (Sẽ được phát triển ở Lab tiếp theo)
</div>
<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>
