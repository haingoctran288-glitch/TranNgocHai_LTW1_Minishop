<?php
$pageTitle = "Quản lý Khách hàng";
ob_start();
?>
<div class="alert alert-info">
    Giao diện Quản lý Khách hàng (Sẽ được phát triển ở Lab tiếp theo)
</div>
<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>
