<?php
    header('Content-Type: application/json; charset=UTF-8'); 
    header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT"); 
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");       
    header("Access-Control-Allow-Origin: *"); 
    
    require_once __DIR__ . '/../Models/UserModel.php';

    class ApiUserController {
        private $userModel;

        public function __construct() {
            $this->userModel = new UserModel();
        }

        public function handleRequest($act) {
            $method = $_SERVER['REQUEST_METHOD'];
            switch ($act) {
                case 'api_users': 
                    if ($method == 'GET') $this->getUsers();
                    else $this->response(405, "Phương thức không hợp lệ");
                    break;
                case 'api_register': 
                    if ($method == 'POST') $this->register();
                    else $this->response(405, "Phương thức không hợp lệ");
                    break;
                case 'api_user_delete': // BỔ SUNG: Xóa tài khoản khách hàng
                    if ($method == 'DELETE' || $method == 'POST') $this->deleteUser();
                    else $this->response(405, "Phương thức không hợp lệ");
                    break;
                default:
                    $this->response(404, "Không tìm thấy Endpoint API User.");
                    break;
            }
        }

        // 1. Lấy danh sách 
        private function getUsers() {
            $users = $this->userModel->getAllUsers();
            
            // BẢN VÁ: Gói vào key 'users' để khớp với res.data.users bên Javascript
            if ($users !== false) {
                $this->response(200, "Lấy danh sách người dùng thành công", ['users' => $users]);
            } else {
                $this->response(500, "Lỗi Server: Không thể lấy danh sách", ['users' => []]);
            }
        }

        // 2. Đăng ký tài khoản mới
        private function register() {
            $data = json_decode(file_get_contents("php://input"), true);
            
            // Phòng hờ nếu gửi bằng FormData thông thường thay vì raw JSON
            if (!$data) {
                $data = $_POST;
            }
            
            $fullname = isset($data['fullname']) ? trim($data['fullname']) : null;
            $phone = isset($data['phone']) ? trim($data['phone']) : null;
            $password = isset($data['password']) ? $data['password'] : null;
            $email = isset($data['email']) ? trim($data['email']) : null;

            if (!$fullname || !$phone || !$password) {
                $this->response(400, "Thiếu fullname, phone hoặc password");
                return;
            }

            // Kiểm tra số điện thoại đã tồn tại chưa
            if ($this->userModel->checkPhoneExists($phone)) {
                $this->response(409, "Số điện thoại đã tồn tại");
                return;
            }
            
            // Kiểm tra email nếu có truyền lên
            if ($email && $this->userModel->checkEmailExists($email)) {
                $this->response(409, "Email đã được sử dụng");
                return;
            }

            // Thực hiện đăng ký
            if ($this->userModel->register($fullname, $phone, $password, $email)) {
                $this->response(201, "Đăng ký thành công");
            } else {
                $this->response(500, "Lỗi Server: Đăng ký thất bại");
            }
        }

        // 3. Xóa khách hàng (BỔ SUNG)
        private function deleteUser() {
            // Lấy ID từ URL (vì JS gửi fetch dạng DELETE index.php?act=api_user_delete&id=...)
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            
            if ($id > 0) {
                // Giả định trong UserModel của bạn có hàm delete()
                $result = $this->userModel->delete($id);
                if ($result) {
                    $this->response(200, "Xóa khách hàng thành công");
                } else {
                    $this->response(500, "Lỗi Server: Không thể xóa khách hàng");
                }
            } else {
                $this->response(400, "ID khách hàng không hợp lệ");
            }
        }

        // Hàm tiện ích để trả về phản hồi JSON
        private function response($statusCode, $message, $data = null) {
            http_response_code($statusCode);
            $response = [
                'status' => $statusCode,
                'message' => $message
            ];
            if ($data !== null) {
                $response['data'] = $data;
            }
            echo json_encode($response);
            exit(); // Dừng luồng xử lý
        }
    }
?>