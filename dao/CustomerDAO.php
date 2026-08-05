<?php
require_once __DIR__ . "/BaseDAO.php";
require_once __DIR__ . "/../models/Customer.php";

class CustomerDAO extends BaseDAO {
    public function __construct() { parent::__construct(); }

    public function getAll() {
        $list = [];
        try {
            $sql = "SELECT * FROM customers";
            $result = $this->executeQuery($sql);
            while ($row = $result->fetch_assoc()) {
                $c = new Customer($row["fullname"], $row["phone"], $row["email"], $row["address"], $row["note"]);
                $c->id = $row["id"];
                $list[] = $c;
            }
        } catch (Exception $e) { throw $e; }
        return $list;
    }

    public function findById(int $id) {
        try {
            $sql = "SELECT * FROM customers WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $c = new Customer($row["fullname"], $row["phone"], $row["email"], $row["address"], $row["note"]);
                $c->id = $row["id"];
                return $c;
            }
        } catch (Exception $e) { throw $e; }
        return null;
    }

    public function insert(Customer $c): bool {
        try {
            $sql = "INSERT INTO customers(fullname, phone, email, address, note) VALUES(?,?,?,?,?)";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("sssss", $c->fullname, $c->phone, $c->email, $c->address, $c->note);
            return $stmt->execute();
        } catch (Exception $e) { throw $e; }
    }

    public function update(Customer $c): bool {
        try {
            $sql = "UPDATE customers SET fullname=?, phone=?, email=?, address=?, note=? WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("sssssi", $c->fullname, $c->phone, $c->email, $c->address, $c->note, $c->id);
            return $stmt->execute();
        } catch (Exception $e) { throw $e; }
    }

    public function delete(int $id): bool {
        try {
            $sql = "DELETE FROM customers WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        } catch (Exception $e) { throw $e; }
    }
}
?>
