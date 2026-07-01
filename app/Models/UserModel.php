<?php
require_once __DIR__ . '/../Config/Database.php';

class UserModel extends Database {

    // 1. Kiểm tra SĐT đã tồn tại chưa
    public function checkPhoneExists($phone) {
        $sql = "SELECT COUNT(*) as count FROM users WHERE phone = :phone";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['phone' => $phone]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    // 2. (Tùy chọn) Kiểm tra Email tồn tại chưa
    public function checkEmailExists($email) {
        if (empty($email)) return false;
        $sql = "SELECT COUNT(*) as count FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    // 3. Đăng ký tài khoản mới (Hỗ trợ cả Email)
    public function register($fullname, $phone, $password, $email = null) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Nếu email rỗng, chuyển thành NULL để tránh lỗi Duplicate Key
        if (empty($email)) {
            $email = null;
        }

        $sql = "INSERT INTO users (fullname, phone, email, password, role, created_at) 
                VALUES (:fullname, :phone, :email, :password, 0, NOW())";
        
        $stmt = $this->conn->prepare($sql);
        
        return $stmt->execute([
            'fullname' => $fullname,
            'phone'    => $phone,
            'email'    => $email,
            'password' => $hashed_password
        ]);
    }

    // 4. Đăng nhập bằng SỐ ĐIỆN THOẠI
    public function login($phone, $password) {
        $sql = "SELECT * FROM users WHERE phone = :phone";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['phone' => $phone]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Kiểm tra mật khẩu mã hóa
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }
    
    // 5. Lấy thông tin User theo ID
    public function getById($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // 6. Lấy User theo Email (Dùng cho Quên mật khẩu)
    public function getByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 7. Cập nhật mật khẩu mới (Quên mật khẩu)
    public function updatePassword($email, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $sql = "UPDATE users SET password = :password WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        
        return $stmt->execute([
            'password' => $hashedPassword,
            'email' => $email
        ]);
    }

    // 8. Lấy tất cả người dùng (Dành cho Admin)
    public function getAllUsers() {
        $sql = "SELECT * FROM users ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 9. Xóa tài khoản (Dành cho Admin)
    public function delete($id) {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
?>