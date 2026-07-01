<?php
// =========================================================
// API BẢO MẬT & CẤU HÌNH HEADER (CORS)
// =========================================================
header('Content-Type: application/json; charset=UTF-8');
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT"); 
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Origin: *");

require_once __DIR__ . '/../Models/BrandModel.php';

class ApiBrandController {
    private $brandModel;

    public function __construct() {
        $this->brandModel = new BrandModel();
    }

    // =========================================================
    // ĐIỀU HƯỚNG API (ROUTER NỘI BỘ)
    // =========================================================
    public function handleRequest($act) {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($act) {
            case 'api_brands': // Lấy danh sách thương hiệu
                if ($method == 'GET') $this->getAllBrands();
                break;
            
            case 'api_brand_add': // Thêm thương hiệu mới
                if ($method == 'POST') $this->addBrand();
                break;

            case 'api_brand_detail': // Xem chi tiết 1 thương hiệu
                if ($method == 'GET') $this->getOne();
                break;

            case 'api_brand_update': // Cập nhật thương hiệu
                if ($method == 'POST') $this->updateBrand();
                break;

            case 'api_brand_delete': // Xóa thương hiệu
                if ($method == 'POST' || $method == 'DELETE') $this->deleteBrand();
                break;

            default:
                $this->response(404, "Không tìm thấy Endpoint API Thương hiệu.");
                break;
        }
    }

    // =========================================================
    // CÁC HÀM XỬ LÝ CHÍNH
    // =========================================================

    // 1. Lấy tất cả thương hiệu
    private function getAllBrands() {
        $brands = $this->brandModel->getAll(); 
        if ($brands) {
            $this->response(200, "Lấy danh sách thành công", ['brands' => $brands]);
        } else {
            $this->response(404, "Chưa có thương hiệu nào", ['brands' => []]);
        }
    }

    private function getOne() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            $brand = $this->brandModel->getOne($id);
            if ($brand) {
                $this->response(200, "Lấy chi tiết thương hiệu thành công", ['brand' => $brand]);
            } else {
                $this->response(404, "Không tìm thấy thương hiệu với ID này");
            }
        } else {
            $this->response(400, "ID thương hiệu không hợp lệ");
        }
    }

    // 2. Thêm thương hiệu mới
    private function addBrand() {
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        // BẢN VÁ: Lấy thêm mô tả thương hiệu
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';

        // Xử lý Upload Ảnh (Logo thương hiệu)
        $imageName = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $imageName = time() . '_' . basename($_FILES['image']['name']);
            $targetPath = __DIR__ . '/../../public/img/' . $imageName;
            move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
        }

        if (empty($name)) {
            $this->response(400, "Tên thương hiệu không được để trống");
        }

        // Gọi insert khớp với BrandModel: insert($name, $image, $description)
        $result = $this->brandModel->insert($name, $imageName, $description);
        
        if ($result) {
            $this->response(201, "Thêm thương hiệu thành công");
        } else {
            $this->response(500, "Lỗi Server: Không thể thêm thương hiệu");
        }
    }

    // 3. Cập nhật thương hiệu
    private function updateBrand() {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        if ($id <= 0) $this->response(400, "Thiếu ID thương hiệu cần sửa");

        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        // BẢN VÁ: Lấy thêm mô tả thương hiệu
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        
        if (empty($name)) {
            $this->response(400, "Tên thương hiệu không được để trống");
        }

        // Xử lý ảnh mới nếu có
        $imageName = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $imageName = time() . '_' . basename($_FILES['image']['name']);
            $targetPath = __DIR__ . '/../../public/img/' . $imageName;
            move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
        }

        // Gọi update khớp với BrandModel: update($id, $name, $image, $description)
        $result = $this->brandModel->update($id, $name, $imageName, $description);
        
        if ($result) {
            $this->response(200, "Cập nhật thương hiệu thành công");
        } else {
            $this->response(500, "Lỗi Server: Không thể cập nhật");
        }
    }

    // 4. Xóa thương hiệu
    private function deleteBrand() {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : (isset($_GET['id']) ? (int)$_GET['id'] : 0);
        
        if ($id > 0) {
            $result = $this->brandModel->delete($id);
            if ($result) {
                $this->response(200, "Xóa thương hiệu thành công");
            } else {
                $this->response(500, "Lỗi Server: Không thể xóa");
            }
        } else {
            $this->response(400, "ID thương hiệu không hợp lệ");
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
        exit(); 
    }
}
?>