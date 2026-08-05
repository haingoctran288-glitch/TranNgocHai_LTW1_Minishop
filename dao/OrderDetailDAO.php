<?php
require_once __DIR__ . "/BaseDAO.php";
require_once __DIR__ . "/../models/OrderDetail.php";

class OrderDetailDAO extends BaseDAO {
    public function __construct() { parent::__construct(); }

    public function getAll() {
        $list = [];
        try {
            $sql = "SELECT * FROM order_details";
            $result = $this->executeQuery($sql);
            while ($row = $result->fetch_assoc()) {
                $od = new OrderDetail((int)$row["order_id"], (int)$row["product_id"], (int)$row["quantity"], (float)$row["price"], (float)$row["subtotal"]);
                $od->id = $row["id"];
                $list[] = $od;
            }
        } catch (Exception $e) { throw $e; }
        return $list;
    }

    public function findById(int $id) {
        try {
            $sql = "SELECT * FROM order_details WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $od = new OrderDetail((int)$row["order_id"], (int)$row["product_id"], (int)$row["quantity"], (float)$row["price"], (float)$row["subtotal"]);
                $od->id = $row["id"];
                return $od;
            }
        } catch (Exception $e) { throw $e; }
        return null;
    }

    public function insert(OrderDetail $od): bool {
        try {
            $sql = "INSERT INTO order_details(order_id, product_id, quantity, price, subtotal) VALUES(?,?,?,?,?)";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("iiidd", $od->orderId, $od->productId, $od->quantity, $od->price, $od->subtotal);
            return $stmt->execute();
        } catch (Exception $e) { throw $e; }
    }

    public function update(OrderDetail $od): bool {
        try {
            $sql = "UPDATE order_details SET order_id=?, product_id=?, quantity=?, price=?, subtotal=? WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("iiiddi", $od->orderId, $od->productId, $od->quantity, $od->price, $od->subtotal, $od->id);
            return $stmt->execute();
        } catch (Exception $e) { throw $e; }
    }

    public function delete(int $id): bool {
        try {
            $sql = "DELETE FROM order_details WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        } catch (Exception $e) { throw $e; }
    }
}
?>
