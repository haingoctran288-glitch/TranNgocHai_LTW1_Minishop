<?php
$pageTitle = "Quản lý Danh mục";
ob_start();
?>
<div class="alert alert-info">
    Giao diện Quản lý Danh mục (Sẽ được phát triển ở Lab tiếp theo)
</div>
<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>
