<?php require_once __DIR__ . '/../inc/header.php'; ?>

<style>
    .tracking-page { padding: 40px 0; }
    .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 30px; }
    
    /* --- THANH TIẾN TRÌNH (TIMELINE) CSS --- */
    .track-line { display: flex; justify-content: space-between; position: relative; margin: 40px 0; }
    .track-line::before {
        content: ''; position: absolute; top: 15px; left: 0; width: 100%; height: 4px; background: #ddd; z-index: 0;
    }
    .step { position: relative; z-index: 1; text-align: center; width: 25%; }
    .step-icon {
        width: 35px; height: 35px; background: #ddd; border-radius: 50%; margin: 0 auto 10px;
        display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;
    }
    .step-text { font-size: 14px; color: #999; font-weight: 500; }
    
    /* Trạng thái Active (Đã qua) */
    .step.active .step-icon { background: #28a745; }
    .step.active .step-text { color: #28a745; font-weight: bold; }
    
    /* Thanh màu xanh chạy theo các bước */
    .progress-bar {
        position: absolute; top: 15px; left: 0; height: 4px; background: #28a745; z-index: 0; transition: width 0.4s;
    }

    /* Chi tiết sản phẩm */
    .item-row { display: flex; align-items: center; border-bottom: 1px solid #eee; padding: 15px 0; }
    .item-img { width: 60px; height: 60px; border: 1px solid #eee; border-radius: 5px; margin-right: 20px; object-fit: cover; }
</style>

<div class="container tracking-page">
    
    <div class="card">
        <h3>Theo dõi đơn hàng #<?php echo $data['order']['id']; ?></h3>
        <p style="color: #666; margin-top: 5px;">Đặt ngày: <?php echo $data['order']['created_at']; ?></p>

        <?php 
            $stt = $data['order']['status']; 
            $width = '0%';
            if($stt == 1) $width = '25%'; // Chờ xử lý
            if($stt == 2) $width = '60%'; // Đang giao
            if($stt == 3) $width = '100%'; // Hoàn thành
            if($stt == 0) $width = '0%';  // Hủy
        ?>

        <?php if($stt != 0): ?>
            <div class="track-line">
                <div class="progress-bar" style="width: <?php echo $width; ?>;"></div>
                
                <div class="step <?php echo ($stt >= 1) ? 'active' : ''; ?>">
                    <div class="step-icon"><i class="fas fa-clipboard-check"></i></div>
                    <div class="step-text">Chờ xác nhận</div>
                </div>
                
                <div class="step <?php echo ($stt >= 2) ? 'active' : ''; ?>">
                    <div class="step-icon"><i class="fas fa-box-open"></i></div>
                    <div class="step-text">Đã đóng gói</div>
                </div>

                <div class="step <?php echo ($stt >= 2) ? 'active' : ''; ?>">
                    <div class="step-icon"><i class="fas fa-shipping-fast"></i></div>
                    <div class="step-text">Đang giao hàng</div>
                </div>

                <div class="step <?php echo ($stt == 3) ? 'active' : ''; ?>">
                    <div class="step-icon"><i class="fas fa-check"></i></div>
                    <div class="step-text">Hoàn thành</div>
                </div>
            </div>
        <?php else: ?>
            <div style="margin-top: 30px; text-align: center; color: red;">
                <h2><i class="fas fa-times-circle"></i> ĐƠN HÀNG ĐÃ BỊ HỦY</h2>
            </div>
        <?php endif; ?>
    </div>

    <div class="card">
        <h4>Thông tin nhận hàng</h4>
        <p><strong>Người nhận:</strong> <?php echo $data['order']['fullname']; ?></p>
        <p><strong>Điện thoại:</strong> <?php echo $data['order']['phone']; ?></p>
        <p><strong>Địa chỉ:</strong> <?php echo $data['order']['address']; ?></p>
        <p><strong>Ghi chú:</strong> <?php echo $data['order']['note']; ?></p>
        <p><strong>Thanh toán:</strong> 
            <?php echo ($data['order']['payment_method'] == 'qr') ? 'Chuyển khoản QR' : 'Tiền mặt (COD)'; ?>
        </p>
    </div>

    <div class="card">
        <h4>Sản phẩm đã mua</h4>
        <?php foreach($data['details'] as $item): ?>
            <div class="item-row">
                <img src="img/<?php echo $item['image']; ?>" class="item-img">
                <div style="flex: 1;">
                    <strong style="font-size: 15px;"><?php echo $item['name']; ?></strong>
                    <div style="color: #666; font-size: 13px;">x<?php echo $item['num']; ?></div>
                </div>
                <div style="color: #d0011b; font-weight: bold;">
                    <?php echo number_format($item['price'] * $item['num']); ?>đ
                </div>
            </div>
        <?php endforeach; ?>
        
        <div style="text-align: right; margin-top: 20px; font-size: 18px;">
            Tổng tiền: <strong style="color: #d0011b;"><?php echo number_format($data['order']['total_money']); ?>đ</strong>
        </div>
    </div>
    
    <div style="text-align: center;">
        <a href="index.php?act=history" style="text-decoration: underline; color: #666;">&laquo; Quay lại danh sách</a>
    </div>
</div>

<?php require_once __DIR__ . '/../inc/footer.php'; ?>