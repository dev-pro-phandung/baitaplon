<?php require_once __DIR__ . '/../inc/header.php'; ?>

<section class="slider">
    <div class="container">
        <div class="slider-content">
            <div class="slider-content-left">
                
                <div class="slider-content-top-left" style="position: relative; overflow: hidden;">
                    
                    <div id="bannerSlides" style="display: flex; transition: transform 0.5s ease-in-out; width: 100%;">
                        <?php if (!empty($data['sliders'])): ?>
                            <?php foreach ($data['sliders'] as $key => $slide): ?>
                                <a href="<?php echo $slide['link']; ?>" class="banner-slide" style="min-width: 100%; display: block;">
                                    <img src="img/<?php echo $slide['image']; ?>" alt="<?php echo $slide['title']; ?>" style="width: 100%; display: block; object-fit: cover;">
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <a href="#" class="banner-slide" style="min-width: 100%; display: block;">
                                <img src="img/banner.png" alt="Default" style="width: 100%; display: block;">
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="slider-nav" style="position: absolute; top: 50%; width: 100%; display: flex; justify-content: space-between; transform: translateY(-50%); pointer-events: none;">
                        <i class="fas fa-chevron-left" onclick="window.moveSlide(-1)" 
                           style="cursor: pointer; pointer-events: auto; background: rgba(0,0,0,0.3); color: white; padding: 10px 15px; border-radius: 0 5px 5px 0;"></i>
                        
                        <i class="fas fa-chevron-right" onclick="window.moveSlide(1)" 
                           style="cursor: pointer; pointer-events: auto; background: rgba(0,0,0,0.3); color: white; padding: 10px 15px; border-radius: 5px 0 0 5px;"></i>
                    </div>

                </div>

                <div class="slider-content-left-bottom">
                    <?php if (!empty($data['sliders'])): ?>
                        <?php foreach ($data['sliders'] as $key => $slide): ?>
                            <li class="banner-tab <?php echo ($key == 0) ? 'active' : ''; ?>" onclick="window.goToSlide(<?php echo $key; ?>)">
                                <?php echo !empty($slide['title']) ? $slide['title'] : 'Tiêu đề ' . ($key + 1); ?>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="active">Mặc định</li>
                    <?php endif; ?>
                </div>
            </div>

            <div class="slider-content-right">
                <li><a href="#"><img src="img/img1.png" alt=""></a></li>
                <li><a href="#"><img src="img/img2.png" alt=""></a></li>
                <li><a href="#"><img src="img/img3.png" alt=""></a></li>
                <li><a href="#"><img src="img/img4.png" alt=""></a></li>
            </div>
        </div>
    </div>
</section>

<section class="banner1">
    <div class="container">
        <?php if (!empty($data['mid_banners']) && isset($data['mid_banners'][0])): ?>
            <a href="<?php echo $data['mid_banners'][0]['link']; ?>">
                <img src="img/<?php echo $data['mid_banners'][0]['image']; ?>" style="width: 100%; border-radius: 10px;">
            </a>
        <?php else: ?>
            <img src="img/banner.png" style="width: 100%; border-radius: 10px;">
        <?php endif; ?>
    </div>
</section>

<section>
    <div class="container">
        <h2 class="category-title" style="margin-top: 20px;">Sản phẩm bán chạy</h2>
        <div class="slider-product-one-content-items">
            <?php if (!empty($data['best_sellers'])): ?>
                <?php foreach ($data['best_sellers'] as $sp): ?>
                    <div class="slider-product-one-content-item">
                        <a href="index.php?act=detail&id=<?php echo $sp['id']; ?>">
                            <img src="img/<?php echo $sp['image']; ?>" alt="<?php echo $sp['name']; ?>">
                        </a>
                        <h3><a href="index.php?act=detail&id=<?php echo $sp['id']; ?>"><?php echo $sp['name']; ?></a></h3>
                        <p>
                            <?php echo number_format($sp['price']); ?>đ
                            <sub>/<?php echo $sp['unit']; ?></sub>
                            <i class="fas fa-shopping-cart" 
                               onclick="openProductModal(this)" 
                               data-id="<?php echo $sp['id']; ?>" 
                               data-name="<?php echo $sp['name']; ?>" 
                               data-price="<?php echo number_format($sp['price']); ?>" 
                               data-image="img/<?php echo $sp['image']; ?>" 
                               data-unit="<?php echo $sp['unit']; ?>" 
                               data-code="SP<?php echo $sp['id']; ?>" 
                               style="cursor: pointer;"></i>
                        </p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="banner2" style="margin: 30px 0;">
    <div class="container">
        <?php 
        if (!empty($data['banners'])): 
            foreach ($data['banners'] as $banner): 
                if ($banner['position'] == 'banner_body'): 
        ?>
                    <div class="banner-item" style="margin-bottom: 20px;">
                        <a href="<?php echo $banner['link']; ?>">
                            <img src="img/<?php echo $banner['image']; ?>" 
                                 alt="<?php echo $banner['title']; ?>" 
                                 style="width: 100%; border-radius: 10px; display: block; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                        </a>
                    </div>
        <?php 
                endif; 
            endforeach; 
        endif; 
        ?>
    </div>
</section>

<section>
    <div class="container">
        <h2 class="category-title" style="margin-top: 20px;">Sản phẩm mới</h2>
        <div class="slider-product-two-content-items">
            <?php if (!empty($data['new_products'])): ?>
                <?php foreach ($data['new_products'] as $sp): ?>
                    <div class="slider-product-two-content-item">
                        <a href="index.php?act=detail&id=<?php echo $sp['id']; ?>">
                            <img src="img/<?php echo $sp['image']; ?>" alt="<?php echo $sp['name']; ?>">
                        </a>
                        <h3><a href="index.php?act=detail&id=<?php echo $sp['id']; ?>"><?php echo $sp['name']; ?></a></h3>
                        <p>
                            <?php echo number_format($sp['price']); ?>đ
                            <sub>/<?php echo $sp['unit']; ?></sub>
                            <i class="fas fa-shopping-cart" onclick="openProductModal(this)" data-id="<?php echo $sp['id']; ?>" data-name="<?php echo $sp['name']; ?>" data-price="<?php echo number_format($sp['price']); ?>" data-image="img/<?php echo $sp['image']; ?>" data-unit="<?php echo $sp['unit']; ?>" data-code="SP<?php echo $sp['id']; ?>" style="cursor: pointer;"></i>
                        </p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="category-section">
    <div class="container">
        <h2 class="category-title">Danh mục sản phẩm</h2>
        <div class="category-list" id="categoryList">
            <?php if (!empty($data['categories'])): ?>
                <?php foreach ($data['categories'] as $cat): ?>
                    <?php if ($cat['parent_id'] == 0): ?>
                        <div class="category-item">
                            <div class="category-img">
                                <a href="index.php?act=category&id=<?php echo $cat['id']; ?>">
                                    <img src="img/<?php echo $cat['image']; ?>" alt="<?php echo $cat['name']; ?>">
                                </a>
                            </div>
                            <p><?php echo $cat['name']; ?></p>
                        </div>
                    <?php endif; ?> 
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="custom-scroll-track"><div class="custom-scroll-thumb" id="scrollThumb"></div></div>
    </div>
</section>


<style>
    /* Mặc định: Ẩn các sản phẩm từ vị trí thứ 11 trở đi */
    #suggestionList .product-item:nth-child(n+11) {
        display: none !important;
    }

    /* Khi bấm nút (có class expanded): Hiện lại tất cả */
    #suggestionList.expanded .product-item:nth-child(n+11) {
        display: block !important;
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<section class="suggestion-section" style="margin-top: 30px;">
    <div class="container">
        <div class="section-header" style="background: white; padding: 15px; border-bottom: 2px solid #d0011b; margin-bottom: 20px;">
            <h2 style="color: #d0011b; text-transform: uppercase; font-size: 20px; font-weight: 700; margin: 0;">
                <i class="fas fa-lightbulb"></i> Gợi ý hôm nay
            </h2>
        </div>

        <div class="product-list" id="suggestionList" style="display: flex; flex-wrap: wrap; margin: 0 -10px;">
            
            <?php if (!empty($data['suggestions'])): ?>
                <?php foreach ($data['suggestions'] as $product): ?>
                    
                    <div class="product-item" style="width: 20%; padding: 0 10px; margin-bottom: 20px;">
                        <div class="product-card" style="background: white; border: 1px solid #eee; transition: 0.3s; height: 100%;">
                            <a href="index.php?act=detail&id=<?php echo $product['id']; ?>" class="product-img" style="display: block; position: relative; padding-top: 100%; overflow: hidden;">
                                <img src="img/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" 
                                     style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain;">
                            </a>
                            <div class="product-info" style="padding: 10px;">
                                <h3 style="font-size: 14px; margin-bottom: 5px; height: 38px; overflow: hidden;">
                                    <a href="index.php?act=detail&id=<?php echo $product['id']; ?>"><?php echo $product['name']; ?></a>
                                </h3>
                                <div class="price" style="color: #d0011b; font-weight: bold;"><?php echo number_format($product['price']); ?>đ</div>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            <?php else: ?>
                <p style="padding: 10px;">Chưa có sản phẩm gợi ý.</p>
            <?php endif; ?>

        </div>

        <div class="view-more-container" style="text-align: center; margin-top: 20px;">
            <button id="loadMoreSuggestions" style="padding: 10px 40px; background: white; border: 1px solid #d0011b; color: #d0011b; cursor: pointer; font-weight: bold;">
                Xem thêm <i class="fas fa-chevron-down"></i>
            </button>
        </div>
    </div>
</section>

<script>
    document.getElementById('loadMoreSuggestions').addEventListener('click', function() {
        var list = document.getElementById('suggestionList');
        if(list) {
            list.classList.add('expanded'); // Thêm class để CSS hiển thị các sản phẩm ẩn
            this.style.display = 'none';    // Ẩn nút sau khi bấm
        }
    });
</script>

<script src="public/js/script.js"></script>

<?php require_once __DIR__ . '/../inc/footer.php'; ?>