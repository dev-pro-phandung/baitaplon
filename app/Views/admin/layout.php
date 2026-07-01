<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang Quản Trị - Mini Mart</title>
    <link rel="stylesheet" href="css/admin.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* CSS nội bộ (giữ lại để đảm bảo giao diện không bị vỡ nếu file css ngoài lỗi) */
        body { font-family: Arial, sans-serif; margin: 0; display: flex; background: #f4f6f9; min-height: 100vh; }
        .sidebar { width: 250px; background: #343a40; color: white; flex-shrink: 0; min-height: 100vh; }
        .sidebar h2 { text-align: center; margin: 20px 0; color: #d0011b; text-transform: uppercase; font-size: 20px; border-bottom: 1px solid #4b545c; padding-bottom: 20px; }
        .sidebar ul { list-style: none; padding: 0; }
        .sidebar ul li { border-bottom: 1px solid #4b545c; }
        .sidebar ul li a { color: #c2c7d0; text-decoration: none; display: block; padding: 15px 20px; transition: 0.3s; }
        .sidebar ul li a:hover { background: #494e53; color: white; padding-left: 25px; }
        
        .main-content { flex: 1; padding: 20px; overflow-y: auto; }
        .header { background: white; padding: 15px 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        
        .card { background: white; padding: 25px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        /* Các class bổ trợ */
        .btn-primary { background: #d0011b; color: white; padding: 8px 15px; border-radius: 4px; text-decoration: none; }
        .btn-action { padding: 5px 10px; border-radius: 3px; color: white; text-decoration: none; font-size: 12px; margin-right: 5px;}
    </style>
</head>
<body>
    <div class="sidebar">
        <h2><i class="fas fa-user-shield"></i> Admin CP</h2>
        <ul>
            <li><a href="index.php?act=admin"><i class="fas fa-tachometer-alt"></i> Tổng quan</a></li>
            
            <li>
                <a href="#" style="font-weight:bold; background:#444;">
                    <i class="fas fa-list"></i> QUẢN LÝ DANH MỤC
                </a>
                <ul style="padding-left: 20px; background: #3c4248;">
                    <li style="border:none"><a href="index.php?act=admin_category_list"><i class="fas fa-table"></i> Danh sách</a></li>
                    <li style="border:none"><a href="index.php?act=admin_add_category"><i class="fas fa-plus"></i> Thêm mới</a></li>
                </ul>
            </li>

            <li>
                <a href="#" style="font-weight:bold; background:#444;">
                    <i class="fas fa-box"></i> QUẢN LÝ SẢN PHẨM
                </a>
                <ul style="padding-left: 20px; background: #3c4248;">
                    <li style="border:none"><a href="index.php?act=admin_product_list"><i class="fas fa-list-ul"></i> Danh sách SP</a></li>
                    <li style="border:none"><a href="index.php?act=admin_add_product"><i class="fas fa-plus"></i> Thêm mới</a></li>
                </ul>
            </li>
            
            <li>
                <a href="#" style="font-weight:bold; background:#444;">
                    <i class="fas fa-images"></i> QUẢN LÝ BANNER
                </a>
                <ul style="padding-left: 20px; background: #3c4248;">
                    <li style="border:none"><a href="index.php?act=admin_banner_list"><i class="fas fa-list-ul"></i> Danh sách Banner</a></li>
                    <li style="border:none"><a href="index.php?act=admin_add_banner"><i class="fas fa-plus"></i> Thêm mới</a></li>
                </ul>
            </li>
            <li>
                <a href="index.php?act=admin_user_list"><i class="fas fa-users"></i> QUẢN LÝ KHÁCH HÀNG</a>
            </li>
            <li>
                <a href="index.php?act=admin_statistics" style="font-weight:bold;">
                    <i class="fas fa-chart-pie"></i> BÁO CÁO THỐNG KÊ
                </a>
            </li>
            <li>
    <a href="index.php?act=admin_brand_list"><i class="fas fa-tag"></i> QUẢN LÝ THƯƠNG HIỆU</a>
</li>
            
            <li><a href="index.php" target="_blank"><i class="fas fa-sign-out-alt"></i> Xem Trang chủ</a></li>
            <li><a href="index.php?act=logout"><i class="fas fa-power-off"></i> Đăng xuất</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h3>Hệ thống quản trị Mini Mart</h3>
            <div>Xin chào, <strong><?php echo isset($_SESSION['user']['fullname']) ? $_SESSION['user']['fullname'] : 'Admin'; ?></strong></div>
        </div>
        
        <?php 
            // Kiểm tra biến $view được truyền từ Controller để include file tương ứng
            if(isset($view) && file_exists(__DIR__ . '/../' . $view . '.php')){
                require_once __DIR__ . '/../' . $view . '.php';
            } else {
                echo "<div class='card'><h2>Chào mừng đến trang quản trị</h2><p>Chọn chức năng bên trái để bắt đầu.</p></div>";
            }
        ?>
    </div>
</body>
</html>