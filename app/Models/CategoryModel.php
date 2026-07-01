<?php
require_once __DIR__ . '/../Config/Database.php';

class CategoryModel extends Database {

    // 1. Lấy tất cả danh mục (Sắp xếp theo cha con để hiển thị đẹp)
    public function getAll() {
        $sql = "SELECT * FROM categories ORDER BY parent_id ASC, id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    // 2. Lấy chi tiết 1 danh mục (QUAN TRỌNG: Để sửa lỗi getOne)
    public function getOne($id) {
        $sql = "SELECT * FROM categories WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 3. Thêm mới danh mục
    public function insert($name, $image, $parent_id) {
        $sql = "INSERT INTO categories (name, image, parent_id) VALUES (:name, :image, :parent_id)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'name' => $name, 
            'image' => $image,
            'parent_id' => $parent_id
        ]);
    }

    // 4. Cập nhật danh mục (Dùng cho chức năng Sửa sau này)
    public function update($id, $name, $image, $parent_id) {
        $sql = "UPDATE categories SET name = :name, image = :image, parent_id = :parent_id WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'name' => $name, 
            'image' => $image,
            'parent_id' => $parent_id
        ]);
    }

    // 5. Xóa danh mục
    public function delete($id) {
        $sql = "DELETE FROM categories WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
    
}
?>