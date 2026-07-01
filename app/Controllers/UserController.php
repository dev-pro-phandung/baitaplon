<?php
require_once __DIR__ . '/../Config/Database.php';

class UserModel extends Database {
    
    // 1. Kiểm tra số điện thoại đã tồn tại chưa (Dùng cho đăng ký)
    public function checkPhoneExists($phone) {
        $sql = "SELECT COUNT(*) as count FROM users WHERE phone = :phone";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['phone' => $phone]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    // 2. Đăng ký tài khoản mới (Khớp với AuthController)
    public function register($fullname, $phone, $password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Lưu ý: Email và Address để trống hoặc null ban đầu
        $sql = "INSERT INTO users (fullname, phone, password, role, created_at) 
                VALUES (:fullname, :phone, :password, 0, NOW())";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'fullname' => $fullname,
            'phone' => $phone,
            'password' => $hashed_password
        ]);
    }

    // 3. Xử lý Đăng nhập (Bằng Số điện thoại)
    public function login($phone, $password) {
        $sql = "SELECT * FROM users WHERE phone = :phone";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['phone' => $phone]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Nếu tìm thấy user VÀ mật khẩu trùng khớp
        if ($user && password_verify($password, $user['password'])) {
            return $user; // Trả về thông tin user
        }
        return false; // Sai thông tin
    }

    // 4. Lấy thông tin theo ID
    public function getById($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 5. Lấy danh sách user (Admin)
    public function getAllUsers() {
        $sql = "SELECT * FROM users WHERE role = 0 ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 6. Xóa tài khoản
    public function delete($id) {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
?>