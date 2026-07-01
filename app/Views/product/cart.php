<?php require_once __DIR__ . '/../inc/header.php'; ?>

<style>
    .cart-container { padding: 40px 0; min-height: 500px; background-color: #f9f9f9; }
    
    .cart-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .cart-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .cart-table th, .cart-table td { padding: 15px; border-bottom: 1px solid #eee; text-align: center; vertical-align: middle; }
    .cart-table th { background: #f8f9fa; font-weight: bold; color: #555; text-transform: uppercase; font-size: 13px; }
    
    .cart-img { width: 70px; height: 70px; object-fit: cover; border: 1px solid #eee; border-radius: 5px; }
    
    /* Khu vực tổng tiền & Nút bấm */
    .total-area { 
        margin-top: 30px; 
        text-align: right; 
        background: #fff; 
        padding: 20px; 
        border-radius: 8px;
    }
    
    .btn-checkout { 
        background: #d0011b; 
        color: white; 
        padding: 12px 40px; 
        text-decoration: none; 
        font-weight: bold; 
        border-radius: 4px; 
        font-size: 16px; 
        transition: 0.3s; 
        display: inline-block;
    }
    .btn-checkout:hover { background: #b00015; box-shadow: 0 4px 10px rgba(208, 1, 27, 0.3); }
    
    /* Nút tăng giảm trong giỏ hàng */
    .btn-qty-group {
        display: flex;
        justify-content: center;
        align-items: center;
        border: 1px solid #ddd;
        border-radius: 4px;
        width: fit-content;
        margin: 0 auto;
    }
    
    .btn-qty-cart { 
        display: inline-block; 
        width: 30px; 
        height: 30px; 
        line-height: 30px; 
        background: #fff; 
        color: #333; 
        text-decoration: none; 
        font-weight: bold; 
        font-size: 16px;
    }
    .btn-qty-cart:hover { background: #f1f1f1; }
    
    .qty-display {
        width: 35px;
        text-align: center;
        font-weight: bold;
        font-size: 14px;
        border-left: 1px solid #ddd;
        border-right: 1px solid #ddd;
        height: 30px;
        line-height: 30px;
    }
</style>

<div class="container cart-container">
    <div class="cart-card">
        <h2 style="margin-bottom: 20px; border-left: 5px solid #d0011b; padding-left: 15px;">Giỏ hàng của bạn</h2>

        <?php if (!empty($data['cart'])): ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th style="text-align: left;">Tên sản phẩm</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th>Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['cart'] as $key => $item): ?>
                        <tr>
                            <td><img src="img/<?php echo $item['image']; ?>" class="cart-img"></td>
                            
                            <td style="text-align: left;">
                                <div style="font-weight: bold; font-size: 15px;"><?php echo $item['name']; ?></div>
                                <div style="color: #888; font-size: 12px;">Đơn vị: <?php echo $item['unit']; ?></div>
                            </td>
                            
                            <td><?php echo number_format($item['price']); ?>đ</td>
                            
                            <td>
                                <div class="btn-qty-group">
                                    <a href="index.php?act=cart_update&id=<?php echo $item['id']; ?>&type=decr" class="btn-qty-cart">-</a>
                                    
                                    <span class="qty-display"><?php echo $item['quantity']; ?></span>
                                    
                                    <a href="index.php?act=cart_update&id=<?php echo $item['id']; ?>&type=incr" class="btn-qty-cart">+</a>
                                </div>
                            </td>

                            <td style="color: #d0011b; font-weight: bold; font-size: 15px;">
                                <?php echo number_format($item['price'] * $item['quantity']); ?>đ
                            </td>
                            
                            <td>
                                <a href="index.php?act=cart_delete&id=<?php echo $item['id']; ?>" onclick="return confirm('Bạn chắc chắn muốn xóa sản phẩm này?')" style="color: #ccc; font-size: 18px; transition: 0.3s;">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="total-area">
                <div style="font-size: 18px; margin-bottom: 15px;">
                    Tổng tiền thanh toán: <span style="color: #d0011b; font-size: 26px; font-weight: bold;"><?php echo number_format($data['totalPrice']); ?>đ</span>
                </div>
                <a href="index.php?act=checkout" class="btn-checkout"><i class="fas fa-check-circle"></i> TIẾN HÀNH THANH TOÁN</a>
            </div>

        <?php else: ?>
            <div style="text-align: center; padding: 60px 0;">
                <i class="fas fa-shopping-cart" style="font-size: 80px; color: #eee; margin-bottom: 20px;"></i>
                <h3 style="color: #555;">Giỏ hàng của bạn đang trống!</h3>
                <p style="color: #888; margin-bottom: 30px;">Hãy chọn thêm sản phẩm để mua sắm nhé.</p>
                <a href="index.php" style="background: #333; color: white; padding: 12px 30px; text-decoration: none; border-radius: 4px; font-weight: bold;">
                    <i class="fas fa-arrow-left"></i> Quay lại cửa hàng
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../inc/footer.php'; ?>