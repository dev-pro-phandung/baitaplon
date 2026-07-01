<?php
// =========================================================
// API BẢO MẬT & CẤU HÌNH HEADER (CORS)
// =========================================================
header('Content-Type: application/json; charset=UTF-8'); 
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT"); 
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");       
header("Access-Control-Allow-Origin: *"); 

require_once __DIR__ . '/../Models/OrderModel.php';

class ApiOrderController {
    private $orderModel;
    
    public function __construct() {
        $this->orderModel = new OrderModel();
    }

    public function handleRequest($act) {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($act) {
            case 'api_orders': // Lấy danh sách tất cả đơn hàng
                if ($method == 'GET') $this->getAllOrders();
                break;

            case 'api_order_detail': // Xem chi tiết Đơn hàng (Cho cả Admin và Khách)
                if ($method == 'GET') $this->getAdminOrderDetail();
                break;

            case 'api_order_add': // Thêm đơn hàng mới
                if ($method == 'POST') $this->createOrder();
                break;

            case 'api_order_update': // Sửa/Cập nhật trạng thái đơn hàng
                if ($method == 'POST') $this->updateOrderStatus();
                break;

            case 'api_order_delete': // Xóa đơn hàng
                if ($method == 'POST' || $method == 'DELETE') $this->deleteOrder();
                break;

            default:
                $this->response(404, "Không tìm thấy Endpoint API Đơn hàng.");
                break;
        }
    }

    // =========================================================
    // CÁC HÀM XỬ LÝ CHÍNH
    // =========================================================

    // 1. Lấy chi tiết đơn hàng (Gộp cả Order Info và Details)
    private function getAdminOrderDetail() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($id > 0) {
            $order = $this->orderModel->getOrderById($id);
            if ($order) {
                $details = $this->orderModel->getOrderDetails($id);
                // Trả về chung 1 cục data để JS dễ xử lý
                $this->response(200, "Lấy chi tiết đơn hàng thành công", [
                    'order' => $order,
                    'details' => $details
                ]);
            } else {
                $this->response(404, "Không tìm thấy đơn hàng");
            }
        } else {
            $this->response(400, "ID đơn hàng không hợp lệ");
        }
    }

    // 2. Cập nhật trạng thái đơn hàng (Dùng cho Admin)
    private function updateOrderStatus() {
        // BẢN VÁ: Dùng $_POST để nhận dữ liệu từ FormData của Javascript
        $order_id = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
        $status = isset($_POST['status']) ? (int)$_POST['status'] : 0;

        if ($order_id > 0 && $status > 0) {
            $result = $this->orderModel->updateOrderStatus($order_id, $status);
            if ($result) {
                $this->response(200, "Cập nhật trạng thái đơn hàng thành công");
            } else {
                $this->response(500, "Lỗi Server: Cập nhật thất bại");
            }
        } else {
            $this->response(400, "Thiếu ID đơn hàng hoặc trạng thái");
        }
    }

    // 3. Lấy tất cả đơn hàng
    private function getAllOrders() {
        $orders = $this->orderModel->getAllOrders();
        $this->response(200, "Lấy tất cả đơn hàng thành công", ['orders' => $orders]);
    }

    // 4. Tạo đơn hàng mới
    private function createOrder() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data) $data = $_POST; // Hỗ trợ cả FormData

        $user_id = isset($data['user_id']) ? $data['user_id'] : null;
        $fullname = isset($data['fullname']) ? $data['fullname'] : null;
        $phone = isset($data['phone']) ? $data['phone'] : null;
        $address = isset($data['address']) ? $data['address'] : null;
        $note = isset($data['note']) ? $data['note'] : '';
        $total_money = isset($data['total_money']) ? $data['total_money'] : 0;
        $payment_method = isset($data['payment_method']) ? $data['payment_method'] : 'COD';

        if (!$user_id || !$fullname || !$phone || !$address) {
            $this->response(400, "Thiếu thông tin bắt buộc");
            return;
        }

        $order_id = $this->orderModel->createOrder($user_id, $fullname, $phone, $address, $note, $total_money, $payment_method);
        
        if ($order_id) {
            $this->response(201, "Tạo đơn hàng thành công", ['order_id' => $order_id]);
        } else {
            $this->response(500, "Tạo đơn hàng thất bại");
        }
    }

    // 5. Xóa đơn hàng
    private function deleteOrder() {
        $order_id = isset($_POST['order_id']) ? (int)$_POST['order_id'] : (isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0);
        
        if ($order_id > 0) {
            $result = $this->orderModel->deleteOrder($order_id);
            if ($result) {
                $this->response(200, "Xóa đơn hàng thành công");
            } else {
                $this->response(500, "Xóa đơn hàng thất bại");
            }
        } else {
            $this->response(400, "Thiếu ID đơn hàng");
        }
    }

    // =========================================================
    // HÀM HỖ TRỢ TRẢ VỀ CHUẨN JSON CHUẨN MỰC
    // =========================================================
    public function response($statusCode, $message, $data = null) {
        http_response_code($statusCode);
        $response = [
            'status' => $statusCode,
            'message' => $message
        ];
        if ($data !== null) {
            $response['data'] = $data;
        }
        echo json_encode($response);
        exit(); 
    }
}
?>