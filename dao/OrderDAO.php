<?php
require_once __DIR__ . "/BaseDAO.php";
require_once __DIR__ . "/../models/Order.php";

class OrderDAO extends BaseDAO {
    public function __construct() { parent::__construct(); }

    public function getLatestOrders() {
        $list = [];
        try {
            $sql = "SELECT o.*, c.fullname as customer_name 
                    FROM orders o 
                    JOIN customers c ON o.customer_id = c.id 
                    ORDER BY o.id DESC LIMIT 5";
            $result = $this->executeQuery($sql);
            while ($row = $result->fetch_assoc()) {
                $o = new Order((int)$row["customer_id"], (int)$row["user_id"], $row["order_code"], (float)$row["total_amount"], $row["note"], (int)$row["status"]);
                $o->id = $row["id"];
                $o->createdAt = $row["created_at"];
                $o->customerName = $row["customer_name"];
                $list[] = $o;
            }
        } catch (Exception $e) { throw $e; }
        return $list;
    }

    public function getAll() {
        $list = [];
        try {
            $sql = "SELECT * FROM orders ORDER BY id DESC";
            $result = $this->executeQuery($sql);
            while ($row = $result->fetch_assoc()) {
                $o = new Order((int)$row["customer_id"], (int)$row["user_id"], $row["order_code"], (float)$row["total_amount"], $row["note"], (int)$row["status"]);
                $o->id = $row["id"];
                $list[] = $o;
            }
        } catch (Exception $e) { throw $e; }
        return $list;
    }

    public function findById(int $id) {
        try {
            $sql = "SELECT * FROM orders WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $o = new Order((int)$row["customer_id"], (int)$row["user_id"], $row["order_code"], (float)$row["total_amount"], $row["note"], (int)$row["status"]);
                $o->id = $row["id"];
                return $o;
            }
        } catch (Exception $e) { throw $e; }
        return null;
    }

    public function insert(Order $o): bool {
        try {
            $sql = "INSERT INTO orders(customer_id, user_id, order_code, total_amount, note, status) VALUES(?,?,?,?,?,?)";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("iisdsi", $o->customerId, $o->userId, $o->orderCode, $o->totalAmount, $o->note, $o->status);
            return $stmt->execute();
        } catch (Exception $e) { throw $e; }
    }

    public function update(Order $o): bool {
        try {
            $sql = "UPDATE orders SET customer_id=?, user_id=?, order_code=?, total_amount=?, note=?, status=? WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("iisdsii", $o->customerId, $o->userId, $o->orderCode, $o->totalAmount, $o->note, $o->status, $o->id);
            return $stmt->execute();
        } catch (Exception $e) { throw $e; }
    }

    public function delete(int $id): bool {
        try {
            $sql = "DELETE FROM orders WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        } catch (Exception $e) { throw $e; }
    }
}
?>
