<?php 
if (!defined('BASE_URL')) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $script = dirname($_SERVER['SCRIPT_NAME']);
    $path = rtrim($protocol . '://' . $host . $script, '/\\');
    if (strpos($path, 'public') !== false) $path = dirname($path);
    define('BASE_URL', $path . '/');
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siêu thị Mini Mart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">

    <style>
        /* ================= RESET CSS ================= */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f5f5f5; }
        a { text-decoration: none; color: inherit; transition: 0.2s; }
        ul { list-style: none; padding: 0; margin: 0; }
        .container { width: 1200px; max-width: 100%; margin: 0 auto; padding: 0 15px; }

        /* ================= 1. TOP BAR ================= */
        .top-bar { 
            background: #b09d04; 
            color: white; 
            padding: 5px 0; 
            font-size: 13px; 
        }
        .top-bar .flex-between { display: flex; justify-content: space-between; align-items: center; }
        .top-links a { margin-left: 15px; font-weight: 500; color: #fff; }
        .top-links a:hover { text-decoration: underline; }

        /* ================= 2. MAIN HEADER ================= */
        .main-header { 
            background-color: #0088cc; 
            padding: 20px 0; /* Giảm padding một chút cho gọn */
            position: relative;
            z-index: 500;
        }
        .header-wrapper { 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            gap: 20px; /* Giảm khoảng cách các khối */
        }

        /* Logo */
        .logo a { 
            font-size: 30px; /* Giảm nhẹ size logo */
            font-weight: 800; 
            color: white !important; 
            display: flex; align-items: center; gap: 8px; 
            text-transform: uppercase; white-space: nowrap;
        }
        .logo span { color: #ffde00; }

        /* Search Box */
        .search-box { flex: 1; max-width: 600px; }
        .search-form { 
            display: flex; background: white; border-radius: 4px; overflow: hidden; height: 40px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .search-input { flex: 1; border: none; padding: 0 15px; outline: none; font-size: 14px; color: #333; }
        .search-btn { background: #333; color: white; border: none; padding: 0 20px; cursor: pointer; font-size: 16px; }
        .search-btn:hover { background: #000; }

        /* Header Actions */
        .header-actions { display: flex; align-items: center; gap: 20px; color: white; white-space: nowrap; }
        .action-item { 
            text-align: center; font-size: 12px; color: white !important; cursor: pointer; 
            display: flex; flex-direction: column; align-items: center;
        }
        .action-item i { font-size: 22px; margin-bottom: 3px; }
        
        .cart-btn-wrapper { position: relative; }
        .cart-count { 
            position: absolute; top: -8px; right: -8px; 
            background: #ffde00; color: #d0011b; 
            font-size: 10px; font-weight: bold; 
            padding: 1px 5px; border-radius: 10px; border: 1px solid #d0011b;
        }

        /* ================= 3. NAVIGATION MENU (Đã tối ưu) ================= */
        /* ================= 3. NAVIGATION MENU (FIX LỖI MẤT SUB-MENU) ================= */
        .nav-menu { 
            background: #0088cc; 
            height: 45px; 
            position: relative; 
            z-index: 1000; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.1); 
        }
        
        .nav-list { 
            display: flex; 
            height: 100%; 
            align-items: center;
            /* XÓA BỎ CÁC DÒNG OVERFLOW ĐỂ HIỆN LẠI MENU CON */
            flex-wrap: nowrap; /* Không xuống dòng */
        }
        
        /* Menu Cha */
        .nav-item { 
            height: 100%; 
            display: flex; align-items: center; position: relative; 
            flex-shrink: 0; 
        }
        
        .nav-link { 
            display: flex; align-items: center; height: 100%; 
            /* GIẢM PADDING TỐI ĐA: Chỉ để 10px để nhét vừa nhiều mục */
            padding: 0 10px; 
            color: white !important; 
            font-weight: 600; 
            font-size: 13px;
            text-transform: uppercase; 
            transition: background 0.2s;
            white-space: nowrap;
        }
        
        .nav-item:hover { background-color: #006fa6; }

        .nav-link img { 
            width: 18px; height: 18px; object-fit: contain; margin-right: 5px; 
            filter: brightness(0) invert(1); 
        }
        .nav-link i { margin-right: 5px; font-size: 14px; }
        /* ================= 4. MENU CON (DROPDOWN) ================= */
        .sub-menu {
            display: none !important;
            position: absolute;
            top: 100%; left: 0;
            background: white;
            min-width: 220px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
            border-top: 3px solid #ffde00;
            z-index: 99999 !important;
            border-radius: 0 0 4px 4px;
            opacity: 1 !important; visibility: visible !important;
        }

        .nav-item:hover .sub-menu { display: block !important; }

        .sub-menu ul li { border-bottom: 1px solid #f5f5f5; }
        .sub-menu a { 
            display: flex; justify-content: space-between; align-items: center;
            padding: 10px 15px; 
            color: #333 !important; font-size: 13px; font-weight: normal; text-transform: none; 
        }
        .sub-menu a:hover { 
            color: #0088cc !important; background: #f9f9f9; padding-left: 20px; transition: all 0.2s;
        }
    </style>
</head>
<body>

    <div class="top-bar">
        <div class="container flex-between">
            <div><i class="fas fa-headset"></i> Hotline: <b>1800 1274</b> (Miễn phí)</div>
            <div class="top-links">
                <?php if(isset($_SESSION['user'])): ?>
                    <span><i class="fas fa-user-circle"></i> Chào, <b><?php echo $_SESSION['user']['fullname']; ?></b></span>
                    <a href="index.php?act=logout">Thoát</a>
                <?php else: ?>
                    <a href="index.php?act=register">Đăng ký</a>
                    <a href="index.php?act=login">Đăng nhập</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="main-header">
        <div style="display: flex; align-items: center; background-color: #0088cc;" class="container header-wrapper">
            <div class="logo">
                <a href="index.php">
                    <i class="fas fa-store"></i> Mini<span>Mart</span>
                </a>
            </div>

            <div class="search-box">
                <form action="index.php" method="GET" class="search-form">
                    <input type="hidden" name="act" value="search">
                    <input type="text" name="keyword" class="search-input" placeholder="Hôm nay bạn muốn mua gì?..." value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
                    <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
                </form>
            </div>

            <div class="header-actions">
                <a href="index.php?act=history" class="action-item">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>Đơn hàng</span>
                </a>
                
                <a href="index.php?act=cart" class="action-item">
                    <div class="cart-btn-wrapper">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count">
                            <?php 
                                $totalQty = 0;
                                if(isset($_SESSION['cart'])) {
                                    foreach($_SESSION['cart'] as $item) $totalQty += $item['quantity'];
                                }
                                echo $totalQty;
                            ?>
                        </span>
                    </div>
                    <span>Giỏ hàng</span>
                </a>
            </div>
        </div>
    </div>

    <nav class="nav-menu">
        <div class="container">
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="index.php" class="nav-link"><i class="fas fa-home"></i> Trang chủ</a>
                </li>

                <?php 
                if(!empty($data['categories'])) {
                    $parents = [];
                    $children = [];

                    // Phân loại Cha / Con
                    foreach($data['categories'] as $cat) {
                        if($cat['parent_id'] == 0 || $cat['parent_id'] == null) {
                            $parents[] = $cat;
                        } else {
                            $children[$cat['parent_id']][] = $cat;
                        }
                    }

                    // Vòng lặp hiển thị
                    foreach($parents as $parent): 
                        $hasChild = isset($children[$parent['id']]);
                ?>
                        <li class="nav-item">
                            <a href="index.php?act=category&id=<?php echo $parent['id']; ?>" class="nav-link">
                                <?php if(!empty($parent['image'])): ?>
                                    <img src="img/<?php echo $parent['image']; ?>" alt="icon">
                                <?php elseif(strpos($parent['name'], 'Tóc') !== false): ?>
                                    <i class="fas fa-cut"></i>
                                <?php elseif(strpos($parent['name'], 'Da') !== false): ?>
                                    <i class="fas fa-spa"></i>
                                <?php elseif(strpos($parent['name'], 'Thực phẩm') !== false): ?>
                                    <i class="fas fa-utensils"></i>
                                <?php else: ?>
                                    <i class="fas fa-box-open"></i>
                                <?php endif; ?>
                                
                                <?php echo $parent['name']; ?>
                                
                                <?php if($hasChild): ?>
                                    <i class="fas fa-chevron-down" style="font-size:10px; margin-left: 5px; opacity: 0.7;"></i>
                                <?php endif; ?>
                            </a>

                            <?php if($hasChild): ?>
                                <div class="sub-menu">
                                    <ul>
                                        <?php foreach($children[$parent['id']] as $child): ?>
                                            <li>
                                                <a href="index.php?act=category&id=<?php echo $child['id']; ?>">
                                                    <?php echo $child['name']; ?>
                                                    <i class="fas fa-angle-right" style="color:#ccc; font-size:12px;"></i>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </li>
                <?php 
                    endforeach; 
                } 
                ?>
            </ul>
        </div>
    </nav>
    
</body>
</html>