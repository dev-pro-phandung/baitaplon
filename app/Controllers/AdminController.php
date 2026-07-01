<?php
// Gọi tất cả các Model cần dùng để tránh lỗi "Class not found"
require_once __DIR__ . '/../Models/OrderModel.php';
require_once __DIR__ . '/../Models/ProductModel.php';
require_once __DIR__ . '/../Models/CategoryModel.php';
require_once __DIR__ . '/../Models/BannerModel.php';
require_once __DIR__ . '/../Models/BrandModel.php';
require_once __DIR__ . '/../Models/UserModel.php';

class AdminController {

    // --- MIDDLEWARE: KIỂM TRA QUYỀN ADMIN ---
    // Hàm này chạy đầu tiên trong mọi chức năng để bảo mật
    private function checkAdmin() {
        // Nếu chưa đăng nhập HOẶC role không phải là 1 (Admin)
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
            echo "<script>
                alert('Bạn không có quyền truy cập vào trang quản trị!'); 
                window.location.href='index.php?act=login';
            </script>";
            exit();
        }
    }

    // ======================================================
    // 1. DASHBOARD & TỔNG QUAN
    // ======================================================
    public function index() {
        $this->checkAdmin();
        $orderModel = new OrderModel();
        
        // Lấy danh sách đơn hàng mới nhất để hiện ở Dashboard
        $orders = $orderModel->getAllOrders(); 
        
        $data = ['orders' => $orders, 'page_title' => 'Tổng quan - Dashboard'];
        $view = 'admin/dashboard';
        require_once __DIR__ . '/../Views/admin/layout.php';
    }

    // ======================================================
    // 2. QUẢN LÝ ĐƠN HÀNG
    // ======================================================
    public function viewOrder() {
        $this->checkAdmin();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        $orderModel = new OrderModel();
        $order = $orderModel->getOrderById($id);
        $details = $orderModel->getOrderDetails($id);
        
        if (!$order) {
            echo "<script>alert('Đơn hàng không tồn tại!'); window.location.href='index.php?act=admin';</script>";
            exit();
        }
        
        $data = ['order' => $order, 'details' => $details, 'page_title' => 'Chi tiết đơn hàng #' . $id];
        $view = 'admin/order_detail'; 
        require_once __DIR__ . '/../Views/admin/layout.php';
    }

    public function updateOrderStatus() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['order_id'];
            $status = $_POST['status'];
            
            $orderModel = new OrderModel();
            $orderModel->updateStatus($id, $status);
            
            echo "<script>alert('Cập nhật trạng thái thành công!'); window.location.href='index.php?act=admin_order_detail&id=$id';</script>";
        }
    }

    // ======================================================
    // 3. QUẢN LÝ SẢN PHẨM (CÓ TÌM KIẾM)
    // ======================================================
    public function listProducts() {
        $this->checkAdmin();
        $productModel = new ProductModel();

        // 1. Lấy từ khóa tìm kiếm từ URL
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        
        // 2. Gọi hàm getAll (đã sửa bên Model để nhận keyword)
        $products = $productModel->getAll($keyword);

        $title = ($keyword != '') ? "Kết quả tìm kiếm: '$keyword'" : "Danh sách sản phẩm";

        $data = [
            'products' => $products, 
            'page_title' => $title,
            'keyword' => $keyword // Truyền lại để hiển thị ở ô input
        ];
        $view = 'admin/product/list';
        require_once __DIR__ . '/../Views/admin/layout.php';
    }

    // Form thêm sản phẩm
    public function addProduct() {
        $this->checkAdmin();
        $cateModel = new CategoryModel();
        $categories = $cateModel->getAll();
        
        $brandModel = new BrandModel();
        $brands = $brandModel->getAll();

        $data = ['categories' => $categories, 'brands' => $brands, 'page_title' => 'Thêm sản phẩm mới'];
        $view = 'admin/product/add'; 
        require_once __DIR__ . '/../Views/admin/layout.php';
    }

    // Xử lý lưu sản phẩm mới
    public function storeProduct() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $price = $_POST['price'];
            $old_price = !empty($_POST['old_price']) ? $_POST['old_price'] : 0;
            $discount = !empty($_POST['discount']) ? $_POST['discount'] : 0;
            $unit = $_POST['unit'];
            $category_id = $_POST['category_id'];
            $brand_id = $_POST['brand_id'];
            $quantity = (int)$_POST['quantity']; 
            $views = !empty($_POST['views']) ? $_POST['views'] : 0;
            $is_new = isset($_POST['is_new']) ? 1 : 0;
            $description = $_POST['description'];

            // Xử lý ảnh
            $imageName = $this->processUploadImage($_FILES['image']);

            $productModel = new ProductModel();
            $result = $productModel->insert($name, $price, $old_price, $discount, $imageName, $unit, $category_id, $brand_id, $quantity, $views, $is_new, $description);

            if ($result) echo "<script>alert('Thêm sản phẩm thành công!'); window.location.href='index.php?act=admin_product_list';</script>";
            else echo "<script>alert('Lỗi thêm sản phẩm.'); history.back();</script>";
        }
    }

    // Form sửa sản phẩm
    public function editProduct() {
        $this->checkAdmin();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $productModel = new ProductModel();
        $product = $productModel->getOne($id);
        
        if (!$product) { 
             echo "<script>alert('Sản phẩm không tồn tại!'); window.location.href='index.php?act=admin_product_list';</script>";
             exit;
        }
        
        $cateModel = new CategoryModel();
        $categories = $cateModel->getAll();

        $brandModel = new BrandModel();
        $brands = $brandModel->getAll();
        
        $data = ['product' => $product, 'categories' => $categories, 'brands' => $brands, 'page_title' => 'Sửa sản phẩm'];
        $view = 'admin/product/edit';
        require_once __DIR__ . '/../Views/admin/layout.php';
    }

    // Xử lý cập nhật sản phẩm
    public function updateProduct() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $price = $_POST['price'];
            $old_price = $_POST['old_price'];
            $discount = $_POST['discount'];
            $unit = $_POST['unit'];
            $category_id = $_POST['category_id'];
            $brand_id = $_POST['brand_id'];
            $quantity = (int)$_POST['quantity']; 
            $views = $_POST['views'];
            $is_new = isset($_POST['is_new']) ? 1 : 0;
            $description = $_POST['description'];
            
            // Giữ ảnh cũ nếu không up ảnh mới
            $imageName = $_POST['current_image']; 
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $imageName = $this->processUploadImage($_FILES['image']);
            }

            $productModel = new ProductModel();
            $productModel->update($id, $name, $price, $old_price, $discount, $imageName, $unit, $category_id, $brand_id, $quantity, $views, $is_new, $description);
            
            echo "<script>alert('Cập nhật thành công!'); window.location.href='index.php?act=admin_product_list';</script>";
        }
    }

    public function deleteProduct() {
        $this->checkAdmin();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $productModel = new ProductModel();
        $productModel->delete($id);
        echo "<script>alert('Đã xóa sản phẩm!'); window.location.href='index.php?act=admin_product_list';</script>";
    }

    public function detailProduct() {
        $this->checkAdmin();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $productModel = new ProductModel();
        $product = $productModel->getOne($id);
        
        // Lấy tên danh mục để hiển thị
        $cateModel = new CategoryModel();
        $category = $cateModel->getOne($product['category_id']); 
        
        $data = ['product' => $product, 'category_name' => $category ? $category['name'] : 'N/A', 'page_title' => 'Chi tiết sản phẩm'];
        $view = 'admin/product/detail';
        require_once __DIR__ . '/../Views/admin/layout.php';
    }

    // ======================================================
    // 4. QUẢN LÝ BANNER
    // ======================================================
    public function listBanners() {
        $this->checkAdmin();
        $bannerModel = new BannerModel();
        $banners = $bannerModel->getAll();
        $data = ['banners' => $banners, 'page_title' => 'Quản lý Banner'];
        $view = 'admin/banner/list';
        require_once __DIR__ . '/../Views/admin/layout.php';
    }

    public function addBanner() {
        $this->checkAdmin();
        $data = ['page_title' => 'Thêm Banner'];
        $view = 'admin/banner/add';
        require_once __DIR__ . '/../Views/admin/layout.php';
    }

    public function storeBanner() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = $_POST['title'];
            $link = $_POST['link'];
            $position = $_POST['position'];
            $imageName = $this->processUploadImage($_FILES['image']);

            $bannerModel = new BannerModel();
            $result = $bannerModel->insert($imageName, $link, $title, $position);

            if ($result) echo "<script>alert('Thêm Banner thành công!'); window.location.href='index.php?act=admin_banner_list';</script>";
            else echo "<script>alert('Lỗi thêm Banner.'); history.back();</script>";
        }
    }

    public function editBanner() {
        $this->checkAdmin();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $bannerModel = new BannerModel();
        $banner = $bannerModel->getOne($id);
        $data = ['banner' => $banner, 'page_title' => 'Sửa Banner'];
        $view = 'admin/banner/edit';
        require_once __DIR__ . '/../Views/admin/layout.php';
    }

    public function updateBanner() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $title = $_POST['title'];
            $link = $_POST['link'];
            $position = $_POST['position'];
            
            $imageName = $_POST['current_image'];
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $imageName = $this->processUploadImage($_FILES['image']);
            }

            $bannerModel = new BannerModel();
            $bannerModel->update($id, $imageName, $link, $title, $position);
            echo "<script>alert('Cập nhật Banner thành công!'); window.location.href='index.php?act=admin_banner_list';</script>";
        }
    }

    public function deleteBanner() {
        $this->checkAdmin();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $bannerModel = new BannerModel();
        $bannerModel->delete($id);
        echo "<script>alert('Đã xóa Banner!'); window.location.href='index.php?act=admin_banner_list';</script>";
    }

    // ======================================================
    // 5. QUẢN LÝ DANH MỤC (CATEGORY)
    // ======================================================
    public function listCategories() {
        $this->checkAdmin();
        $cateModel = new CategoryModel();
        $categories = $cateModel->getAll();
        $data = ['categories' => $categories, 'page_title' => 'Quản lý Danh mục'];
        $view = 'admin/category/list';
        require_once __DIR__ . '/../Views/admin/layout.php';
    }

    public function addCategory() {
        $this->checkAdmin();
        $cateModel = new CategoryModel();
        $categories = $cateModel->getAll(); 
        $data = ['categories' => $categories, 'page_title' => 'Thêm Danh mục'];
        $view = 'admin/category/add';
        require_once __DIR__ . '/../Views/admin/layout.php';
    }

    public function storeCategory() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $parent_id = !empty($_POST['parent_id']) ? $_POST['parent_id'] : 0;
            $imageName = $this->processUploadImage($_FILES['image']);

            $cateModel = new CategoryModel();
            $result = $cateModel->insert($name, $imageName, $parent_id);

            if ($result) echo "<script>alert('Thêm Danh mục thành công!'); window.location.href='index.php?act=admin_category_list';</script>";
            else echo "<script>alert('Lỗi thêm Danh mục.'); history.back();</script>";
        }
    }

    public function editCategory() {
        $this->checkAdmin();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $cateModel = new CategoryModel();
        $category = $cateModel->getOne($id);
        $allCats = $cateModel->getAll();

        if (!$category) {
            echo "<script>alert('Danh mục không tồn tại!'); window.location.href='index.php?act=admin_category_list';</script>";
            exit();
        }

        $data = ['category' => $category, 'categories' => $allCats, 'page_title' => 'Sửa Danh mục'];
        $view = 'admin/category/edit';
        require_once __DIR__ . '/../Views/admin/layout.php';
    }

    public function updateCategory() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $parent_id = !empty($_POST['parent_id']) ? $_POST['parent_id'] : 0;
            
            $imageName = $_POST['current_image'];
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $imageName = $this->processUploadImage($_FILES['image']);
            }

            $cateModel = new CategoryModel();
            $cateModel->update($id, $name, $imageName, $parent_id);
            echo "<script>alert('Cập nhật Danh mục thành công!'); window.location.href='index.php?act=admin_category_list';</script>";
        }
    }

    public function deleteCategory() {
        $this->checkAdmin();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $cateModel = new CategoryModel();
        $cateModel->delete($id);
        echo "<script>alert('Đã xóa Danh mục!'); window.location.href='index.php?act=admin_category_list';</script>";
    }

    // ======================================================
    // 6. QUẢN LÝ THƯƠNG HIỆU (BRAND)
    // ======================================================
    public function listBrands() {
        $this->checkAdmin();
        $brandModel = new BrandModel();
        $brands = $brandModel->getAll();
        $data = ['brands' => $brands, 'page_title' => 'Quản lý Thương hiệu'];
        $view = 'admin/brand/list';
        require_once __DIR__ . '/../Views/admin/layout.php';
    }

    public function addBrand() {
        $this->checkAdmin();
        $data = ['page_title' => 'Thêm Thương hiệu mới'];
        $view = 'admin/brand/add';
        require_once __DIR__ . '/../Views/admin/layout.php';
    }

    public function storeBrand() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            
            $imageName = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $imageName = $this->processUploadImage($_FILES['image']);
            }

            $brandModel = new BrandModel();
            $brandModel->insert($name, $imageName, $description);
            echo "<script>alert('Thêm thương hiệu thành công!'); window.location.href='index.php?act=admin_brand_list';</script>";
        }
    }

    public function editBrand() {
        $this->checkAdmin();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $brandModel = new BrandModel();
        $brand = $brandModel->getOne($id);
        
        $data = ['brand' => $brand, 'page_title' => 'Sửa Thương hiệu'];
        $view = 'admin/brand/edit';
        require_once __DIR__ . '/../Views/admin/layout.php';
    }

    public function updateBrand() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            
            $imageName = $_POST['current_image'];
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $imageName = $this->processUploadImage($_FILES['image']);
            }

            $brandModel = new BrandModel();
            $brandModel->update($id, $name, $imageName, $description);
            echo "<script>alert('Cập nhật thành công!'); window.location.href='index.php?act=admin_brand_list';</script>";
        }
    }

    public function deleteBrand() {
        $this->checkAdmin();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $brandModel = new BrandModel();
        $brandModel->delete($id);
        echo "<script>alert('Đã xóa thương hiệu!'); window.location.href='index.php?act=admin_brand_list';</script>";
    }

    // ======================================================
    // 7. QUẢN LÝ TÀI KHOẢN (USER)
    // ======================================================
    public function listUsers() {
        $this->checkAdmin();
        
        $userModel = new UserModel();
        $users = $userModel->getAllUsers();
        
        $data = ['users' => $users, 'page_title' => 'Quản lý Khách hàng'];
        $view = 'admin/user/list';
        require_once __DIR__ . '/../Views/admin/layout.php';
    }

    public function deleteUser() {
        $this->checkAdmin();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        $userModel = new UserModel();
        $userModel->delete($id);
        
        echo "<script>alert('Đã xóa tài khoản!'); window.location.href='index.php?act=admin_user_list';</script>";
    }

    // ======================================================
    // 8. THỐNG KÊ (STATISTICS)
    // ======================================================
    public function statistics() {
        $this->checkAdmin();
        
        $orderModel = new OrderModel();
        $productModel = new ProductModel();

        // Lấy dữ liệu thống kê từ Model
        $revenue = $orderModel->getTotalRevenue();      // Tổng doanh thu
        $orderCount = $orderModel->getCountSuccessOrders(); // Tổng số đơn
        $topSelling = $productModel->getTopSelling();   // SP bán chạy
        $unsold = $productModel->getUnsoldProducts();   // SP tồn kho nhiều/ít
        
        $data = [
            'revenue' => $revenue,
            'order_count' => $orderCount,
            'top_selling' => $topSelling,
            'unsold' => $unsold,
            'page_title' => 'Báo cáo thống kê'
        ];
        
        $view = 'admin/statistics';
        require_once __DIR__ . '/../Views/admin/layout.php';
    }

    // ======================================================
    // HÀM HỖ TRỢ (HELPER)
    // ======================================================
    private function processUploadImage($fileInput) {
        $imageName = '';
        if (isset($fileInput) && $fileInput['error'] == 0) {
            // Đặt tên file theo thời gian để tránh trùng lặp
            $imageName = time() . '_' . basename($fileInput['name']);
            $targetPath = __DIR__ . '/../../public/img/' . $imageName;
            
            // Di chuyển file từ bộ nhớ tạm vào thư mục img
            if(move_uploaded_file($fileInput['tmp_name'], $targetPath)) {
                return $imageName;
            }
        }
        return $imageName;
    }
}
?>