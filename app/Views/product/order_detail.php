<?php require_once __DIR__ . '/../inc/header.php'; ?>

<div class="container" style="padding: 40px 0;">
    <h2>Chi tiết đơn hàng #<?php echo $data['order_id']; ?></h2>
    <a href="index.php?act=history" style="text-decoration: underline; color: #666;">&larr; Quay lại danh sách</a>

    <table style="width: 100%; margin-top: 20px; border-collapse: collapse;">
        <tr style="background: #eee;">
            <th style="padding: 10px; text-align: left;">Sản phẩm</th>
            <th style="padding: 10px;">Hình ảnh</th>
            <th style="padding: 10px;">Giá mua</th>
            <th style="padding: 10px;">Số lượng</th>
            <th style="padding: 10px;">Thành tiền</th>
        </tr>
        <?php 
            $total = 0;
            foreach($data['details'] as $item): 
                $subtotal = $item['price'] * $item['num'];
                $total += $subtotal;
        ?>
            <tr style="border-bottom: 1px solid #ddd;">
                <td style="padding: 10px;"><strong><?php echo $item['name']; ?></strong></td>
                <td style="padding: 10px; text-align: center;"><img src="img/<?php echo $item['image']; ?>" width="50"></td>
                <td style="padding: 10px; text-align: center;"><?php echo number_format($item['price']); ?>đ</td>
                <td style="padding: 10px; text-align: center;"><?php echo $item['num']; ?></td>
                <td style="padding: 10px; text-align: center; font-weight: bold;"><?php echo number_format($subtotal); ?>đ</td>
            </tr>
        <?php endforeach; ?>
    </table>
    
    <div style="text-align: right; margin-top: 20px; font-size: 20px;">
        Tổng cộng: <strong style="color: #d0011b;"><?php echo number_format($total); ?>đ</strong>
    </div>
</div>

<?php require_once __DIR__ . '/../inc/footer.php'; ?>