<?php
require_once __DIR__ . "/BaseDAO.php";
require_once __DIR__ . "/../models/Category.php";

class CategoryDAO extends BaseDAO {
    public function __construct() {
        parent::__construct();
    }

    public function getAll() {
        $list = [];
        try {
            $sql = "SELECT * FROM categories ORDER BY catename";
            $result = $this->executeQuery($sql);
            while ($row = $result->fetch_assoc()) {
                $category = new Category($row["catename"], $row["slug"], $row["image"], $row["description"], (int)$row["status"]);
                $category->id = (int)$row["id"];
                $category->createdAt = $row["created_at"];
                $category->updatedAt = $row["updated_at"];
                $list[] = $category;
            }
        } catch (Exception $e) { echo "Lỗi: " . $e->getMessage(); }
        return $list;
    }

    public function findById(int $id) {
        try {
            $sql = "SELECT * FROM categories WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $category = new Category($row["catename"], $row["slug"], $row["image"], $row["description"], (int)$row["status"]);
                $category->id = (int)$row["id"];
                $category->createdAt = $row["created_at"];
                $category->updatedAt = $row["updated_at"];
                return $category;
            }
        } catch (Exception $e) { echo "Lỗi: " . $e->getMessage(); }
        return null;
    }

    public function insert(Category $category): bool {
        try {
            $sql = "INSERT INTO categories(catename, slug, image, description, status) VALUES(?,?,?,?,?)";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("ssssi", $category->name, $category->slug, $category->image, $category->description, $category->status);
            return $stmt->execute();
        } catch (Exception $e) { throw $e; }
    }

    public function update(Category $category): bool {
        try {
            $sql = "UPDATE categories SET catename=?, slug=?, image=?, description=?, status=? WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("ssssii", $category->name, $category->slug, $category->image, $category->description, $category->status, $category->id);
            return $stmt->execute();
        } catch (Exception $e) { throw $e; }
    }

    public function delete(int $id): bool {
        try {
            $sql = "DELETE FROM categories WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        } catch (Exception $e) { throw $e; }
    }
}
?>
