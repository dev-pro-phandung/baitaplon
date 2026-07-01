<div class="card">
    <div class="header-action" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0;">Danh sách đơn hàng mới</h3>
        <a href="index.php?act=admin" class="btn-action" style="background: #6c757d;">Làm mới</a>
    </div>

    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #f8f9fa;">
                <th style="padding:12px; border-bottom:1px solid #eee;">ID</th>
                <th style="padding:12px; border-bottom:1px solid #eee;">Khách hàng</th>
                <th style="padding:12px; border-bottom:1px solid #eee;">Tổng tiền</th>
                <th style="padding:12px; border-bottom:1px solid #eee;">Trạng thái</th>
                <th style="padding:12px; border-bottom:1px solid #eee;">Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($data['orders'])): ?>
                <?php foreach($data['orders'] as $order): ?>
                    <tr>
                        <td style="padding:12px; border-bottom:1px solid #eee;">#<?php echo $order['id']; ?></td>
                        <td style="padding:12px; border-bottom:1px solid #eee;">
                            <strong><?php echo $order['fullname']; ?></strong><br>
                            <small><?php echo $order['phone']; ?></small>
                        </td>
                        <td style="padding:12px; border-bottom:1px solid #eee; color:#d0011b; font-weight:bold;">
                            <?php echo number_format($order['total_money']); ?>đ
                        </td>
                        <td style="padding:12px; border-bottom:1px solid #eee;">
                            <?php 
                                if($order['status']==1) echo '<span class="badge bg-new" style="background:#17a2b8; color:white; padding:4px 8px; border-radius:4px; font-size:12px;">Đơn mới</span>';
                                elseif($order['status']==2) echo '<span class="badge bg-process" style="background:#ffc107; color:black; padding:4px 8px; border-radius:4px; font-size:12px;">Đang giao</span>';
                                elseif($order['status']==3) echo '<span class="badge bg-success" style="background:#28a745; color:white; padding:4px 8px; border-radius:4px; font-size:12px;">Hoàn thành</span>';
                                else echo '<span class="badge bg-danger" style="background:#dc3545; color:white; padding:4px 8px; border-radius:4px; font-size:12px;">Đã hủy</span>';
                            ?>
                        </td>
                        <td style="padding:12px; border-bottom:1px solid #eee;">
                            <a href="index.php?act=admin_order_detail&id=<?php echo $order['id']; ?>" 
                               style="background:#333; color:white; padding:5px 10px; text-decoration:none; border-radius:3px; font-size:12px;">
                               Xử lý
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" style="padding:20px; text-align:center;">Chưa có đơn hàng nào.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>