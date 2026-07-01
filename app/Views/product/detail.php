<?php require_once __DIR__ . '/../inc/header.php'; ?>

<style>
    .detail-wrapper { padding: 50px 0; background: #fff; }
    
    /* KHUNG CHỨA SẢN PHẨM */
    .detail-box { display: flex; gap: 40px; margin-bottom: 60px; align-items: flex-start; }
    
    /* ẢNH SẢN PHẨM */
    .detail-img { flex: 1; border: 1px solid #f0f0f0; padding: 20px; border-radius: 10px; text-align: center; position: relative; }
    .detail-img img { width: 100%; max-width: 100%; height: auto; object-fit: contain; }
    .label-new { position: absolute; top: 10px; left: 10px; background: #d0011b; color: #fff; padding: 5px 10px; border-radius: 5px; font-size: 12px; font-weight: bold; }

    /* THÔNG TIN SẢN PHẨM */
    .detail-info { flex: 1.5; }
    .detail-name { font-size: 26px; margin-bottom: 15px; color: #333; font-weight: 700; line-height: 1.3; }
    
    .detail-meta { margin-bottom: 20px; font-size: 14px; color: #555; display: flex; gap: 20px; }
    .meta-item strong { color: #333; }
    .status-ok { color: #28a745; font-weight: bold; }
    .status-out { color: #dc3545; font-weight: bold; }

    .detail-price { background: #fafafa; padding: 20px; border-radius: 8px; margin-bottom: 25px; }
    .current-price { font-size: 32px; color: #d0011b; font-weight: bold; }
    .old-price { font-size: 16px; color: #999; text-decoration: line-through; margin-left: 10px; }
    .unit { font-size: 14px; color: #666; }

    /* BỘ CHỌN SỐ LƯỢNG */
    .quantity-section { margin-bottom: 25px; display: flex; align-items: center; gap: 15px; }
    .qty-control { display: flex; border: 1px solid #ddd; border-radius: 5px; overflow: hidden; }
    .qty-btn { width: 40px; height: 40px; background: #fff; border: none; font-size: 18px; cursor: pointer; transition: 0.2s; }
    .qty-btn:hover { background: #f0f0f0; }
    .qty-input { width: 50px; height: 40px; text-align: center; border: none; border-left: 1px solid #ddd; border-right: 1px solid #ddd; font-weight: bold; outline: none; -moz-appearance: textfield; }
    .qty-input::-webkit-outer-spin-button, .qty-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

    /* CÁC NÚT MUA HÀNG */
    .detail-actions { display: flex; gap: 15px; }
    .btn-main { flex: 1; padding: 15px; font-size: 16px; font-weight: bold; text-transform: uppercase; border-radius: 5px; cursor: pointer; border: none; transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 10px; }
    
    .btn-add { background: #ffeaea; color: #d0011b; border: 1px solid #d0011b; }
    .btn-add:hover { background: #ffcccc; }
    
    .btn-buy { background: #d0011b; color: #fff; }
    .btn-buy:hover { background: #b00015; box-shadow: 0 4px 10px rgba(208, 1, 27, 0.3); }

    .btn-disabled { background: #ccc !important; color: #666 !important; border: none !important; cursor: not-allowed; }

    /* MÔ TẢ SẢN PHẨM */
    .detail-desc { margin-top: 40px; border-top: 1px solid #eee; padding-top: 30px; }
    .desc-title { font-size: 20px; font-weight: bold; margin-bottom: 15px; border-left: 4px solid #d0011b; padding-left: 10px; }
    .desc-content { color: #444; line-height: 1.6; font-size: 15px; text-align: justify; }

    /* SẢN PHẨM LIÊN QUAN */
    .related-wrapper { margin-top: 60px; }
    .related-title { font-size: 22px; font-weight: bold; text-transform: uppercase; margin-bottom: 25px; text-align: center; position: relative; }
    .related-title::after { content: ''; display: block; width: 60px; height: 3px; background: #d0011b; margin: 10px auto 0; }
    
    .related-list { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
    .related-item { border: 1px solid #eee; border-radius: 8px; overflow: hidden; transition: 0.3s; background: #fff; }
    .related-item:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.1); transform: translateY(-3px); }
    .related-img { width: 100%; height: 200px; object-fit: contain; padding: 10px; border-bottom: 1px solid #f5f5f5; }
    .related-info { padding: 15px; text-align: center; }
    .related-name { font-size: 15px; margin-bottom: 8px; color: #333; font-weight: 500; height: 40px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }
    .related-price { color: #d0011b; font-weight: bold; font-size: 16px; }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .detail-box { flex-direction: column; }
        .related-list { grid-template-columns: repeat(2, 1fr); }
    }
</style>

<div class="container detail-wrapper">
    <?php 
        // Lấy dữ liệu cho gọn code
        $p = $data['product'];
        $related = $data['related_products'];
        
        // Kiểm tra tồn kho
        $quantityStock = isset($p['quantity']) ? (int)$p['quantity'] : 0;
        $isStock = $quantityStock > 0;
    ?>

    <div class="detail-box">
        <div class="detail-img">
            <?php if(isset($p['is_new']) && $p['is_new']): ?>
                <span class="label-new">Mới</span>
            <?php endif; ?>
            <img src="img/<?php echo $p['image']; ?>" alt="<?php echo $p['name']; ?>">
        </div>

        <div class="detail-info">
            <h1 class="detail-name"><?php echo $p['name']; ?></h1>
            
            <div class="detail-meta">
                <span class="meta-item">Mã SP: <strong>#<?php echo $p['id']; ?></strong></span>
                <span class="meta-item">Tình trạng: 
                    <?php if($isStock): ?>
                        <span class="status-ok"><i class="fas fa-check-circle"></i> Còn hàng (<?php echo $quantityStock; ?>)</span>
                    <?php else: ?>
                        <span class="status-out"><i class="fas fa-times-circle"></i> Hết hàng</span>
                    <?php endif; ?>
                </span>
                <span class="meta-item">Lượt xem: <strong><?php echo isset($p['views']) ? $p['views'] : 0; ?></strong></span>
            </div>

            <div class="detail-price">
                <span class="current-price"><?php echo number_format($p['price']); ?>đ</span>
                <?php if(!empty($p['old_price']) && $p['old_price'] > $p['price']): ?>
                    <span class="old-price"><?php echo number_format($p['old_price']); ?>đ</span>
                <?php endif; ?>
                <span class="unit">/ <?php echo $p['unit']; ?></span>
            </div>

            <form action="index.php?act=cart_add" method="POST">
                <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                
                <div class="quantity-section">
    <strong>Số lượng:</strong>
    <div class="qty-control">
        <button type="button" class="qty-btn" onclick="updateDetailQty(-1)">-</button>
        
        <input type="number" name="quantity" id="qtyInput" class="qty-input" value="1" min="1" max="<?php echo $quantityStock; ?>" readonly>
        
        <button type="button" class="qty-btn" onclick="updateDetailQty(1)">+</button>
    </div>
</div>

<script>
    function updateDetailQty(change) {
        var input = document.getElementById('qtyInput');
        var currentVal = parseInt(input.value);
        var maxVal = parseInt(input.getAttribute('max')); // Lấy số lượng tồn kho tối đa

        var newVal = currentVal + change;

        // Điều kiện: Không nhỏ hơn 1 và Không lớn hơn tồn kho
        if (newVal >= 1 && newVal <= maxVal) {
            input.value = newVal;
        }
    }
</script>

                <div class="detail-actions">
                    <?php if($isStock): ?>
                        <button type="submit" class="btn-main btn-add">
                            <i class="fas fa-cart-plus"></i> Thêm vào giỏ
                        </button>
                        <button type="submit" class="btn-main btn-buy">
                            <i class="fas fa-money-bill-wave"></i> Mua ngay
                        </button>
                    <?php else: ?>
                        <button type="button" class="btn-main btn-disabled" disabled>
                            Hết hàng
                        </button>
                    <?php endif; ?>
                </div>
            </form>

            <div class="detail-desc">
    <h3 class="desc-title">Mô tả sản phẩm</h3>
    <div class="desc-content">
        <?php 
            // Kiểm tra xem có mô tả trong Database không
            if (!empty($p['description'])) {
                // Cách 1: Chỉ hiện mô tả gốc
                //echo nl2br($p['description']); 
                
                // Cách 2: (Gợi ý) Chèn tên sản phẩm vào đầu cho chuẩn SEO
                 echo "<p><strong>Đặc điểm nổi bật của " . $p['name'] . ":</strong></p>";
                 echo nl2br($p['description']); 
            } else {
                // Hiện thông báo kèm Tên sản phẩm cho đỡ trống
                echo "Thông tin chi tiết về sản phẩm <b>" . $p['name'] . "</b> Sản phẩm 100% chính hãng. Vui lòng liên hệ bộ phận chăm sóc khách hàng để biết thêm chi tiết.";
            }
        ?>
    </div>
</div>
        </div>
    </div>

    <?php if(!empty($related)): ?>
    <div class="related-wrapper">
    <h2 class="related-title">Sản phẩm cùng loại</h2>
    
    <div class="related-list">
        <?php if (!empty($related)): ?>
            
            <?php foreach($related as $item): ?>
                <div class="related-item">
                    <a href="index.php?act=detail&id=<?php echo $item['id']; ?>">
                        <img src="img/<?php echo $item['image']; ?>" class="related-img" alt="<?php echo $item['name']; ?>">
                        <div class="related-info">
                            <h3 class="related-name"><?php echo $item['name']; ?></h3>
                            <div class="related-price"><?php echo number_format($item['price']); ?>đ</div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>

        <?php else: ?>
            <p style="padding: 10px; color: #777;">Chưa có sản phẩm cùng loại.</p>
        <?php endif; ?>
    </div>
</div>
    <?php endif; ?>
</div>

<script>
    // Lấy tồn kho tối đa từ PHP
    var maxStock = <?php echo $quantityStock; ?>;

    function updateQty(change) {
        var input = document.getElementById('qtyInput');
        var currentQty = parseInt(input.value);
        var newQty = currentQty + change;

        // Kiểm tra điều kiện
        if (newQty >= 1 && newQty <= maxStock) {
            input.value = newQty;
        } else if (newQty > maxStock) {
            alert('Xin lỗi, trong kho chỉ còn ' + maxStock + ' sản phẩm!');
        }
    }
</script>

<?php require_once __DIR__ . '/../inc/footer.php'; ?>