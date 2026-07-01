<style>
    .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 30px; }
    .stat-card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); display: flex; align-items: center; justify-content: space-between; }
    .stat-info h3 { font-size: 32px; color: #d0011b; margin: 5px 0; }
    .stat-info p { color: #666; font-weight: bold; }
    .stat-icon { font-size: 40px; color: #ddd; }
    
    .tables-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .table-box { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
    .table-box h4 { margin-bottom: 15px; border-bottom: 2px solid #f0f0f0; padding-bottom: 10px; color: #333; }
    
    .mini-table { width: 100%; border-collapse: collapse; }
    .mini-table th { text-align: left; padding: 8px; background: #f9f9f9; font-size: 13px; }
    .mini-table td { padding: 8px; border-bottom: 1px solid #eee; font-size: 13px; }
    .mini-img { width: 40px; height: 40px; object-fit: cover; border-radius: 4px; border: 1px solid #eee; }
</style>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-info">
            <p>TỔNG DOANH THU</p>
            <h3><?php echo number_format($data['revenue']); ?>đ</h3>
        </div>
        <div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <p>ĐƠN HÀNG THÀNH CÔNG</p>
            <h3><?php echo $data['order_count']; ?></h3>
        </div>
        <div class="stat-icon"><i class="fas fa-shopping-bag"></i></div>
    </div>
</div>

<div class="tables-grid">
    
    <div class="table-box">
        <h4 style="color: #28a745;"><i class="fas fa-chart-line"></i> Top sản phẩm bán chạy</h4>
        <?php if(!empty($data['top_selling'])): ?>
        <table class="mini-table">
            <thead>
                <tr>
                    <th>Ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Đã bán</th>
                    <th>Doanh thu SP</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['top_selling'] as $top): ?>
                <tr>
                    <td><img src="img/<?php echo $top['image']; ?>" class="mini-img"></td>
                    <td>
                        <strong><?php echo $top['name']; ?></strong><br>
                        <small><?php echo number_format($top['price']); ?>đ</small>
                    </td>
                    <td style="font-weight: bold; text-align: center;"><?php echo $top['total_sold']; ?></td>
                    <td style="color: #d0011b;"><?php echo number_format($top['total_sold'] * $top['price']); ?>đ</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p>Chưa có dữ liệu bán hàng.</p>
        <?php endif; ?>
    </div>

    <div class="table-box">
        <h4 style="color: #dc3545;"><i class="fas fa-box-open"></i> Sản phẩm chưa bán được (Tồn kho)</h4>
        <?php if(!empty($data['unsold'])): ?>
        <table class="mini-table">
            <thead>
                <tr>
                    <th>Ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá tiền</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['unsold'] as $bad): ?>
                <tr>
                    <td><img src="img/<?php echo $bad['image']; ?>" class="mini-img"></td>
                    <td><strong><?php echo $bad['name']; ?></strong></td>
                    <td><?php echo number_format($bad['price']); ?>đ</td>
                    <td>
                        <a href="index.php?act=admin_product_edit&id=<?php echo $bad['id']; ?>" style="color: blue; font-size: 12px;">Giảm giá ngay</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p style="color: green;">Tuyệt vời! Tất cả sản phẩm đều đã có người mua.</p>
        <?php endif; ?>
    </div>

</div>