<?php
require_once __DIR__ . '/../Config/Database.php';

class BrandModel extends Database {

    // 1. Lấy tất cả thương hiệu
    public function getAll() {
        $sql = "SELECT * FROM brands ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Lấy 1 thương hiệu theo ID
    public function getOne($id) {
        $sql = "SELECT * FROM brands WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 3. Thêm thương hiệu mới
    public function insert($name, $image, $description) {
        $sql = "INSERT INTO brands (name, image, description) VALUES (:name, :image, :description)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'name' => $name,
            'image' => $image,
            'description' => $description
        ]);
    }

    // 4. Cập nhật thương hiệu
    public function update($id, $name, $image, $description) {
        if ($image) {
            // Nếu có chọn ảnh mới -> Cập nhật cả ảnh
            $sql = "UPDATE brands SET name=:name, image=:image, description=:description WHERE id=:id";
            $params = ['id'=>$id, 'name'=>$name, 'image'=>$image, 'description'=>$description];
        } else {
            // Nếu không chọn ảnh -> Giữ nguyên ảnh cũ
            $sql = "UPDATE brands SET name=:name, description=:description WHERE id=:id";
            $params = ['id'=>$id, 'name'=>$name, 'description'=>$description];
        }
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    // 5. Xóa thương hiệu
    public function delete($id) {
        // (Tùy chọn) Trước khi xóa nên kiểm tra xem có sản phẩm nào thuộc hãng này không
        $sql = "DELETE FROM brands WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
?>