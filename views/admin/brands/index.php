<?php
$pageTitle = "Quản lý Thương hiệu";
ob_start();
?>
<div class="alert alert-info">
    Giao diện Quản lý Thương hiệu (Sẽ được phát triển ở Lab tiếp theo)
</div>
<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>
