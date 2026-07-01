<?php
    header('Content-Type: application/json');
    header("Access-Control-Allow-Methods: GET, POST");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Access-Control-Allow-Origin: *");

    require_once __DIR__ . '/../Models/OrderModel.php';
    require_once __DIR__ . '/../Models/ProductModel.php';
    require_once __DIR__ . '/../Models/CategoryModel.php';
    require_once __DIR__ . '/../Models/BannerModel.php';
    require_once __DIR__ . '/../Models/BrandModel.php';
    require_once __DIR__ . '/../Models/UserModel.php';

    public function getAllOrders() {
        $orderModel = new OrderModel();
        $orders = $orderModel->getAllOrders(); // Giả sử bạn đã có phương thức này trong OrderModel
        echo json_encode($orders);
    }

    public function getOrderDetails($order_id) {
        $orderModel = new OrderModel();
        $details = $orderModel->getOrderDetails($order_id);
        echo json_encode($details);
    }

    public function updateOrderStatus($order_id, $status) {
        $orderModel = new OrderModel();
        $result = $orderModel->updateOrderStatus($order_id, $status); // Giả sử bạn đã có phương thức này trong OrderModel
        echo json_encode(['success' => $result]);
    }

    public function getTotalRevenue() {
        $orderModel = new OrderModel();
        $totalRevenue = $orderModel->getTotalRevenue(); // Giả sử bạn đã có phương thức này trong OrderModel
        echo json_encode(['total_revenue' => $totalRevenue]);
    }

    public function getCountSuccessOrders() {
        $orderModel = new OrderModel();
        $count = $orderModel->getCountSuccessOrders(); // Giả sử bạn đã có phương thức này trong OrderModel
        echo json_encode(['count_success_orders' => $count]);
    }

    // ==========================================
    // NHÓM 1: XỬ LÝ ĐƠN HÀNG
    // ==========================================
    // 1. Tạo đơn hàng mới
    public function createOrder($user_id, $total_money, $status) {
        $sql = "INSERT INTO orders (user_id, total_money, status) VALUES (:user_id, :total_money, :status)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'user_id' => $user_id,
            'total_money' => $total_money,
            'status' => $status
        ]);
        return $this->conn->lastInsertId();
    }

    // 2. Thêm chi tiết đơn hàng
    public function addOrderDetails($order_id, $product_id, $quantity, $price
    ) {
        $sql = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'order_id' => $order_id,
            'product_id' => $product_id,
            'quantity' => $quantity,
            'price' => $price
        ]);
    }

    // 3. Lấy tất cả đơn hàng của một người dùng
    public function getOrdersByUserId($user_id) {
        $sql = "SELECT * FROM orders WHERE user_id = :user_id ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    