<?php
    header('Content-Type: application/json');
    header("Access-Control-Allow-Methods: GET, POST, DELETE");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Access-Control-Allow-Origin: *");
    require_once __DIR__ . '/../Models/OrderModel.php';

    class ApiPayController {
        private $orderModel;

        public function __construct() {
            $this->orderModel = new OrderModel();
        }

        // 1. Thanh toán đơn hàng
        public function checkout() {
            $data = json_decode(file_get_contents("php://input"), true);
            
            $user_id = isset($data['user_id']) ? $data['user_id'] : null;
            $payment_method = isset($data['payment_method']) ? $data['payment_method'] : 'COD';
            
            // Lấy thêm các thông tin bắt buộc để giao hàng
            $fullname = isset($data['fullname']) ? $data['fullname'] : '';
            $phone = isset($data['phone']) ? $data['phone'] : '';
            $address = isset($data['address']) ? $data['address'] : '';
            $note = isset($data['note']) ? $data['note'] : '';

            if (!$user_id || !$fullname || !$phone || !$address) {
                $this->response(400, "Thiếu thông tin người nhận (user_id, fullname, phone hoặc address)");
                return;
            }

            // Lấy danh sách sản phẩm trong giỏ hàng của người dùng
            $cartItems = $this->orderModel->getCartItemsByUserId($user_id);
            if (empty($cartItems)) {
                $this->response(400, "Giỏ hàng đang trống, không thể thanh toán");
                return;
            }

            // Tính tổng tiền
            $total_amount = 0;
            foreach ($cartItems as $item) {
                $total_amount += $item['price'] * $item['num'];
            }

            // Tạo đơn hàng mới (Gọi đúng hàm createOrder trong OrderModel)
            $order_id = $this->orderModel->createOrder($user_id, $fullname, $phone, $address, $note, $total_amount, $payment_method);

            if ($order_id) {
                // Thêm chi tiết đơn hàng (Gọi đúng hàm createOrderDetail)
                foreach ($cartItems as $item) {
                    $this->orderModel->createOrderDetail($order_id, $item['product_id'], $item['price'], $item['num']);
                }

                // Xóa toàn bộ giỏ hàng của user sau khi chốt đơn
                $this->orderModel->clearCartByUserId($user_id);

                $orderInfo = [
                    'order_id' => $order_id,
                    'total_amount' => $total_amount,
                    'payment_method' => $payment_method,
                    'status' => 'pending'
                ];
                $this->response(200, "Thanh toán và tạo đơn hàng thành công", $orderInfo);
            } else {
                $this->response(500, "Lỗi server: Không thể tạo đơn hàng");
            }
        }

        public function response($status_code, $message, $data = null) {
            http_response_code($status_code);
            echo json_encode([
                'status' => $status_code,
                'message' => $message,
                'data' => $data
            ]);
            exit(); // Đừng quên exit() để không load HTML
        }

        // Đồng bộ biến $act giống các Controller khác
        public function handleRequest($act) {
            $method = $_SERVER['REQUEST_METHOD'];

            switch ($act) {
                case 'api_checkout': // Đường link sẽ là ?act=api_checkout
                    if ($method == 'POST') $this->checkout();
                    else $this->response(405, "Phương thức không hợp lệ (Cần dùng POST)");
                    break;
                default:
                    $this->response(404, "Không tìm thấy Endpoint API.");
                    break;
            }
        }
    } 
?>