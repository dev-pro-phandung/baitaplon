<?php
// =========================================================
// API BẢO MẬT & CẤU HÌNH HEADER (CORS)
// =========================================================
header('Content-Type: application/json; charset=UTF-8');
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT"); 
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Origin: *");

require_once __DIR__ . '/../Models/CategoryModel.php';

class ApiCategoryController {
    private $categoryModel;

    public function __construct() {
        $this->categoryModel = new CategoryModel();
    }

    // =========================================================
    // ĐIỀU HƯỚNG API (ROUTER NỘI BỘ)
    // =========================================================
    public function handleRequest($act) {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($act) {
            case 'api_categories': // Lấy danh sách danh mục
                if ($method == 'GET') $this->getAllCategories();
                break;
            
            case 'api_category_add': // Thêm danh mục mới
                if ($method == 'POST') $this->addCategory();
                break;

            case 'api_category_update': // Sửa danh mục
                if ($method == 'POST') $this->updateCategory();
                break;

            case 'api_category_delete': // Xóa danh mục
                if ($method == 'POST' || $method == 'DELETE') $this->deleteCategory();
                break;

            default:
                $this->response(404, "Không tìm thấy Endpoint API Danh mục.");
                break;
        }
    }

    // =========================================================
    // CÁC HÀM XỬ LÝ CHÍNH
    // =========================================================

    // 1. Lấy tất cả danh mục
    private function getAllCategories() {
        $categories = $this->categoryModel->getAll(); 
        
        if ($categories) {
            // Trả về kèm key 'categories' để Frontend dễ gọi (res.data.categories)
            $this->response(200, "Lấy danh sách thành công", ['categories' => $categories]);
        } else {
            $this->response(404, "Chưa có danh mục nào", ['categories' => []]);
        }
    }

    // 2. Thêm danh mục mới
    private function addCategory() {
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $parent_id = isset($_POST['parent_id']) ? (int)$_POST['parent_id'] : 0;

        // Xử lý Upload Ảnh (Nếu danh mục có ảnh/icon)
        $imageName = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $imageName = time() . '_' . basename($_FILES['image']['name']);
            $targetPath = __DIR__ . '/../../public/img/' . $imageName;
            move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
        }

        if (empty($name)) {
            $this->response(400, "Tên danh mục không được để trống");
        }

        $result = $this->categoryModel->insert($name, $imageName, $parent_id);
        
        if ($result) {
            $this->response(201, "Thêm danh mục thành công");
        } else {
            $this->response(500, "Lỗi Server: Không thể thêm danh mục");
        }
    }

    // 3. Cập nhật danh mục
    private function updateCategory() {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        if ($id <= 0) $this->response(400, "Thiếu ID danh mục cần sửa");

        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $parent_id = isset($_POST['parent_id']) ? (int)$_POST['parent_id'] : 0;
        
        if (empty($name)) {
            $this->response(400, "Tên danh mục không được để trống");
        }

        // Xử lý ảnh: Nếu có up ảnh mới thì lấy, nếu không thì truyền chuỗi rỗng 
        // (Bên CategoryModel của bạn cần có logic: nếu ảnh rỗng thì giữ nguyên ảnh cũ)
        $imageName = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $imageName = time() . '_' . basename($_FILES['image']['name']);
            $targetPath = __DIR__ . '/../../public/img/' . $imageName;
            move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
        }

        $result = $this->categoryModel->update($id, $name, $imageName, $parent_id);
        
        if ($result) {
            $this->response(200, "Cập nhật danh mục thành công");
        } else {
            $this->response(500, "Lỗi Server: Không thể cập nhật danh mục");
        }
    }

    // 4. Xóa danh mục
    private function deleteCategory() {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : (isset($_GET['id']) ? (int)$_GET['id'] : 0);
        
        if ($id > 0) {
            $result = $this->categoryModel->delete($id);
            if ($result) {
                $this->response(200, "Xóa danh mục thành công");
            } else {
                $this->response(500, "Lỗi Server: Không thể xóa danh mục");
            }
        } else {
            $this->response(400, "ID danh mục không hợp lệ");
        }
    }

    // =========================================================
    // HÀM HỖ TRỢ TRẢ VỀ CHUẨN JSON
    // =========================================================
    private function response($statusCode, $message, $data = null) {
        http_response_code($statusCode);
        $response = [
            "status" => $statusCode,
            "message" => $message
        ];
        if ($data !== null) {
            $response["data"] = $data;
        }
        echo json_encode($response);
        exit(); // Bắt buộc dùng exit() để chặn mã HTML sinh ra
    }
}
?>