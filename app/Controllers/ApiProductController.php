<?php
// =========================================================
// API BẢO MẬT & CẤU HÌNH HEADER (CORS)
// =========================================================
header("Access-Control-Allow-Origin: *"); 
header("Content-Type: application/json; charset=UTF-8"); 
header("Access-Control-Allow-Methods: GET, POST, DELETE"); 
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../Models/ProductModel.php';

class ApiProductController {
    private $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
    }

    // =========================================================
    // ĐIỀU HƯỚNG API (ROUTER NỘI BỘ)
    // =========================================================
    public function handleRequest($act) {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($act) {
            case 'api_products': // Lấy danh sách hoặc tìm kiếm cơ bản
                if ($method == 'GET') $this->getProducts();
                else $this->response(405, "Cần sử dụng phương thức GET");
                break;
            
            case 'api_products_filter': // Lọc nâng cao (Giá, Danh mục, Sắp xếp)
                if ($method == 'GET') $this->filterProducts();
                break;

            case 'api_product_detail': // Xem chi tiết 1 sản phẩm & SP liên quan
                if ($method == 'GET') $this->getProductDetail();
                break;

            case 'api_products_by_category': // Lấy sản phẩm theo danh mục (Có hỗ trợ limit)
                if ($method == 'GET') $this->getProductsByCategory();
                break;

            case 'api_product_add': // Thêm sản phẩm mới (Dùng POST vì có upload ảnh)
                if ($method == 'POST') $this->addProduct();
                break;

            case 'api_product_update': // Sửa sản phẩm (Dùng POST để gửi Form Data chứa ảnh)
                if ($method == 'POST') $this->updateProduct();
                break;

            case 'api_product_delete': // Xóa sản phẩm
                // Lưu ý: Nếu JS dùng POST để xóa thì đổi 'DELETE' thành 'POST' ở đây nhé
                if ($method == 'DELETE' || $method == 'POST') $this->deleteProduct();
                break;

            default:
                $this->response(404, "Không tìm thấy Endpoint API.");
                break;
        }
    }

    // =========================================================
    // CÁC HÀM XỬ LÝ CHÍNH
    // =========================================================

    // 1. Lấy danh sách (Hỗ trợ tìm kiếm theo tên)
    private function getProducts() {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $products = $this->productModel->getAll($keyword);
        
    
        $data = [
            'keyword' => $keyword,
            'products' => $products
        ];

        if ($products) {
            $this->response(200, "Lấy danh sách thành công", $data);
        } else {
            $this->response(404, "Không có sản phẩm nào", $data);
        }
    }

    // 2. Lọc sản phẩm nâng cao
    private function filterProducts() {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $cat_id = isset($_GET['cat_id']) ? (int)$_GET['cat_id'] : '';
        $price_min = isset($_GET['price_min']) ? $_GET['price_min'] : '';
        $price_max = isset($_GET['price_max']) ? $_GET['price_max'] : '';
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

        $products = $this->productModel->getFilteredProducts($keyword, $cat_id, $price_min, $price_max, $sort);
        $this->response(200, "Lọc sản phẩm thành công", ['products' => $products]);
    }

    // 3. Lấy chi tiết 1 sản phẩm
    private function getProductDetail() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($id > 0) {
            $product = $this->productModel->getOne($id);
            if ($product) {
                $related = $this->productModel->getRelated($product['category_id'], $id);
                $data = [
                    'info' => $product,
                    'related' => $related
                ];
                $this->response(200, "Thành công", $data);
            } else {
                $this->response(404, "Sản phẩm không tồn tại");
            }
        } else {
            $this->response(400, "Vui lòng truyền ID sản phẩm hợp lệ");
        }
    }

    // 4. Thêm sản phẩm
    private function addProduct() {
        $name = $_POST['name'] ?? '';
        $price = isset($_POST['price']) ? (int)$_POST['price'] : 0;
        $old_price = isset($_POST['old_price']) ? (int)$_POST['old_price'] : 0;
        $discount = isset($_POST['discount']) ? (int)$_POST['discount'] : 0;
        $unit = $_POST['unit'] ?? 'Cái';
        $category_id = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;
        $brand_id = isset($_POST['brand_id']) ? (int)$_POST['brand_id'] : 0;
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
        $views = isset($_POST['views']) ? (int)$_POST['views'] : 0;
        $is_new = isset($_POST['is_new']) ? 1 : 0;
        $description = $_POST['description'] ?? '';

        $imageName = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $imageName = time() . '_' . basename($_FILES['image']['name']);
            $targetPath = __DIR__ . '/../../public/img/' . $imageName;
            move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
        }

        if (empty($name) || $price <= 0) {
            $this->response(400, "Tên và Giá sản phẩm là bắt buộc");
        }

        $result = $this->productModel->insert($name, $price, $old_price, $discount, $imageName, $unit, $category_id, $brand_id, $quantity, $views, $is_new, $description);
        
        if ($result) {
            $this->response(201, "Thêm sản phẩm thành công");
        } else {
            $this->response(500, "Lỗi Server: Không thể thêm sản phẩm");
        }
    }

    // 5. Cập nhật sản phẩm
    private function updateProduct() {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        if ($id <= 0) $this->response(400, "Thiếu ID sản phẩm cần sửa");

        $name = $_POST['name'] ?? '';
        $price = isset($_POST['price']) ? (int)$_POST['price'] : 0;
        $old_price = isset($_POST['old_price']) ? (int)$_POST['old_price'] : 0;
        $discount = isset($_POST['discount']) ? (int)$_POST['discount'] : 0;
        $unit = $_POST['unit'] ?? 'Cái';
        $category_id = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;
        $brand_id = isset($_POST['brand_id']) ? (int)$_POST['brand_id'] : 0;
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
        $views = isset($_POST['views']) ? (int)$_POST['views'] : 0;
        $is_new = isset($_POST['is_new']) ? 1 : 0;
        $description = $_POST['description'] ?? '';

        $imageName = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $imageName = time() . '_' . basename($_FILES['image']['name']);
            $targetPath = __DIR__ . '/../../public/img/' . $imageName;
            move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
        }

        $result = $this->productModel->update($id, $name, $price, $old_price, $discount, $imageName, $unit, $category_id, $brand_id, $quantity, $views, $is_new, $description);
        
        if ($result) {
            $this->response(200, "Cập nhật sản phẩm thành công");
        } else {
            $this->response(500, "Lỗi Server: Không thể cập nhật");
        }
    }

    // 6. Xóa sản phẩm
    private function deleteProduct() {
        // Hỗ trợ cả gửi ID qua URL ($_GET) và gửi qua Body FormData ($_POST)
        $id = isset($_POST['id']) ? (int)$_POST['id'] : (isset($_GET['id']) ? (int)$_GET['id'] : 0);
        
        if ($id > 0) {
            $result = $this->productModel->delete($id);
            if ($result) {
                $this->response(200, "Xóa sản phẩm thành công");
            } else {
                $this->response(500, "Lỗi Server: Không thể xóa");
            }
        } else {
            $this->response(400, "ID sản phẩm không hợp lệ");
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
        exit(); // Dừng luồng ngay lập tức
    }
}
?>