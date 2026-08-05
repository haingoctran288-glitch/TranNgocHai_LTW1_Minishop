<?php
require_once __DIR__ . "/BaseDAO.php";
require_once __DIR__ . "/../models/Product.php";

class ProductDAO extends BaseDAO {
    public function __construct() { parent::__construct(); }

    public function getLatestProducts() {
        $list = [];
        try {
            $sql = "SELECT * FROM products ORDER BY id DESC LIMIT 5";
            $result = $this->executeQuery($sql);
            while ($row = $result->fetch_assoc()) {
                $product = new Product((int)$row["category_id"], (int)$row["brand_id"], $row["proname"], $row["slug"], (float)$row["price"], (float)$row["discount_price"], (int)$row["quantity"], $row["image"], $row["description"], (int)$row["status"]);
                $product->id = $row["id"];
                $list[] = $product;
            }
        } catch (Exception $e) { throw $e; }
        return $list;
    }

    public function getAll() {
        $list = [];
        try {
            $sql = "SELECT * FROM products ORDER BY id DESC";
            $result = $this->executeQuery($sql);
            while ($row = $result->fetch_assoc()) {
                $product = new Product((int)$row["category_id"], (int)$row["brand_id"], $row["proname"], $row["slug"], (float)$row["price"], (float)$row["discount_price"], (int)$row["quantity"], $row["image"], $row["description"], (int)$row["status"]);
                $product->id = $row["id"];
                $list[] = $product;
            }
        } catch (Exception $e) { throw $e; }
        return $list;
    }

    public function findById(int $id) {
        try {
            $sql = "SELECT * FROM products WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $product = new Product((int)$row["category_id"], (int)$row["brand_id"], $row["proname"], $row["slug"], (float)$row["price"], (float)$row["discount_price"], (int)$row["quantity"], $row["image"], $row["description"], (int)$row["status"]);
                $product->id = $row["id"];
                return $product;
            }
        } catch (Exception $e) { throw $e; }
        return null;
    }

    public function insert(Product $p): bool {
        try {
            $sql = "INSERT INTO products(category_id, brand_id, proname, slug, price, discount_price, quantity, image, description, status) VALUES(?,?,?,?,?,?,?,?,?,?)";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("iissddissi", $p->categoryId, $p->brandId, $p->proname, $p->slug, $p->price, $p->discountPrice, $p->quantity, $p->image, $p->description, $p->status);
            return $stmt->execute();
        } catch (Exception $e) { throw $e; }
    }

    public function update(Product $p): bool {
        try {
            $sql = "UPDATE products SET category_id=?, brand_id=?, proname=?, slug=?, price=?, discount_price=?, quantity=?, image=?, description=?, status=? WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("iissddissii", $p->categoryId, $p->brandId, $p->proname, $p->slug, $p->price, $p->discountPrice, $p->quantity, $p->image, $p->description, $p->status, $p->id);
            return $stmt->execute();
        } catch (Exception $e) { throw $e; }
    }

    public function delete(int $id): bool {
        try {
            $sql = "DELETE FROM products WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        } catch (Exception $e) { throw $e; }
    }
}
?>
