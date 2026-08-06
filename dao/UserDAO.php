<?php
require_once __DIR__ . "/BaseDAO.php";
require_once __DIR__ . "/../models/User.php";

class UserDAO extends BaseDAO {
    public function __construct() { parent::__construct(); }

    public function getAll($keyword = "") {
        $list = [];
        try {
            $sql = "SELECT * FROM users";
            if (!empty($keyword)) {
                $sql .= " WHERE fullname LIKE ? OR username LIKE ?";
            }
            $sql .= " ORDER BY fullname";

            if (!empty($keyword)) {
                $stmt = $this->prepare($sql);
                $param = "%" . $keyword . "%";
                $stmt->bind_param("ss", $param, $param);
                $stmt->execute();
                $result = $stmt->get_result();
            } else {
                $result = $this->executeQuery($sql);
            }

            while ($row = $result->fetch_assoc()) {
                $u = new User($row["fullname"], $row["username"], $row["password"], $row["email"], $row["phone"], $row["address"], (int)$row["role"], (int)$row["status"]);
                $u->id = (int)$row["id"];
                $u->createdAt = $row["created_at"];
                $u->updatedAt = $row["updated_at"];
                $list[] = $u;
            }
        } catch (Exception $e) { throw $e; }
        return $list;
    }

    public function findById(int $id) {
        try {
            $sql = "SELECT * FROM users WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $user = new User($row["fullname"], $row["username"], $row["password"], $row["email"], $row["phone"], $row["address"], (int)$row["role"], (int)$row["status"]);
                $user->id = $row["id"];
                return $user;
            }
        } catch (Exception $e) { throw $e; }
        return null;
    }

    public function insert(User $u): bool {
        try {
            $sql = "INSERT INTO users(fullname, username, password, email, phone, address, role, status) VALUES(?,?,?,?,?,?,?,?)";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("ssssssii", $u->fullname, $u->username, $u->password, $u->email, $u->phone, $u->address, $u->role, $u->status);
            return $stmt->execute();
        } catch (Exception $e) { throw $e; }
    }

    public function update(User $u): bool {
        try {
            $sql = "UPDATE users SET fullname=?, username=?, password=?, email=?, phone=?, address=?, role=?, status=? WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("ssssssiii", $u->fullname, $u->username, $u->password, $u->email, $u->phone, $u->address, $u->role, $u->status, $u->id);
            return $stmt->execute();
        } catch (Exception $e) { throw $e; }
    }

    public function delete(int $id): bool {
        try {
            $sql = "DELETE FROM users WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        } catch (Exception $e) { throw $e; }
    }
}
?>
