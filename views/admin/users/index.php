<?php
$pageTitle = "Quản lý Người dùng";
ob_start();
?>
<div class="alert alert-info">
    Giao diện Quản lý Người dùng (Sẽ được phát triển ở Lab tiếp theo)
</div>
<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>
