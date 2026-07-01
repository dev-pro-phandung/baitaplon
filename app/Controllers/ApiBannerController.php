<?php
// =========================================================
// API BẢO MẬT & CẤU HÌNH HEADER (CORS)
// =========================================================
header('Content-Type: application/json; charset=UTF-8');
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT"); 
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Origin: *");

// Gọi đúng Model của Banner
require_once __DIR__ . '/../Models/BannerModel.php';

class ApiBannerController {
    private $bannerModel;

    public function __construct() {
        $this->bannerModel = new BannerModel();
    }

    // =========================================================
    // ĐIỀU HƯỚNG API (ROUTER NỘI BỘ)
    // =========================================================
    public function handleRequest($act) {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($act) {
            case 'api_banners': // Lấy danh sách banner
                if ($method == 'GET') $this->getAllBanners();
                break;

            case 'api_banner_detail': // Lấy chi tiết 1 banner để Sửa
                if ($method == 'GET') $this->getBannerDetail();
                break;
            
            case 'api_banner_add': // Thêm banner mới
                if ($method == 'POST') $this->addBanner();
                break;

            case 'api_banner_update': // Cập nhật banner
                if ($method == 'POST') $this->updateBanner();
                break;

            case 'api_banner_delete': // Xóa banner
                if ($method == 'POST' || $method == 'DELETE') $this->deleteBanner();
                break;

            default:
                $this->response(404, "Không tìm thấy Endpoint API Banner.");
                break;
        }
    }

    // =========================================================
    // CÁC HÀM XỬ LÝ CHÍNH
    // =========================================================

    // 1. Lấy tất cả banner
    private function getAllBanners() {
        $banners = $this->bannerModel->getAll(); 
        
        if ($banners) {
            $this->response(200, "Lấy danh sách thành công", ['banners' => $banners]);
        } else {
            $this->response(404, "Chưa có banner nào", ['banners' => []]);
        }
    }

    // 2. Lấy chi tiết banner
    private function getBannerDetail() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            $banner = $this->bannerModel->getOne($id);
            if ($banner) {
                $this->response(200, "Thành công", ['banner' => $banner]);
            } else {
                $this->response(404, "Không tìm thấy banner");
            }
        } else {
            $this->response(400, "ID không hợp lệ");
        }
    }

    // 3. Thêm banner mới
    private function addBanner() {
        $title = isset($_POST['title']) ? trim($_POST['title']) : '';
        $link = isset($_POST['link']) ? trim($_POST['link']) : '';
        $position = isset($_POST['position']) ? trim($_POST['position']) : '';

        // Xử lý Upload Ảnh
        $imageName = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $imageName = time() . '_' . basename($_FILES['image']['name']);
            $targetPath = __DIR__ . '/../../public/img/' . $imageName;
            move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
        }

        if (empty($title)) {
            $this->response(400, "Tiêu đề banner không được để trống");
        }

        $result = $this->bannerModel->insert($imageName, $link, $title, $position);
        
        if ($result) {
            $this->response(201, "Thêm banner thành công");
        } else {
            $this->response(500, "Lỗi Server: Không thể thêm banner");
        }
    }

    // 4. Cập nhật banner
    private function updateBanner() {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        if ($id <= 0) $this->response(400, "Thiếu ID banner cần sửa");

        $title = isset($_POST['title']) ? trim($_POST['title']) : '';
        $link = isset($_POST['link']) ? trim($_POST['link']) : '';
        $position = isset($_POST['position']) ? trim($_POST['position']) : '';
        
        if (empty($title)) {
            $this->response(400, "Tiêu đề banner không được để trống");
        }

        // Xử lý ảnh
        $imageName = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $imageName = time() . '_' . basename($_FILES['image']['name']);
            $targetPath = __DIR__ . '/../../public/img/' . $imageName;
            move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
        }

        $result = $this->bannerModel->update($id, $imageName, $link, $title, $position);
        
        if ($result) {
            $this->response(200, "Cập nhật banner thành công");
        } else {
            $this->response(500, "Lỗi Server: Không thể cập nhật banner");
        }
    }

    // 5. Xóa banner
    private function deleteBanner() {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : (isset($_GET['id']) ? (int)$_GET['id'] : 0);
        
        if ($id > 0) {
            $result = $this->bannerModel->delete($id);
            if ($result) {
                $this->response(200, "Xóa banner thành công");
            } else {
                $this->response(500, "Lỗi Server: Không thể xóa banner");
            }
        } else {
            $this->response(400, "ID banner không hợp lệ");
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