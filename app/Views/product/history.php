<?php require_once __DIR__ . '/../inc/header.php'; ?>

<style>
    .history-page { padding: 40px 0; background-color: #f5f5fa; min-height: 600px; }
    
    .history-card {
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .page-title {
        font-size: 24px;
        font-weight: bold;
        color: #333;
        margin-bottom: 5px;
        border-left: 5px solid #d0011b;
        padding-left: 15px;
    }

    /* Table Styles */
    .history-table { width: 100%; border-collapse: collapse; margin-top: 25px; }
    .history-table thead { background: #f9f9f9; border-bottom: 2px solid #eee; }
    .history-table th { padding: 15px; text-align: left; font-size: 14px; color: #555; font-weight: bold; }
    .history-table td { padding: 15px; border-bottom: 1px solid #eee; font-size: 14px; color: #333; vertical-align: middle; }
    
    /* Status Badge Styles */
    .badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
        text-transform: uppercase;
    }
    .status-pending { background: #fff3cd; color: #856404; }   /* Vàng cam: Chờ xử lý */
    .status-shipping { background: #cce5ff; color: #004085; }  /* Xanh dương: Đang giao */
    .status-success { background: #d4edda; color: #155724; }   /* Xanh lá: Hoàn thành */
    .status-cancel { background: #f8d7da; color: #721c24; }    /* Đỏ: Đã hủy */

    /* Button Styles */
    .btn-detail {
        display: inline-block;
        padding: 6px 15px;
        background: white;
        color: #333;
        border: 1px solid #ddd;
        border-radius: 4px;
        text-decoration: none;
        font-size: 13px;
        transition: 0.3s;
    }
    .btn-detail:hover {
        background: #d0011b;
        color: white;
        border-color: #d0011b;
    }
    .btn-detail i { margin-right: 5px; }

    .empty-history {
        text-align: center;
        padding: 50px 0;
        color: #777;
    }
    .empty-history i {
        font-size: 60px;
        color: #ddd;
        margin-bottom: 20px;
    }
</style>

<div class="history-page">
    <div class="container history-card">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 class="page-title">Lịch sử đơn hàng</h2>
                <p style="color: #666; margin-left: 20px;">Quản lý và theo dõi các đơn hàng của bạn</p>
            </div>
            <a href="index.php" style="color: #d0011b; text-decoration: none; font-weight: bold;">
                <i class="fas fa-arrow-left"></i> Tiếp tục mua sắm
            </a>
        </div>

        <table class="history-table">
            <thead>
                <tr>
                    <th>Mã Đơn</th>
                    <th>Ngày đặt</th>
                    <th>Người nhận</th>
                    <th>Tổng tiền</th>
                    <th>Hình thức TT</th>
                    <th>Trạng thái</th>
                    <th style="text-align: center;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($data['orders'])): ?>
                    <?php foreach($data['orders'] as $order): ?>
                        <?php 
                            // Xử lý Logic hiển thị trạng thái
                            $statusLabel = 'Chờ xác nhận';
                            $statusClass = 'status-pending';

                            if($order['status'] == 2) { 
                                $statusLabel = 'Đang giao hàng'; 
                                $statusClass = 'status-shipping'; 
                            }
                            elseif($order['status'] == 3) { 
                                $statusLabel = 'Giao thành công'; 
                                $statusClass = 'status-success'; 
                            }
                            elseif($order['status'] == 0) { 
                                $statusLabel = 'Đã hủy'; 
                                $statusClass = 'status-cancel'; 
                            }
                        ?>
                        <tr>
                            <td><strong>#<?php echo $order['id']; ?></strong></td>
                            <td><?php echo date("d/m/Y H:i", strtotime($order['created_at'])); ?></td>
                            <td>
                                <div><?php echo $order['fullname']; ?></div>
                                <small style="color:#888;"><?php echo $order['phone']; ?></small>
                            </td>
                            <td style="color: #d0011b; font-weight: bold; font-size: 15px;">
                                <?php echo number_format($order['total_money']); ?>đ
                            </td>
                            <td>
                                <?php if(isset($order['payment_method']) && $order['payment_method'] == 'qr'): ?>
                                    <span style="font-size: 12px; color: blue;"><i class="fas fa-qrcode"></i> QR Code</span>
                                <?php else: ?>
                                    <span style="font-size: 12px; color: green;"><i class="fas fa-money-bill"></i> Tiền mặt</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge <?php echo $statusClass; ?>">
                                    <?php echo $statusLabel; ?>
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <a href="index.php?act=order_tracking&id=<?php echo $order['id']; ?>" class="btn-detail">
                                    <i class="fas fa-eye"></i> Chi tiết
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">
                            <div class="empty-history">
                                <i class="fas fa-shopping-bag"></i>
                                <h3>Bạn chưa có đơn hàng nào!</h3>
                                <p>Hãy dạo một vòng siêu thị và chọn món đồ yêu thích nhé.</p>
                                <a href="index.php" style="background: #d0011b; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; margin-top: 15px; display: inline-block;">Mua sắm ngay</a>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../inc/footer.php'; ?>