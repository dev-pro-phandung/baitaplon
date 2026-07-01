<?php 
// 1. GỌI HEADER
// Lưu ý: Nếu file này nằm trong view/product/checkout.php
// Thì để ra được thư mục inc (nằm ở gốc), ta cần lùi 2 cấp (../../)
// Bạn kiểm tra lại nếu lỗi thì sửa thành ../inc/header.php
$headerPath = __DIR__ . '/../../inc/header.php';
if (file_exists($headerPath)) {
    require_once $headerPath;
} else {
    // Fallback nếu cấu trúc thư mục khác
    require_once __DIR__ . '/../inc/header.php'; 
}
?>

<?php
// 2. XỬ LÝ LOGIC PHP (Tính toán giỏ hàng)
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$totalPrice = 0;

// Tính tổng tiền
foreach ($cart as $item) {
    // Ưu tiên lấy key 'num', nếu không có thì lấy 'quantity', mặc định là 1
    $qty = isset($item['num']) ? $item['num'] : (isset($item['quantity']) ? $item['quantity'] : 1);
    $totalPrice += $item['price'] * $qty;
}

// Lấy thông tin User nếu đã đăng nhập để điền sẵn vào Form
$user = isset($_SESSION['user']) ? $_SESSION['user'] : [];
$name = isset($user['fullname']) ? $user['fullname'] : '';
$phone = isset($user['phone']) ? $user['phone'] : ''; 
$address = isset($user['address']) ? $user['address'] : '';
$email = isset($user['email']) ? $user['email'] : '';
?>

<style>
    .checkout-container { padding: 40px 0; display: flex; gap: 30px; flex-wrap: wrap; }
    
    /* Cột Trái: Form */
    .checkout-form { flex: 1.5; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); min-width: 300px; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #333; }
    .form-control { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; box-sizing: border-box; }
    
    /* Cột Phải: Tổng đơn hàng */
    .order-summary { flex: 1; background: #f9f9f9; padding: 30px; border-radius: 8px; border: 1px solid #eee; height: fit-content; min-width: 300px; }
    .summary-item { display: flex; align-items: center; gap: 15px; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 15px; }
    .summary-item img { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd; }
    .item-info { flex: 1; }
    .item-name { font-size: 14px; font-weight: 600; margin: 0; }
    .item-meta { font-size: 12px; color: #666; }
    
    .total-row { display: flex; justify-content: space-between; font-size: 20px; font-weight: bold; color: #d0011b; margin-top: 20px; border-top: 2px solid #ddd; padding-top: 15px; }
    
    /* Phần thanh toán */
    .payment-methods { margin-top: 20px; background: #fff; padding: 15px; border-radius: 5px; border: 1px solid #ddd; }
    .payment-option { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; cursor: pointer; user-select: none; }
    .payment-option input { width: 18px; height: 18px; cursor: pointer; }
    
    /* Khung QR Code */
    #qr-section { display: none; text-align: center; margin-top: 15px; padding-top: 15px; border-top: 1px dashed #ccc; background: #fdfdfd; padding-bottom: 10px; }
    #qr-image { width: 100%; max-width: 250px; border: 1px solid #ddd; border-radius: 5px; margin-bottom: 10px; }
    .qr-note { font-size: 13px; color: #666; margin-top: 5px; font-style: italic; }

    .btn-submit { width: 100%; padding: 15px; background: #d0011b; color: white; font-weight: bold; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; margin-top: 20px; text-transform: uppercase; transition: 0.3s; }
    .btn-submit:hover { background: #b00015; }
</style>

<div class="container checkout-container">
    
    <div class="checkout-form">
        <h2 style="border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 20px; font-size: 24px;">
            <i class="fas fa-map-marker-alt"></i> Thông tin nhận hàng
        </h2>
        
        <form action="index.php?act=submit_order" method="POST" id="checkoutForm">
            
            <div class="form-group">
                <label>Họ tên người nhận (*)</label>
                <input type="text" name="fullname" class="form-control" value="<?php echo $name; ?>" required placeholder="Nhập họ tên đầy đủ">
            </div>
            
            <div class="form-group">
                <label>Số điện thoại (*)</label>
                <input type="text" name="phone" id="customer_phone" class="form-control" value="<?php echo $phone; ?>" required placeholder="Nhập số điện thoại" onchange="updateQRContent()">
            </div>

            <div class="form-group">
                <label>Email (Nhận hóa đơn)</label>
                <input type="email" name="email" class="form-control" value="<?php echo $email; ?>" placeholder="VD: abc@gmail.com">
            </div>

            <div class="form-group">
                <label>Địa chỉ giao hàng (*)</label>
                <input type="text" name="address" class="form-control" value="<?php echo $address; ?>" required placeholder="Số nhà, đường, phường/xã...">
            </div>
            
            <div class="form-group">
                <label>Ghi chú đơn hàng</label>
                <textarea name="note" class="form-control" rows="2" placeholder="Ví dụ: Giao hàng giờ hành chính, gọi trước khi giao..."></textarea>
            </div>

            <h3 style="margin-top: 30px; font-size: 18px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Phương thức thanh toán</h3>
            <div class="payment-methods">
                <label class="payment-option">
                    <input type="radio" name="payment_method" value="COD" checked onchange="toggleQR(false)">
                    <span><i class="fas fa-money-bill-wave" style="color: green;"></i> Thanh toán khi nhận hàng (COD)</span>
                </label>

                <label class="payment-option">
                    <input type="radio" name="payment_method" value="BANK" onchange="toggleQR(true)">
                    <span><i class="fas fa-qrcode" style="color: blue;"></i> Chuyển khoản ngân hàng (QR Code)</span>
                </label>

                <div id="qr-section">
                    <p style="font-weight: bold; color: #333; margin-bottom: 10px;">Quét mã VietQR để thanh toán:</p>
                    
                    <img id="qr-image" src="" alt="Mã QR Thanh Toán">
                    
                    <p class="qr-note">Nội dung chuyển khoản: <strong id="qr-content-display">...</strong></p>
                    <p style="color: red; font-size: 13px; margin-top: 10px;">
                        <i class="fas fa-exclamation-circle"></i> Vui lòng thực hiện chuyển khoản, sau đó ấn nút <strong>"ĐẶT HÀNG"</strong> bên dưới.
                    </p>
                </div>
            </div>

            <button type="submit" class="btn-submit">XÁC NHẬN ĐẶT HÀNG</button>
        </form>
    </div>

    <div class="order-summary">
        <h3 style="margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 10px;">Đơn hàng của bạn</h3>
        
        <?php if (!empty($cart)): ?>
            <?php foreach($cart as $item): 
                $qty = isset($item['num']) ? $item['num'] : (isset($item['quantity']) ? $item['quantity'] : 1);
                $subtotal = $item['price'] * $qty;
                // Xử lý ảnh: Nếu đường dẫn ảnh không có http thì thêm đường dẫn cơ sở
                $img = !empty($item['image']) ? $item['image'] : 'no-image.jpg'; 
                // Kiểm tra xem ảnh có nằm trong thư mục public/images không
                // Nếu code chạy từ index.php ở root/public thì src="images/..."
                // Nếu code chạy từ index.php ở root thì src="public/images/..."
                // Giữ nguyên theo code cũ của bạn là "public/images/"
            ?>
                <div class="summary-item">
                    <img src="public/images/<?php echo $img; ?>" alt="Product" onerror="this.src='https://via.placeholder.com/50'">
                    <div class="item-info">
                        <p class="item-name"><?php echo $item['name']; ?></p>
                        <p class="item-meta">
                            <?php echo $qty; ?> x <?php echo number_format($item['price']); ?>đ
                        </p>
                    </div>
                    <span style="font-weight: bold;"><?php echo number_format($subtotal); ?>đ</span>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Giỏ hàng trống</p>
        <?php endif; ?>

        <div class="total-row">
            <span>Tổng cộng:</span>
            <span id="total-price-display" data-amount="<?php echo $totalPrice; ?>">
                <?php echo number_format($totalPrice); ?> VNĐ
            </span>
        </div>
    </div>
</div>

<script>
    // --- CẤU HÌNH TÀI KHOẢN NGÂN HÀNG CỦA BẠN TẠI ĐÂY ---
    const BANK_ID = 'MB';          // Mã ngân hàng (MB, VCB, TPB, BIDV...)
    const ACCOUNT_NO = '0374518490'; // Số tài khoản của bạn
    const TEMPLATE = 'compact';     // Giao diện: 'compact', 'qr_only', 'print'
    // -----------------------------------------------------

    function toggleQR(show) {
        var qrSection = document.getElementById('qr-section');
        
        if (show) {
            qrSection.style.display = 'block';
            generateQR(); // Gọi hàm tạo QR
        } else {
            qrSection.style.display = 'none';
        }
    }

    function generateQR() {
        var totalAmount = document.getElementById('total-price-display').getAttribute('data-amount');
        var phone = document.getElementById('customer_phone').value;
        var qrImage = document.getElementById('qr-image');
        var qrContentDisplay = document.getElementById('qr-content-display');

        // Tạo nội dung chuyển khoản: "TT [Số điện thoại]"
        var content = 'Thanh toan don hang';
        if(phone) {
            content += ' ' + phone;
        }

        // Hiển thị nội dung cho khách thấy
        qrContentDisplay.innerText = content;

        // Xử lý khoảng trắng trong nội dung để đưa vào URL (thay space bằng %20)
        var contentEncoded = encodeURIComponent(content);

        // Tạo link VietQR
        var qrUrl = `https://img.vietqr.io/image/${BANK_ID}-${ACCOUNT_NO}-${TEMPLATE}.png?amount=${totalAmount}&addInfo=${contentEncoded}`;
        
        qrImage.src = qrUrl;
    }

    // Khi người dùng nhập số điện thoại xong thì cập nhật lại QR code
    function updateQRContent() {
        var isQROpen = document.querySelector('input[name="payment_method"][value="BANK"]').checked;
        if(isQROpen) {
            generateQR();
        }
    }
</script>

<?php 
// 3. GỌI FOOTER
// Tương tự header, lùi 2 cấp thư mục
$footerPath = __DIR__ . '/../../inc/footer.php';
if (file_exists($footerPath)) {
    require_once $footerPath;
} else {
    require_once __DIR__ . '/../inc/footer.php'; 
}
?>