<?php
require_once __DIR__ . '/../Models/ProductModel.php';
require_once __DIR__ . '/../Models/OrderModel.php';

class CartController {

    // 1. Xem giỏ hàng
    public function index() {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        
        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        $data = [
            'cart' => $cart,
            'totalPrice' => $totalPrice,
            'page_title' => 'Giỏ hàng của bạn'
        ];

        require_once __DIR__ . '/../Views/product/cart.php';
    }

    // 2. Thêm vào giỏ hàng (Form Submit - Load lại trang)
    public function add() {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : (isset($_GET['id']) ? (int)$_GET['id'] : 0);
        $qty = isset($_POST['quantity']) ? (int)$_POST['quantity'] : (isset($_GET['qty']) ? (int)$_GET['qty'] : 1);

        if ($id > 0) {
            $productModel = new ProductModel();
            $product = $productModel->getOne($id);

            if ($product) {
                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
                }

                $currentCartQty = isset($_SESSION['cart'][$id]) ? $_SESSION['cart'][$id]['quantity'] : 0;
                $newTotalQty = $currentCartQty + $qty;

                // Kiểm tra tồn kho
                if ($newTotalQty > $product['quantity']) {
                    echo "<script>
                        alert('Xin lỗi! Sản phẩm này chỉ còn tồn kho " . $product['quantity'] . " cái.');
                        window.history.back();
                    </script>";
                    exit;
                }

                if (isset($_SESSION['cart'][$id])) {
                    $_SESSION['cart'][$id]['quantity'] += $qty;
                } else {
                    $_SESSION['cart'][$id] = [
                        'id' => $product['id'],
                        'name' => $product['name'],
                        'price' => $product['price'],
                        'image' => $product['image'],
                        'unit' => $product['unit'],
                        'quantity' => $qty
                    ];
                }
            }
        }
        
        header("Location: index.php?act=cart");
        exit();
    }

    // 3. API Thêm vào giỏ hàng (AJAX - Không load trang)
    public function addToCartApi() {
        // Đặt header JSON để trình duyệt hiểu đúng định dạng
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        
        $id = isset($input['id']) ? (int)$input['id'] : 0;
        $qty = isset($input['qty']) ? (int)$input['qty'] : 1;

        if ($id > 0) {
            $productModel = new ProductModel();
            $product = $productModel->getOne($id);

            if ($product) {
                // Kiểm tra tồn kho
                $currentCartQty = isset($_SESSION['cart'][$id]) ? $_SESSION['cart'][$id]['quantity'] : 0;
                
                if (($currentCartQty + $qty) > $product['quantity']) {
                    echo json_encode([
                        'status' => false, 
                        'message' => 'Xin lỗi, kho chỉ còn ' . $product['quantity'] . ' sản phẩm!'
                    ]);
                    exit;
                }

                // Khởi tạo giỏ
                if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
                
                // Thêm/Cập nhật
                if (isset($_SESSION['cart'][$id])) {
                    $_SESSION['cart'][$id]['quantity'] += $qty;
                } else {
                    $_SESSION['cart'][$id] = [
                        'id' => $product['id'],
                        'name' => $product['name'],
                        'price' => $product['price'],
                        'image' => $product['image'],
                        'unit' => $product['unit'],
                        'quantity' => $qty
                    ];
                }

                // Tính tổng số lượng
                $totalQty = 0;
                foreach($_SESSION['cart'] as $item) $totalQty += $item['quantity'];

                echo json_encode([
                    'status' => true, 
                    'message' => 'Đã thêm vào giỏ hàng thành công!', 
                    'total_qty' => $totalQty
                ]);
                exit;
            }
        }
        echo json_encode(['status' => false, 'message' => 'Sản phẩm không tồn tại hoặc lỗi dữ liệu!']);
        exit;
    }

    // 4. Cập nhật số lượng (+ / -)
    public function update() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $type = isset($_GET['type']) ? $_GET['type'] : 'incr';

        if (isset($_SESSION['cart'][$id])) {
            if ($type == 'incr') {
                $productModel = new ProductModel();
                $product = $productModel->getOne($id);
                
                $currentQty = (int)$_SESSION['cart'][$id]['quantity'];
                $stockQty   = (int)$product['quantity'];

                if ($currentQty >= $stockQty) {
                    echo "<script>
                        alert('Rất tiếc! Kho chỉ còn " . $stockQty . " sản phẩm. Không thể mua thêm.');
                        window.location.href = 'index.php?act=cart'; 
                    </script>";
                    exit();
                }
                
                $_SESSION['cart'][$id]['quantity']++;

            } else {
                $_SESSION['cart'][$id]['quantity']--;
                if ($_SESSION['cart'][$id]['quantity'] <= 0) {
                    unset($_SESSION['cart'][$id]);
                }
            }
        }
        
        header("Location: index.php?act=cart");
        exit();
    }

    // 5. Xóa sản phẩm
    public function delete() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        header("Location: index.php?act=cart");
        exit();
    }

    // 6. Trang Thanh toán
    public function checkout() {
        if (empty($_SESSION['cart'])) {
            header("Location: index.php");
            exit();
        }

        $cart = $_SESSION['cart'];
        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        $data = [
            'cart' => $cart,
            'totalPrice' => $totalPrice,
            'page_title' => 'Thanh toán đơn hàng'
        ];

        require_once __DIR__ . '/../Views/product/checkout.php';
    }

    // 7. Xử lý Đặt hàng & Gửi Email
    public function submitOrder() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $fullname = $_POST['fullname'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            $note = $_POST['note'];
            $payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : 'cod';
            
            $user_id = isset($_SESSION['user']) ? $_SESSION['user']['id'] : 0;
            $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
            
            if (empty($cart)) {
                header("Location: index.php");
                exit();
            }

            $total_money = 0;
            foreach ($cart as $item) {
                $total_money += $item['price'] * $item['quantity'];
            }

            require_once __DIR__ . '/../Models/OrderModel.php';
            $orderModel = new OrderModel();
            
            // Tạo đơn hàng trong DB
            $order_id = $orderModel->createOrder($user_id, $fullname, $phone, $address, $note, $total_money, $payment_method);

            if ($order_id) {
                // Lưu chi tiết đơn hàng
                foreach ($cart as $item) {
                    $orderModel->createOrderDetail($order_id, $item['id'], $item['price'], $item['quantity']);
                }

                // --- BẮT ĐẦU QUY TRÌNH GỬI EMAIL ---
                $customerEmail = '';
                // Ưu tiên lấy email từ tài khoản đăng nhập
                if(isset($_SESSION['user']['email'])) {
                    $customerEmail = $_SESSION['user']['email'];
                } elseif (isset($_POST['email'])) {
                    // Nếu không đăng nhập, lấy từ form checkout (nếu form có input name='email')
                    $customerEmail = $_POST['email'];
                }

                if (!empty($customerEmail)) {
                    $mailServicePath = __DIR__ . '/../Services/MailService.php';
                    
                    // Kiểm tra file tồn tại để tránh lỗi Fatal Error
                    if (file_exists($mailServicePath)) {
                        require_once $mailServicePath;
                        $mailService = new MailService();
                        
                        // Gửi email xác nhận
                        $mailService->sendOrderConfirmation($customerEmail, $fullname, $order_id, $total_money);
                    }
                }
                // --- KẾT THÚC GỬI EMAIL ---

                // Xóa giỏ hàng sau khi thành công
                unset($_SESSION['cart']);

                if ($payment_method == 'qr') {
                    echo "<script>
                        alert('Đơn hàng #$order_id đã được ghi nhận!\\nChúng tôi sẽ kiểm tra khoản chuyển khoản và giao hàng sớm nhất.');
                        window.location.href = 'index.php';
                    </script>";
                } else {
                    echo "<script>
                        alert('ĐẶT HÀNG THÀNH CÔNG!\\nMã đơn: #$order_id.\\nEmail xác nhận đã được gửi tới: $customerEmail');
                        window.location.href = 'index.php';
                    </script>";
                }
                exit();
            } else {
                echo "Lỗi: Không thể tạo đơn hàng. Vui lòng thử lại.";
            }
        }
    }

    // 8. Lịch sử đơn hàng
    public function history() {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?act=login");
            exit;
        }

        require_once __DIR__ . '/../Models/OrderModel.php';
        $orderModel = new OrderModel();
        
        $orders = $orderModel->getOrdersByUser($_SESSION['user']['id']);
        
        $data = ['orders' => $orders, 'page_title' => 'Lịch sử đơn hàng'];
        require_once __DIR__ . '/../Views/product/history.php';
    }

    // 9. Theo dõi chi tiết đơn hàng
    public function tracking() {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?act=login");
            exit;
        }

        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        require_once __DIR__ . '/../Models/OrderModel.php';
        $orderModel = new OrderModel();
        
        $order = $orderModel->getOrderById($id);
        $details = $orderModel->getOrderDetails($id);

        if (!$order || $order['user_id'] != $_SESSION['user']['id']) {
            echo "<script>alert('Đơn hàng không tồn tại!'); window.location.href='index.php?act=history';</script>";
            exit;
        }

        $data = ['order' => $order, 'details' => $details, 'page_title' => 'Chi tiết đơn hàng #' . $id];
        require_once __DIR__ . '/../Views/product/tracking.php';
    }
}
?>