<?php include __DIR__ . "/header.php"; ?>
<?php include __DIR__ . "/sidebar.php"; ?>
<div class="col-md-10">
    <div class="topbar d-flex justify-content-between align-items-center">
        <h5 class="m-0 text-primary fw-bold"><?= isset($pageTitle) ? $pageTitle : "Bảng điều khiển" ?></h5>
        <div class="user-info">
            <span class="me-2"><i class="fa-solid fa-user-circle"></i> Chào, Admin</span>
        </div>
    </div>
    <div class="p-4">
        <?= isset($content) ? $content : "" ?>
    </div>
<?php include __DIR__ . "/footer.php"; ?>
