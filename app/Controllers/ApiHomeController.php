<?php
    header('Content-Type: application/json');
    header("Access-Control-Allow-Methods: GET"); // Trang chủ chỉ cần phương thức GET
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Access-Control-Allow-Origin: *");

    // Nhúng các Model cần thiết để lấy dữ liệu
    require_once __DIR__ . '/../Models/ProductModel.php';
    require_once __DIR__ . '/../Models/CategoryModel.php';
    require_once __DIR__ . '/../Models/BannerModel.php'; // Đảm bảo bạn đã có file này nhé

    class ApiHomeController {

        public function getHomeData() {
            // 1. Khởi tạo các object từ Model
            $productModel = new ProductModel();
            $categoryModel = new CategoryModel();
            $bannerModel = new BannerModel();

            // 2. Kéo dữ liệu từ Database lên thông qua các hàm có sẵn
            
            // Hàm getNewArrivals() lấy 4 sản phẩm mới nhất
            $newProducts = $productModel->getNewArrivals(); 
            
            // Hàm getBestSellers() lấy top 50 lượt xem cao nhất
            $bestSellers = $productModel->getBestSellers(); 
            
            // Hàm getSuggestedProducts() lấy 60 sản phẩm (có thể chỉnh số lượng)
            $suggestions = $productModel->getSuggestedProducts(20); 
            
            // Hàm getAll() lấy toàn bộ danh mục sắp xếp theo parent_id
            $categories = $categoryModel->getAll(); 

            // Giả định BannerModel có hàm getAll() giống các Model khác
            // Nếu trong BannerModel bạn dùng tên hàm khác (VD: getSliders), hãy đổi lại dòng này nhé
            $sliders = $bannerModel->getAll();

            // 3. Đóng gói tất cả vào một mảng duy nhất để gửi về JS
            $data = [
                'sliders'      => $sliders,
                'categories'   => $categories,
                'best_sellers' => $bestSellers,
                'new_products' => $newProducts, // Tên key 'new_products' phải khớp với JS file home_api.js
                'suggestions'  => $suggestions
            ];

            // 4. Trả về cho Frontend
            $this->response(200, "Tải dữ liệu trang chủ thành công", $data);
        }

        // ======================================================
        // HÀM HỖ TRỢ TRẢ VỀ JSON CHUẨN
        // ======================================================
        public function response($status_code, $message, $data = null) {
            http_response_code($status_code);
            echo json_encode([
                'status'  => $status_code,
                'message' => $message,
                'data'    => $data
            ]);
            exit(); // Dừng hoàn toàn luồng PHP để không bị in lẫn HTML
        }
    }
?>