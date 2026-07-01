<?php require_once __DIR__ . '/../inc/header.php'; ?>

<style>
    .filter-page { padding: 40px 0; background-color: #f9f9f9; }
    .filter-sidebar { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); height: fit-content; }
    .filter-title { font-size: 18px; font-weight: bold; margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 10px; }
    
    .product-card {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 15px;
        transition: 0.3s;
        position: relative;
    }
    .product-card:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.1); transform: translateY(-3px); }
    .card-img img { width: 100%; height: 200px; object-fit: contain; }
    .card-title a { color: #333; text-decoration: none; font-weight: 600; font-size: 16px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 44px; }
    
    .price-wrap { margin-top: 10px; display: flex; justify-content: space-between; align-items: center; }
    .current-price { color: #d0011b; font-weight: bold; font-size: 18px; }
    .old-price { color: #999; text-decoration: line-through; font-size: 13px; }
    
    .btn-buy-icon {
        width: 40px; height: 40px;
        background: #d0011b; color: white;
        border: none; border-radius: 50%;
        cursor: pointer; display: flex;
        align-items: center; justify-content: center;
        transition: 0.3s;
    }
    .btn-buy-icon:hover { background: #a00; }
</style>

<div class="filter-page">
    <div class="container" style="display: flex; gap: 30px;">
        
        <aside style="width: 250px; flex-shrink: 0;">
            <div class="filter-sidebar">
                <h3 class="filter-title"><i class="fas fa-filter"></i> Bộ lọc tìm kiếm</h3>
                
                <form action="index.php" method="GET">
                    <input type="hidden" name="act" value="filter">

                    <div style="margin-bottom: 15px;">
                        <label style="font-weight: 600; display: block; margin-bottom: 5px;">Từ khóa</label>
                        <input type="text" name="keyword" value="<?php echo htmlspecialchars($data['filters']['keyword']); ?>" class="form-control" placeholder="Nhập tên sản phẩm...">
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label style="font-weight: 600; display: block; margin-bottom: 5px;">Danh mục</label>
                        <select name="cat_id" class="form-control">
                            <option value="">-- Tất cả --</option>
                            <?php foreach($data['categories'] as $c): ?>
                                <option value="<?php echo $c['id']; ?>" 
                                    <?php echo ($data['filters']['cat_id'] == $c['id']) ? 'selected' : ''; ?>>
                                    <?php echo $c['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label style="font-weight: 600; display: block; margin-bottom: 5px;">Khoảng giá</label>
                        <input type="number" name="price_min" value="<?php echo $data['filters']['price_min']; ?>" class="form-control" placeholder="Từ (VNĐ)" style="margin-bottom: 5px;">
                        <input type="number" name="price_max" value="<?php echo $data['filters']['price_max']; ?>" class="form-control" placeholder="Đến (VNĐ)">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="font-weight: 600; display: block; margin-bottom: 5px;">Sắp xếp theo</label>
                        <select name="sort" class="form-control">
                            <option value="newest" <?php echo ($data['filters']['sort'] == 'newest') ? 'selected' : ''; ?>>Mới nhất</option>
                            <option value="price_asc" <?php echo ($data['filters']['sort'] == 'price_asc') ? 'selected' : ''; ?>>Giá tăng dần</option>
                            <option value="price_desc" <?php echo ($data['filters']['sort'] == 'price_desc') ? 'selected' : ''; ?>>Giá giảm dần</option>
                        </select>
                    </div>

                    <button type="submit" style="width: 100%; background: #333; color: white; padding: 12px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                        LỌC KẾT QUẢ
                    </button>
                    
                    <?php if(!empty($data['filters']['keyword']) || !empty($data['filters']['cat_id'])): ?>
                        <a href="index.php?act=filter" style="display: block; text-align: center; margin-top: 10px; color: #d0011b; font-size: 14px;">
                            <i class="fas fa-undo"></i> Xóa bộ lọc
                        </a>
                    <?php endif; ?>
                </form>
            </div>
        </aside>

        <main style="flex: 1;">
            <div style="margin-bottom: 20px;">
                <?php if(!empty($data['filters']['keyword'])): ?>
                    <h2>Kết quả cho: "<span style="color: #d0011b;"><?php echo htmlspecialchars($data['filters']['keyword']); ?></span>"</h2>
                <?php else: ?>
                    <h2>Tất cả sản phẩm</h2>
                <?php endif; ?>
                <p style="color: #666; font-size: 14px;">Tìm thấy <strong><?php echo count($data['products']); ?></strong> sản phẩm phù hợp</p>
            </div>

            <div class="product-grid-container" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                <?php if(!empty($data['products'])): ?>
                    <?php foreach($data['products'] as $sp): ?>
                        <div class="product-card">
                            <div class="card-img">
                                <a href="index.php?act=detail&id=<?php echo $sp['id']; ?>">
                                    <img src="img/<?php echo $sp['image']; ?>" alt="<?php echo $sp['name']; ?>">
                                </a>
                            </div>

                            <h3 class="card-title" style="margin-top: 15px;">
                                <a href="index.php?act=detail&id=<?php echo $sp['id']; ?>"><?php echo $sp['name']; ?></a>
                            </h3>

                            <div class="price-wrap">
                                <div>
                                    <div class="current-price"><?php echo number_format($sp['price']); ?>đ</div>
                                    <?php if($sp['old_price'] > $sp['price']): ?>
                                        <div class="old-price"><?php echo number_format($sp['old_price']); ?>đ</div>
                                    <?php endif; ?>
                                </div>

                                <button class="btn-buy-icon"
                                        onclick="openProductModal(this)" 
                                        data-id="<?php echo $sp['id']; ?>"
                                        data-name="<?php echo $sp['name']; ?>"
                                        data-price="<?php echo number_format($sp['price']); ?>"
                                        data-image="img/<?php echo $sp['image']; ?>"
                                        data-unit="<?php echo $sp['unit']; ?>"
                                        data-code="SP<?php echo $sp['id']; ?>">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column: span 3; text-align: center; padding: 50px; background: #fff; border-radius: 8px;">
                        <img src="img/no-product.png" alt="Not found" style="width: 100px; opacity: 0.5; margin-bottom: 20px;">
                        <p style="font-size: 18px; color: #666;">Rất tiếc, không tìm thấy sản phẩm nào!</p>
                        <a href="index.php" style="color: #d0011b; font-weight: bold; text-decoration: none;">Xem tất cả sản phẩm</a>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>

<script src="js/script.js"></script>

<?php require_once __DIR__ . '/../inc/footer.php'; ?>