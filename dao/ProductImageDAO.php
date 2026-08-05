<?php
require_once __DIR__ . "/BaseDAO.php";
require_once __DIR__ . "/../models/ProductImage.php";

class ProductImageDAO extends BaseDAO {
    public function __construct() { parent::__construct(); }

    public function getAll() {
        $list = [];
        try {
            $sql = "SELECT * FROM product_images";
            $result = $this->executeQuery($sql);
            while ($row = $result->fetch_assoc()) {
                $pi = new ProductImage((int)$row["product_id"], $row["image"], (int)$row["sort_order"]);
                $pi->id = $row["id"];
                $list[] = $pi;
            }
        } catch (Exception $e) { throw $e; }
        return $list;
    }

    public function findById(int $id) {
        try {
            $sql = "SELECT * FROM product_images WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $pi = new ProductImage((int)$row["product_id"], $row["image"], (int)$row["sort_order"]);
                $pi->id = $row["id"];
                return $pi;
            }
        } catch (Exception $e) { throw $e; }
        return null;
    }

    public function insert(ProductImage $pi): bool {
        try {
            $sql = "INSERT INTO product_images(product_id, image, sort_order) VALUES(?,?,?)";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("isi", $pi->productId, $pi->image, $pi->sortOrder);
            return $stmt->execute();
        } catch (Exception $e) { throw $e; }
    }

    public function update(ProductImage $pi): bool {
        try {
            $sql = "UPDATE product_images SET product_id=?, image=?, sort_order=? WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("isii", $pi->productId, $pi->image, $pi->sortOrder, $pi->id);
            return $stmt->execute();
        } catch (Exception $e) { throw $e; }
    }

    public function delete(int $id): bool {
        try {
            $sql = "DELETE FROM product_images WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        } catch (Exception $e) { throw $e; }
    }
}
?>
