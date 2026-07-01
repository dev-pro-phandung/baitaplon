<?php
// Gọi các file Model cần thiết để lấy dữ liệu từ Database
require_once __DIR__ . '/../Models/ProductModel.php';
require_once __DIR__ . '/../Models/CategoryModel.php';
require_once __DIR__ . '/../Models/BannerModel.php';

class HomeController {
    
    // Hàm chạy mặc định khi vào trang chủ
    public function index() {
        // ---------------------------------------------------------
        // BƯỚC 1: KHỞI TẠO CÁC MODEL
        // ---------------------------------------------------------
        $productModel = new ProductModel();   // Xử lý sản phẩm
        $categoryModel = new CategoryModel(); // Xử lý danh mục
        $bannerModel = new BannerModel();     // Xử lý banner/slider

        // ---------------------------------------------------------
        // BƯỚC 2: LẤY DỮ LIỆU TỪ DATABASE
        // ---------------------------------------------------------
        
        // Lấy TẤT CẢ danh mục (Cả cha và con) để hiển thị Menu đa cấp
        $categories = $categoryModel->getAll(); 
        
        // Lấy sản phẩm bán chạy
        $bestSellers = $productModel->getBestSellers();
        
        // Lấy sản phẩm mới về
        $newProducts = $productModel->getNewArrivals();
        
        // Lấy sản phẩm gợi ý (Hiện tại lấy tất cả, sau này có thể lọc random)
        $suggestions = $productModel->getSuggestedProducts(60);
        

        // ---------------------------------------------------------
        // BƯỚC 3: XỬ LÝ BANNER & SLIDER
        // ---------------------------------------------------------
        // Lấy tất cả banner đang hoạt động (status = 1)
        $allBanners = $bannerModel->getAllActive();
        
        $sliders = [];      // Mảng chứa banner chạy ngang ở đầu trang
        $midBanners = [];   // Mảng chứa banner quảng cáo ở giữa trang

        // Phân loại banner dựa vào cột 'position' trong database
        if (!empty($allBanners)) {
            foreach ($allBanners as $banner) {
                // Nếu vị trí là 'slider' -> Đưa vào danh sách Slider đầu trang
                if ($banner['position'] == 'slider') {
                    $sliders[] = $banner;
                } 
                // Các trường hợp còn lại -> Đưa vào danh sách Banner giữa trang
                else {
                    $midBanners[] = $banner;
                }
            }
        }

        // ---------------------------------------------------------
        // BƯỚC 4: ĐÓNG GÓI DỮ LIỆU ĐỂ GỬI SANG VIEW
        // ---------------------------------------------------------
        $data = [
            'page_title'       => 'Trang chủ - Mini Mart', // Tiêu đề thẻ tab trình duyệt
            'categories'       => $categories,      // Dữ liệu cho Menu
            'sliders'          => $sliders,         // Dữ liệu cho Slider chạy ngang
            'mid_banners'      => $midBanners,      // Dữ liệu cho Banner quảng cáo
            'best_sellers'     => $bestSellers,     // Dữ liệu mục "Bán chạy"
            'new_products'     => $newProducts,     // Dữ liệu mục "Sản phẩm mới"
            'suggestions' => $suggestions // Dữ liệu mục "Gợi ý"
        ];

        // ---------------------------------------------------------
        // BƯỚC 5: GỌI GIAO DIỆN (VIEW) HIỂN THỊ
        // ---------------------------------------------------------
        $viewPath = __DIR__ . '/../Views/home/index.php';
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            // Thông báo lỗi nếu quên tạo file View
            echo "Lỗi hệ thống: Không tìm thấy file giao diện trang chủ tại: " . $viewPath;
        }
    }
}
?>