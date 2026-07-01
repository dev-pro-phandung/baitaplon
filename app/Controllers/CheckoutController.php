<?php
// app/Controllers/CheckoutController.php

require_once __DIR__ . '/../Models/OrderModel.php';
require_once __DIR__ . '/../Models/ProductModel.php';
// Gọi file MailService vừa tạo ở trên
require_once __DIR__ . '/../Services/MailService.php'; 

class CheckoutController {

    public function index() {
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            header("Location: index.php");
            exit;
        }

        // Tính toán hiển thị view...
        $cartTotal = 0;
        foreach ($_SESSION['cart'] as $item) {
            $qty = isset($item['num']) ? $item['num'] : (isset($item['quantity']) ? $item['quantity'] : 1);
            $cartTotal += $item['price'] * $qty;
        }

        // Gọi View
        $viewPath = __DIR__ . '/../Views/product/checkout.php';
        if (file_exists($viewPath)) require_once $viewPath;
    }

    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // 1. Kiểm tra giỏ hàng
            if (empty($_SESSION['cart'])) {
                echo "<script>alert('Giỏ hàng trống!'); window.location.href='index.php';</script>";
                exit;
            }

            // 2. Lấy dữ liệu
            $fullname = $_POST['fullname'] ?? '';
            $email    = $_POST['email'] ?? '';
            $phone    = $_POST['phone'] ?? '';
            $address  = $_POST['address'] ?? '';
            $note     = $_POST['note'] ?? '';
            $payment_method = $_POST['payment_method'] ?? 'COD';
            $user_id  = isset($_SESSION['user']) ? $_SESSION['user']['id'] : null;

            // 3. Tính tiền
            $total_money = 0;
            foreach ($_SESSION['cart'] as $item) {
                $qty = isset($item['num']) ? $item['num'] : (isset($item['quantity']) ? $item['quantity'] : 1);
                $total_money += $item['price'] * $qty;
            }

            // 4. Khởi tạo
            $orderModel = new OrderModel();
            $productModel = new ProductModel();
            $mailService = new MailService(); // <--- KHỞI TẠO MAIL SERVICE

            // 5. Lưu đơn hàng
            $orderId = $orderModel->createOrder($user_id, $fullname, $phone, $address, $note, $total_money, $payment_method);

            if ($orderId) {
                // Tạo nội dung danh sách sản phẩm cho Email
                $mailProductHtml = "";

                foreach ($_SESSION['cart'] as $product) {
                    $qty = isset($product['num']) ? $product['num'] : (isset($product['quantity']) ? $product['quantity'] : 1);
                    
                    // Lưu chi tiết & Trừ kho
                    $orderModel->createOrderDetail($orderId, $product['id'], $product['price'], $qty);
                    $productModel->decreaseStock($product['id'], $qty);

                    // HTML cho Email (Tạo dòng trong bảng)
                    $subTotal = number_format($product['price'] * $qty);
                    $price = number_format($product['price']);
                    $mailProductHtml .= "
                        <tr>
                            <td style='padding: 8px; border: 1px solid #ddd;'>{$product['name']}</td>
                            <td style='padding: 8px; border: 1px solid #ddd;'>
                                $qty x $price đ = <strong>$subTotal đ</strong>
                            </td>
                        </tr>";
                }

                // 6. GỬI MAIL (Chỉ gửi nếu khách có nhập email)
                if (!empty($email)) {
                    $mailService->sendOrderConfirmation($email, $fullname, $orderId, $total_money, $mailProductHtml);
                }

                // 7. Hoàn tất
                unset($_SESSION['cart']);
                echo "<script>
                    alert('Đặt hàng thành công! Đã gửi email xác nhận.');
                    window.location.href = 'index.php';
                </script>";

            } else {
                echo "<script>alert('Lỗi hệ thống!'); window.history.back();</script>";
            }
        }
    }
}
?>