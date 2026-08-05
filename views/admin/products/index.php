<?php
$pageTitle = "Quản lý Sản phẩm";
ob_start();
?>
<div class="alert alert-info">
    Giao diện Quản lý Sản phẩm (Sẽ được phát triển ở Lab tiếp theo)
</div>
<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>
