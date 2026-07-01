<?php
    header('Content-Type: application/json'); // Đặt header trả về JSON cho tất cả các API trong file này
    header("Access-Control-Allow-Methods: GET, POST, DELETE"); // Cho phép các phương thức HTTP này
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); // Cho phép các header này (quan trọng cho POST có chứa JSON hoặc Form Data)       
    header("Access-Control-Allow-Origin: *"); // Cho phép tất cả các nguồn (domain) có thể gọi API này (CORS   policy)
    require_once __DIR__ . '/../Models/OrderModel.php';
    require_once __DIR__ . '/../Models/ProductModel.php';

    class ApiCartController {
        private $orderModel;
        private $productModel;
        public function __construct() {
            $this->orderModel = new OrderModel();
            $this->productModel = new ProductModel();
        }

        // 1. Lấy danh sách (Hỗ trợ tìm kiếm theo tên)
        public function getCartItems() {
            $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
            if (!$user_id) {
                $this->response(400, "Thiếu user_id");
                return;
            }
            $cartItems = $this->orderModel->getCartItemsByUserId($user_id);
            $this->response(200, "Lấy danh sách giỏ hàng thành công", $cartItems);
        }

        // 2. Thêm vào giỏ hàng (Nếu đã có sản phẩm thì tăng số lượng)
        public function addToCart() {
            $data = json_decode(file_get_contents("php://input"), true);
            $user_id = isset($data['user_id']) ? $data['user_id'] : null;
            $product_id = isset($data['product_id']) ? $data['product_id'] : null;
            $quantity = isset($data['num']) ? $data['num'] : 1;

            if (!$user_id || !$product_id) {
                $this->response(400, "Thiếu user_id hoặc product_id");
                return;
            }

            // Kiểm tra sản phẩm đã tồn tại trong giỏ hàng chưa
            $existingItem = $this->orderModel->getCartItem($user_id, $product_id);
            if ($existingItem) {
                // Đã có rồi, tăng số lượng
                $newQuantity = $existingItem['num'] + $quantity;
                $this->orderModel->updateCartItem($user_id, $product_id, $newQuantity);
            } else {
                // Chưa có, thêm mới
                $this->orderModel->addCartItem($user_id, $product_id, $quantity);
            }
            $this->response(200, "Thêm vào giỏ hàng thành công");
        }
        // 3. Cập nhật số lượng trong giỏ hàng
        public function updateCartItem() {
            $data = json_decode(file_get_contents("php://input"), true);
            $user_id = isset($data['user_id']) ? $data['user_id'] : null;
            $product_id = isset($data['product_id']) ? $data['product_id'] : null;
            $quantity = isset($data['num']) ? $data['num'] : 1;

            if (!$user_id || !$product_id) {
                $this->response(400, "Thiếu user_id hoặc product_id");
                return;
            }
            $this->orderModel->updateCartItem($user_id, $product_id, $quantity);
            $this->response(200, "Cập nhật giỏ hàng thành công");
        }
        // 4. Xóa sản phẩm khỏi giỏ hàng
        public function removeFromCart() {
            $data = json_decode(file_get_contents("php://input"), true);
            $user_id = isset($data['user_id']) ? $data['user_id'] : null;
            $product_id = isset($data['product_id']) ? $data['product_id'] : null;

            if (!$user_id || !$product_id) {
                $this->response(400, "Thiếu user_id hoặc product_id");
                return;
            }
            $this->orderModel->removeCartItem($user_id, $product_id);
            $this->response(200, "Xóa khỏi giỏ hàng thành công");
        }
        // Hàm tiện ích để trả về JSON
        private function response($status_code, $message, $data = []) {
            http_response_code($status_code);
            echo json_encode([
                'status' => $status_code,
                'message' => $message,
                'data' => $data
            ]);
        }
        // Thay thế hàm handleRequest cũ bằng hàm này
        public function handleRequest($act) {
            $method = $_SERVER['REQUEST_METHOD'];

            switch ($act) {
                case 'api_cart_get':
                    if ($method == 'GET') $this->getCartItems();
                    else $this->response(405, "Phương thức không được phép (Cần GET)");
                    break;
                    
                case 'api_cart_add':
                    if ($method == 'POST') $this->addToCart();
                    else $this->response(405, "Phương thức không được phép (Cần POST)");
                    break;
                    
                case 'api_cart_update':
                    if ($method == 'POST' || $method == 'PUT') $this->updateCartItem();
                    else $this->response(405, "Phương thức không được phép");
                    break;
                    
                case 'api_cart_remove':
                    if ($method == 'POST' || $method == 'DELETE') $this->removeFromCart();
                    else $this->response(405, "Phương thức không được phép");
                    break;
                    
                default:
                    $this->response(400, "Action không hợp lệ: " . $act);
            }
        }
    }



    