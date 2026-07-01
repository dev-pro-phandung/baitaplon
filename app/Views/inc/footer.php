<?php 
// Tự động xác định BASE_URL (http://localhost/baitap/)
if (!defined('BASE_URL')) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $script = dirname($_SERVER['SCRIPT_NAME']);
    $path = rtrim($protocol . '://' . $host . $script, '/\\');
    
    // Nếu file chạy là public/index.php, ta cần lùi lại 1 cấp để ra root
    if (strpos($path, 'public') !== false) {
        $path = dirname($path);
    }
    
    define('BASE_URL', $path . '/');
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* ================= CSS FOOTER ================= */
    .footer-section {
        background-color: #f8f9fa;
        padding: 50px 0;
        border-top: 1px solid #e9ecef;
        font-family: Arial, sans-serif;
        width: 100%;
        margin-top: 50px;
        clear: both;
        position: relative;
        z-index: 10;
    }
    .footer-section .container { max-width: 1200px; margin: 0 auto; padding: 0 15px; }
    .footer-content { display: flex; flex-wrap: wrap; justify-content: space-between; gap: 30px; }
    .footer-item { flex: 1; min-width: 220px; }
    .footer-item h3 { font-size: 16px; font-weight: bold; color: #333; text-transform: uppercase; margin-bottom: 20px; border-bottom: 2px solid #d0011b; display: inline-block; padding-bottom: 5px; }
    .footer-item p, .footer-item li a { font-size: 14px; color: #555; line-height: 1.8; text-decoration: none; margin: 5px 0; display: block; list-style: none; }
    .footer-item ul { list-style: none; padding: 0; margin: 0; }
    .footer-item ul li a:hover { color: #d0011b; padding-left: 5px; transition: 0.2s; }
    .payment-icons { display: flex; gap: 15px; font-size: 28px; color: #555; margin-bottom: 15px; }
    .qr-code img { width: 120px; border: 1px solid #ddd; padding: 5px; background: white; }
    footer.copyright-bar { background-color: #333; color: #fff; padding: 15px 0; text-align: center; width: 100%; }
    footer.copyright-bar p { margin: 5px 0; font-size: 13px; color: #ccc; }

    /* ================= CSS CHATBOT & MODAL ================= */
    .chat-toggler { position: fixed; bottom: 30px; right: 30px; height: 55px; width: 55px; background: #d0011b; color: white; border: none; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 4px 15px rgba(208, 1, 27, 0.4); z-index: 9999; transition: transform 0.3s; animation: pulse 2s infinite; }
    .chat-toggler:hover { transform: scale(1.1); }
    @keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(208, 1, 27, 0.7); } 70% { box-shadow: 0 0 0 10px rgba(208, 1, 27, 0); } 100% { box-shadow: 0 0 0 0 rgba(208, 1, 27, 0); } }

    .chatbot-container { position: fixed; bottom: 100px; right: 30px; width: 350px; background: white; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.15); overflow: hidden; opacity: 0; visibility: hidden; transform: translateY(20px) scale(0.9); transition: 0.3s; z-index: 9999; display: flex; flex-direction: column; border: 1px solid #ddd; }
    .chatbot-container.show { opacity: 1; visibility: visible; pointer-events: auto; transform: translateY(0) scale(1); }
    
    .chatbot-header { background: #d0011b; color: white; padding: 15px; display: flex; align-items: center; gap: 10px; }
    .bot-avatar { width: 35px; height: 35px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #d0011b; font-size: 18px; }
    .chatbot-close { margin-left: auto; background: none; border: none; color: white; font-size: 24px; cursor: pointer; }
    .chat-body { height: 320px; padding: 15px; overflow-y: auto; background: #f4f7f6; display: flex; flex-direction: column; gap: 10px; }
    .message { max-width: 80%; padding: 10px 14px; border-radius: 15px; font-size: 14px; line-height: 1.4; word-wrap: break-word; }
    .bot-message { align-self: flex-start; background: white; color: #333; border-bottom-left-radius: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
    .user-message { align-self: flex-end; background: #d0011b; color: white; border-bottom-right-radius: 4px; }
    .chat-input { display: flex; border-top: 1px solid #eee; padding: 10px; background: white; }
    .chat-input input { flex: 1; border: none; outline: none; padding: 8px; background: #f9f9f9; border-radius: 20px; margin-right: 8px; }
    .chat-input button { background: none; border: none; color: #d0011b; font-size: 20px; cursor: pointer; }

    /* Sản phẩm gợi ý */
    .bot-product-item { display: flex; gap: 10px; background: #fff; padding: 8px; border-radius: 8px; margin-top: 5px; border: 1px solid #eee; text-decoration: none; color: inherit; }
    .bot-product-img { width: 45px; height: 45px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd; }
    .bot-product-info { flex: 1; display: flex; flex-direction: column; justify-content: center; }
    .bot-product-name { font-size: 13px; font-weight: bold; margin: 0; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
    .bot-product-price { font-size: 12px; color: #d0011b; font-weight: bold; }

    /* Modal */
    .product-modal { display: none; position: fixed; z-index: 10000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); }
    .product-modal-content { background-color: #fefefe; margin: 5% auto; padding: 20px; border: 1px solid #888; width: 90%; max-width: 800px; display: flex; gap: 20px; border-radius: 8px; position: relative; flex-wrap: wrap; }
    .close-modal { position: absolute; right: 20px; top: 10px; font-size: 28px; font-weight: bold; cursor: pointer; }
    
    @media (max-width: 768px) {
        .footer-item { width: 100%; margin-bottom: 20px; }
        .product-modal-content { flex-direction: column; margin: 20% auto; }
        .chatbot-container { width: 90%; right: 5%; bottom: 80px; }
    }
</style>

<section class="footer-section">
    <div class="container">
        <div class="footer-content">
            <div class="footer-item">
                <h3>Hệ thống MiNi Mart</h3>
                <p>Dễ dàng. Nhanh chóng. Tất cả tại Siêu thị MiNi Mart.</p>
                <div style="margin-top: 15px;">
                    <p><strong>Hotline:</strong> 1800 1274</p>
                    <p><strong>Email:</strong> phantiendung2005gtc@gmail.com</p>
                </div>
            </div>
            <div class="footer-item">
                <h3>Về thương hiệu</h3>
                <ul>
                    <li><a href="#">Giới thiệu Siêu Thị</a></li>
                    <li><a href="#">Tuyển dụng</a></li>
                    <li><a href="#">Liên hệ</a></li>
                </ul>
            </div>
            <div class="footer-item">
                <h3>Hỗ trợ khách hàng</h3>
                <ul>
                    <li><a href="#">Chính sách bảo hành</a></li>
                    <li><a href="#">Thanh toán & Vận chuyển</a></li>
                </ul>
            </div>
            <div class="footer-item">
                <h3>Thanh toán</h3>
                <div class="payment-icons">
                    <i class="fab fa-cc-visa"></i>
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <h3 style="margin-top: 15px; border:none; margin-bottom: 5px;">Tải App ngay</h3>
                <div class="qr-code">
                    <img src="<?php echo BASE_URL; ?>public/img/qr-code-demo.png" alt="QR Code" onerror="this.style.display='none'"> 
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="copyright-bar">
    <div class="container">
        <p>&copy; 2024 Siêu thị MiNi Mart. All rights reserved.</p>
    </div>
</footer>

<div class="product-modal" id="productModal">
    <div class="product-modal-content">
        <span class="close-modal" onclick="window.closeProductModal()">&times;</span>
        <div class="modal-left" style="flex: 1; text-align: center;">
            <img id="modal-img" src="" alt="" style="width: 100%; max-height: 300px; object-fit: contain;">
        </div>
        <div class="modal-right" style="flex: 1.5;">
            <h2 id="modal-name" style="margin-top:0;">Tên sản phẩm</h2>
            <p>Mã SP: <strong id="modal-code">SP0000</strong></p>
            <div style="margin: 15px 0;">
                <span id="modal-price" style="font-size: 24px; color: #d0011b; font-weight: bold;">0đ</span> 
            </div>
            <div style="margin-bottom: 20px;">
                <label>Số lượng:</label>
                <div style="display: inline-flex; border: 1px solid #ddd; border-radius: 4px;">
                    <button onclick="window.updateQty(-1)" style="padding: 5px;">-</button>
                    <input type="number" id="modal-quantity" value="1" min="1" style="width: 40px; text-align: center; border: none;">
                    <button onclick="window.updateQty(1)" style="padding: 5px;">+</button>
                </div>
            </div>
            <button onclick="window.addToCart()" style="background: #d0011b; color: white; border: none; padding: 12px 20px; border-radius: 4px; cursor: pointer;">
                Thêm vào Giỏ
            </button>
        </div>
    </div>
</div>

<button class="chat-toggler"><i class="fas fa-comment-dots" style="font-size: 24px;"></i></button>

<div class="chatbot-container" id="chatbot">
    <div class="chatbot-header">
        <div class="bot-avatar"><i class="fas fa-robot"></i></div>
        <div class="bot-info">
            <h4 style="margin:0; font-size:15px;">Trợ lý MiNi Mart</h4>
            <p style="margin:0; font-size:11px; opacity:0.9;">● Đang hoạt động</p>
        </div>
        <button class="chatbot-close">×</button>
    </div>
    <div class="chat-body" id="chatBody">
        <div class="message bot-message">Chào bạn! 👋 Mình có thể giúp gì cho bạn?</div>
        <div style="display:flex; gap:5px; flex-wrap:wrap; margin-top:5px;">
            <span onclick="window.sendQuickReply('Phí ship?')" style="border:1px solid #d0011b; color:#d0011b; padding:5px 10px; border-radius:15px; font-size:11px; cursor:pointer;">🚚 Phí ship</span>
            <span onclick="window.sendQuickReply('Hotline?')" style="border:1px solid #d0011b; color:#d0011b; padding:5px 10px; border-radius:15px; font-size:11px; cursor:pointer;">📞 Hotline</span>
        </div>
    </div>
    <div class="chat-input">
        <input type="text" id="chatInput" placeholder="Nhập tin nhắn..." onkeypress="window.handleEnter(event)">
        <button onclick="window.sendMessage()"><i class="fas fa-paper-plane"></i></button>
    </div>
</div>

<?php
// Lấy dữ liệu sản phẩm cho Bot (Chỉ lấy nếu có Model)
$botProducts = [];
$paths = [__DIR__ . '/../../Models/ProductModel.php', __DIR__ . '/../Models/ProductModel.php'];
foreach ($paths as $path) { if (file_exists($path)) { require_once $path; break; } }
if (class_exists('ProductModel')) {
    try { $pm = new ProductModel(); if(method_exists($pm, 'getAll')) $botProducts = $pm->getAll(); } catch (Exception $e) {}
}
?>

<script>
    // 1. BASE_URL chuẩn
    const BASE_URL = "<?php echo BASE_URL; ?>"; 
    // 2. Dữ liệu sản phẩm
    const productData = <?php echo !empty($botProducts) ? json_encode($botProducts) : '[]'; ?>;
</script>

<script src="<?php echo BASE_URL; ?>public/js/script.js"></script>

</body>
</html>