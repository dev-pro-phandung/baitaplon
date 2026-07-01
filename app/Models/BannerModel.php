<?php
require_once __DIR__ . '/../Config/Database.php';

class BannerModel extends Database {

    // 1. Lấy tất cả Banner (Dùng cho Admin)
    public function getAll() {
        $sql = "SELECT * FROM banners ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Lấy Banner hiển thị ra Trang chủ (HÀM BẠN ĐANG THIẾU)
    // Thường sẽ sắp xếp theo thứ tự hiển thị (position)
    public function getAllActive() {
        $sql = "SELECT * FROM banners ORDER BY position ASC, id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 3. Lấy 1 Banner theo ID (để sửa)
    public function getOne($id) {
        $sql = "SELECT * FROM banners WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 4. Thêm mới
    public function insert($image, $link, $title, $position) {
        $sql = "INSERT INTO banners (image, link, title, position) VALUES (:image, :link, :title, :position)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'image' => $image,
            'link' => $link,
            'title' => $title,
            'position' => $position
        ]);
    }

    // 5. Cập nhật Banner
    public function update($id, $image, $link, $title, $position) {
        $sql = "UPDATE banners SET image = :image, link = :link, title = :title, position = :position WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'image' => $image,
            'link' => $link,
            'title' => $title,
            'position' => $position
        ]);
    }

    // 6. Xóa Banner
    public function delete($id) {
        $sql = "DELETE FROM banners WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
?>