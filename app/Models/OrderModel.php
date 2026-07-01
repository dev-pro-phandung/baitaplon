<?php
require_once __DIR__ . '/../Config/Database.php';

class OrderModel extends Database {
    
    // 1. Tạo đơn hàng mới
    public function createOrder($user_id, $fullname, $phone, $address, $note, $total_money, $payment_method = 'COD') {
        
        // --- SỬA LỖI TẠI ĐÂY ---
        // 1. Dùng 'phone' thay vì 'phone_number'
        // 2. Dùng 'created_at' thay vì 'order_date'
        $sql = "INSERT INTO orders (user_id, fullname, phone, address, note, total_money, payment_method, status, created_at) 
                VALUES (:user_id, :fullname, :phone, :address, :note, :total_money, :payment_method, 1, NOW())";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'user_id' => $user_id,
            'fullname' => $fullname,
            'phone' => $phone,
            'address' => $address,
            'note' => $note,
            'total_money' => $total_money,
            'payment_method' => $payment_method
        ]);
        
        return $this->conn->lastInsertId();
    }

    // 2. Lưu chi tiết đơn hàng
    public function createOrderDetail($order_id, $product_id, $price, $quantity) {
        $sql = "INSERT INTO order_details (order_id, product_id, price, num) 
                VALUES (:order_id, :product_id, :price, :num)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'order_id' => $order_id,
            'product_id' => $product_id,
            'price' => $price,
            'num' => $quantity
        ]);
    }

    // 3. Lấy danh sách đơn hàng của MỘT NGƯỜI DÙNG
    public function getOrdersByUser($user_id) {
        $sql = "SELECT * FROM orders WHERE user_id = :user_id ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 4. Lấy chi tiết các sản phẩm trong 1 đơn hàng
    public function getOrderDetails($order_id) {
        $sql = "SELECT od.*, p.name, p.image 
                FROM order_details od 
                JOIN products p ON od.product_id = p.id 
                WHERE od.order_id = :order_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['order_id' => $order_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 5. Lấy TẤT CẢ đơn hàng (Admin)
    public function getAllOrders() {
        $sql = "SELECT * FROM orders ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 6. Lấy thông tin một đơn hàng theo ID
    public function getOrderById($id) {
        $sql = "SELECT * FROM orders WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 7. Cập nhật trạng thái đơn hàng
    public function updateOrderStatus($id, $status) {
        $sql = "UPDATE orders SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'status' => $status,
            'id' => $id
        ]);
    }

    // 8. TÍNH TỔNG DOANH THU
    public function getTotalRevenue() {
        $sql = "SELECT SUM(total_money) as total FROM orders WHERE status = 3";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ? $result['total'] : 0;
    }

    // 9. ĐẾM SỐ ĐƠN HÀNG THÀNH CÔNG
    public function getCountSuccessOrders() {
        $sql = "SELECT COUNT(*) as count FROM orders WHERE status = 3";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
    // ==========================================
    // NHÓM 2: XỬ LÝ GIỎ HÀNG BẰNG API (BẢNG CARTS)
    // ==========================================

    public function getCartItemsByUserId($user_id) {
        $sql = "SELECT c.*, p.name, p.price, p.image, p.quantity as stock 
                FROM carts c 
                JOIN products p ON c.product_id = p.id 
                WHERE c.user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCartItem($user_id, $product_id) {
        $sql = "SELECT * FROM carts WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ĐÂY CHÍNH LÀ HÀM BẠN ĐANG BỊ THIẾU
    public function addCartItem($user_id, $product_id, $quantity) {
        $sql = "INSERT INTO carts (user_id, product_id, num) VALUES (:user_id, :product_id, :num)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'user_id' => $user_id,
            'product_id' => $product_id,
            'num' => $quantity
        ]);
    }

    public function updateCartItem($user_id, $product_id, $quantity) {
        $sql = "UPDATE carts SET num = :num WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'num' => $quantity,
            'user_id' => $user_id,
            'product_id' => $product_id
        ]);
    }

    public function removeCartItem($user_id, $product_id) {
        $sql = "DELETE FROM carts WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'user_id' => $user_id,
            'product_id' => $product_id
        ]);
    }

    public function clearCartByUserId($user_id) {
        $sql = "DELETE FROM carts WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['user_id' => $user_id]);
    }
}
?>