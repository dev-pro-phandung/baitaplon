<?php
require_once __DIR__ . '/../Models/ProductModel.php';
require_once __DIR__ . '/../Models/CategoryModel.php';

class ProductController {
    
    public function detail() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        $productModel = new ProductModel();
        $product = $productModel->getOne($id);

        // Kiểm tra sản phẩm có tồn tại không
        if (!$product) {
            require_once __DIR__ . '/../Views/inc/header.php';
            echo "<div style='padding:50px; text-align:center'><h2>Sản phẩm không tồn tại!</h2></div>";
            require_once __DIR__ . '/../Views/inc/footer.php';
            return;
        }

       // Ví dụ trong Controller
$related = $productModel->getRelated($product['category_id'], $id);

        $data = [
            'product' => $product,
            'related_products' => $related,
            'page_title' => $product['name']
        ];

        // --- QUAN TRỌNG: TRỎ ĐÚNG VÀO VIEWS/PRODUCT ---
        $viewPath = __DIR__ . '/../Views/product/detail.php';

        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            echo "Lỗi: Không tìm thấy file view tại " . $viewPath;
        }
    }

    // CHỨC NĂNG TÌM KIẾM
    public function search() {
        // Lấy từ khóa từ URL (GET)
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        
        $products = [];
        if (!empty($keyword)) {
            $productModel = new ProductModel();
            $products = $productModel->searchByName($keyword);
        }

        // Gửi dữ liệu sang View
        $data = [
            'products' => $products,
            'keyword' => $keyword,
            'page_title' => 'Tìm kiếm: ' . $keyword
        ];

        require_once __DIR__ . '/../Views/product/search.php'; // Tạo file này ở bước 4
    }
    public function filter() {
        require_once __DIR__ . '/../Models/ProductModel.php';
        require_once __DIR__ . '/../Models/CategoryModel.php';

        $productModel = new ProductModel();
        $cateModel = new CategoryModel();

        // Lấy tham số từ URL (GET)
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        $cat_id = isset($_GET['cat_id']) ? $_GET['cat_id'] : '';
        $price_min = isset($_GET['price_min']) ? $_GET['price_min'] : '';
        $price_max = isset($_GET['price_max']) ? $_GET['price_max'] : '';
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

        // Gọi hàm lọc trong Model
        $products = $productModel->getFilteredProducts($keyword, $cat_id, $price_min, $price_max, $sort);
        $categories = $cateModel->getAll(); // Để hiển thị list danh mục lọc

        $data = [
            'products' => $products,
            'categories' => $categories,
            'page_title' => 'Tìm kiếm sản phẩm',
            // Truyền lại các biến để giữ giá trị trên form
            'filters' => [
                'keyword' => $keyword, 'cat_id' => $cat_id, 
                'price_min' => $price_min, 'price_max' => $price_max, 'sort' => $sort
            ]
        ];

        require_once __DIR__ . '/../Views/product/filter.php'; // Tạo file này ở bước 3
    }
}
?>