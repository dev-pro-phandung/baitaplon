<?php require_once __DIR__ . '/../inc/header.php'; ?>

<style>
    .search-page { padding: 40px 0; min-height: 500px; }
    .search-title { margin-bottom: 30px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
    
    /* Copy CSS Grid sản phẩm từ trang chủ */
    .product-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 20px; }
    .product-card { background: white; border-radius: 5px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.05); transition: 0.3s; }
    .product-card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    .product-img { width: 100%; height: 180px; object-fit: cover; }
    .product-info { padding: 15px; text-align: center; }
    .product-name { font-size: 14px; font-weight: bold; margin-bottom: 10px; display: block; height: 38px; overflow: hidden; color: #333; text-decoration: none;}
    .price { color: #d0011b; font-weight: bold; font-size: 16px; }
    .btn-buy { display: block; background: #d0011b; color: white; padding: 8px; margin-top: 10px; border-radius: 4px; font-size: 13px; text-decoration: none;}
</style>

<div class="container search-page">
    <div class="search-title">
        <h2>Kết quả tìm kiếm cho: "<?php echo htmlspecialchars($data['keyword']); ?>"</h2>
        <p style="color: #666;">Tìm thấy <?php echo count($data['products']); ?> sản phẩm.</p>
    </div>

    <?php if (!empty($data['products'])): ?>
        <div class="product-grid">
            <?php foreach($data['products'] as $sp): ?>
            <div class="product-card">
                <a href="index.php?act=detail&id=<?php echo $sp['id']; ?>">
                    <img src="img/<?php echo $sp['image']; ?>" class="product-img">
                </a>
                <div class="product-info">
                    <a href="index.php?act=detail&id=<?php echo $sp['id']; ?>" class="product-name">
                        <?php echo $sp['name']; ?>
                    </a>
                    <div class="price">
                        <?php echo number_format($sp['price']); ?>đ
                    </div>
                    <a href="index.php?act=detail&id=<?php echo $sp['id']; ?>" class="btn-buy">
                        Xem chi tiết
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div style="text-align: center; margin-top: 50px;">
            <i class="fas fa-search" style="font-size: 50px; color: #ddd;"></i>
            <h3>Không tìm thấy sản phẩm nào!</h3>
            <p>Vui lòng thử lại với từ khóa khác.</p>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../inc/footer.php'; ?>