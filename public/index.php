<?php
session_start(); // Khởi động Session để lưu Giỏ hàng & Đăng nhập

// [TÙY CHỌN] BẬT HIỆN LỖI (Bỏ comment 2 dòng dưới nếu API báo lỗi 500 hoặc trắng trang)
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

// 1. Định nghĩa đường dẫn gốc
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('BASE_URL', 'http://localhost/baitaplon/'); // Đã sửa lại chuẩn theo thư mục của bạn

// 2. TỰ ĐỘNG ĐĂNG NHẬP (REMEMBER ME)
if (!isset($_SESSION['user']) && isset($_COOKIE['remember_user'])) {
    require_once APP_PATH . '/Models/UserModel.php';
    $userModel = new UserModel();
    $user = $userModel->getById($_COOKIE['remember_user']);
    
    if ($user) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'fullname' => $user['fullname'],
            'phone' => $user['phone'],
            'role' => $user['role']
        ];
    }
}

// Lấy tham số hành động (act)
$act = isset($_GET['act']) ? trim($_GET['act']) : 'home';

// =========================================================================
// PHẦN 1: BỘ ĐỊNH TUYẾN DÀNH RIÊNG CHO API (CHỈ TRẢ VỀ JSON)
// =========================================================================
if (strpos($act, 'api_') === 0) {
    
    // Ép buộc toàn bộ luồng này phải trả về định dạng JSON
    header('Content-Type: application/json; charset=UTF-8');
    
    if (strpos($act, 'api_admin') === 0) {
        require_once APP_PATH . '/Controllers/ApiAdminController.php';
        (new ApiAdminController())->handleRequest($act);
    }
    elseif ($act === 'api_home') {
        require_once APP_PATH . '/Controllers/ApiHomeController.php';
        (new ApiHomeController())->getHomeData();
    }
    elseif ($act === 'api_checkout') {
        require_once APP_PATH . '/Controllers/ApiPayController.php';
        (new ApiPayController())->handleRequest($act);
    }
    elseif (strpos($act, 'api_cart') === 0) {
        require_once APP_PATH . '/Controllers/ApiCartController.php';
        (new ApiCartController())->handleRequest($act);
    }
    elseif (strpos($act, 'api_product') === 0) {
        require_once APP_PATH . '/Controllers/ApiProductController.php';
        (new ApiProductController())->handleRequest($act);
    }
     elseif (strpos($act, 'api_order') === 0) {
        require_once APP_PATH . '/Controllers/ApiOrderController.php';
        (new ApiOrderController())->handleRequest($act);
    }
    elseif (strpos($act, 'api_categor') === 0) {
        require_once APP_PATH . '/Controllers/ApiCategoryController.php';
        (new ApiCategoryController())->handleRequest($act);
    }
    elseif (strpos($act, 'api_banner') === 0) {
    require_once APP_PATH . '/Controllers/ApiBannerController.php';
    (new ApiBannerController())->handleRequest($act);
}
elseif (strpos($act, 'api_brand') === 0) {
    require_once APP_PATH . '/Controllers/ApiBrandController.php';
    (new ApiBrandController())->handleRequest($act);
}
    elseif (strpos($act, 'api_user') === 0 || $act === 'api_register' || $act === 'api_login') {
        require_once APP_PATH . '/Controllers/ApiUserController.php';
        (new ApiUserController())->handleRequest($act);
    }
    else {
        http_response_code(404);
        echo json_encode(['status' => 404, 'message' => 'Endpoint API không tồn tại.']);
    }

    // CHỐT CHẶN: Dừng PHP ngay lập tức, không cho chạy xuống code HTML bên dưới
    exit(); 
}

// =========================================================================
// PHẦN 2: BỘ ĐỊNH TUYẾN GIAO DIỆN HTML (CHỈ LOAD KHUNG HTML & JS)
// =========================================================================

switch ($act) {
    // ---------------- 1. KHÁCH HÀNG ----------------
    case 'home':
        require_once APP_PATH . '/Controllers/HomeController.php';
        (new HomeController())->index();
        break;

    case 'detail':
    case 'search':
    case 'filter':
        require_once APP_PATH . '/Controllers/ProductController.php';
        $productCtrl = new ProductController();
        if ($act === 'detail') $productCtrl->detail();
        else $productCtrl->filter();
        break;

    case 'cart':
    case 'cart_add':
    case 'cart_update':
    case 'cart_delete':
    case 'checkout':
    case 'submit_order':
    case 'history':
    case 'order_tracking':
        require_once APP_PATH . '/Controllers/CartController.php';
        $cartCtrl = new CartController();
        if ($act === 'cart') $cartCtrl->index();
        elseif ($act === 'cart_add') $cartCtrl->add();
        elseif ($act === 'cart_update') $cartCtrl->update();
        elseif ($act === 'cart_delete') $cartCtrl->delete();
        elseif ($act === 'checkout') $cartCtrl->checkout();
        elseif ($act === 'submit_order') $cartCtrl->submitOrder();
        elseif ($act === 'history') $cartCtrl->history();
        elseif ($act === 'order_tracking') $cartCtrl->tracking();
        break;

    // ---------------- 2. XÁC THỰC TÀI KHOẢN ----------------
    case 'login':
    case 'logout':
    case 'register':
    case 'verify_otp':
    case 'check_otp':
    case 'forgot_password':
    case 'send_reset_otp':
    case 'verify_reset_otp':
    case 'reset_password':
        require_once APP_PATH . '/Controllers/AuthController.php';
        $authCtrl = new AuthController();
        if ($act === 'login') $authCtrl->login();
        elseif ($act === 'logout') $authCtrl->logout();
        elseif ($act === 'register') $authCtrl->register();
        elseif ($act === 'verify_otp') $authCtrl->verifyOtpForm();
        elseif ($act === 'check_otp') $authCtrl->verifyOtp();
        elseif ($act === 'forgot_password') $authCtrl->forgotPasswordForm();
        elseif ($act === 'send_reset_otp') $authCtrl->sendResetOtp();
        elseif ($act === 'verify_reset_otp') $authCtrl->verifyResetOtpForm();
        elseif ($act === 'reset_password') $authCtrl->resetPassword();
        break;

    // ---------------- 3. TRANG QUẢN TRỊ ADMIN ----------------
    case 'admin':
    case 'admin_statistics':
    case 'admin_order_detail':
    case 'admin_order_update':
    case 'admin_product_list':
    case 'admin_add_product':
    case 'admin_store_product':
    case 'admin_product_edit':
    case 'admin_product_update':
    case 'admin_product_delete':
    case 'admin_product_detail':
    case 'admin_category_list':
    case 'admin_add_category':
    case 'admin_category_store':
    case 'admin_category_edit':
    case 'admin_category_update':
    case 'admin_category_delete':
    case 'admin_banner_list':
    case 'admin_add_banner':
    case 'admin_banner_store':
    case 'admin_banner_edit':
    case 'admin_banner_update':
    case 'admin_banner_delete':
    case 'admin_brand_list':
    case 'admin_brand_add':
    case 'admin_brand_store':
    case 'admin_brand_edit':
    case 'admin_brand_update':
    case 'admin_brand_delete':
    case 'admin_user_list':
    case 'admin_user_delete':
        require_once APP_PATH . '/Controllers/AdminController.php';
        $adminCtrl = new AdminController();
        
        // Gọi các hàm tương ứng cực kỳ ngắn gọn
        if ($act === 'admin') $adminCtrl->index();
        elseif ($act === 'admin_statistics') $adminCtrl->statistics();
        elseif ($act === 'admin_order_detail') $adminCtrl->viewOrder();
        elseif ($act === 'admin_order_update') $adminCtrl->updateOrderStatus();
        elseif ($act === 'admin_product_list') $adminCtrl->listProducts();
        elseif ($act === 'admin_add_product') $adminCtrl->addProduct();
        elseif ($act === 'admin_store_product') $adminCtrl->storeProduct();
        elseif ($act === 'admin_product_edit') $adminCtrl->editProduct();
        elseif ($act === 'admin_product_update') $adminCtrl->updateProduct();
        elseif ($act === 'admin_product_delete') $adminCtrl->deleteProduct();
        elseif ($act === 'admin_product_detail') $adminCtrl->detailProduct();
        elseif ($act === 'admin_category_list') $adminCtrl->listCategories();
        elseif ($act === 'admin_add_category') $adminCtrl->addCategory();
        elseif ($act === 'admin_category_store') $adminCtrl->storeCategory();
        elseif ($act === 'admin_category_edit') $adminCtrl->editCategory();
        elseif ($act === 'admin_category_update') $adminCtrl->updateCategory();
        elseif ($act === 'admin_category_delete') $adminCtrl->deleteCategory();
        elseif ($act === 'admin_banner_list') $adminCtrl->listBanners();
        elseif ($act === 'admin_add_banner') $adminCtrl->addBanner();
        elseif ($act === 'admin_banner_store') $adminCtrl->storeBanner();
        elseif ($act === 'admin_banner_edit') $adminCtrl->editBanner();
        elseif ($act === 'admin_banner_update') $adminCtrl->updateBanner();
        elseif ($act === 'admin_banner_delete') $adminCtrl->deleteBanner();
        elseif ($act === 'admin_brand_list') $adminCtrl->listBrands();
        elseif ($act === 'admin_brand_add') $adminCtrl->addBrand();
        elseif ($act === 'admin_brand_store') $adminCtrl->storeBrand();
        elseif ($act === 'admin_brand_edit') $adminCtrl->editBrand();
        elseif ($act === 'admin_brand_update') $adminCtrl->updateBrand();
        elseif ($act === 'admin_brand_delete') $adminCtrl->deleteBrand();
        elseif ($act === 'admin_user_list') $adminCtrl->listUsers();
        elseif ($act === 'admin_user_delete') $adminCtrl->deleteUser();
        break;

    // ---------------- 4. MẶC ĐỊNH (TRANG CHỦ HOẶC LỖI) ----------------
    default:
        require_once APP_PATH . '/Controllers/HomeController.php';
        (new HomeController())->index();
        break;
}
?>