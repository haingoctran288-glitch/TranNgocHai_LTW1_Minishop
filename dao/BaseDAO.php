<?php
require_once __DIR__ . "/../config/Database.php";

class BaseDAO extends Database {
    public function __construct() {
        parent::__construct();
    }

    protected function executeQuery(string $sql) {
        return $this->conn->query($sql);
    }

    protected function prepare(string $sql) {
        return $this->conn->prepare($sql);
    }

    protected function beginTransaction(): void {
        $this->conn->begin_transaction();
    }

    protected function commit(): void {
        $this->conn->commit();
    }

    protected function rollback(): void {
        $this->conn->rollback();
    }

    // Hàm chung đếm tổng số dòng trong 1 bảng
    public function countTotal(string $table) {
        $sql = "SELECT COUNT(*) as total FROM " . $table;
        $result = $this->executeQuery($sql);
        if ($result && $row = $result->fetch_assoc()) {
            return $row['total'];
        }
        return 0;
    }
}
?>
