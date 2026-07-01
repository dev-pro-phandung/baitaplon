<?php require_once __DIR__ . '/../inc/header.php'; ?>

<section class="slider">
    <div class="container">
        <div class="slider-content">
            <div class="slider-content-left">
                <div class="slider-content-top-left" style="position: relative; overflow: hidden;">
                    <div id="api-banner-slides" style="display: flex; transition: transform 0.5s ease-in-out; width: 100%;">
                        <p style="text-align:center; width:100%; padding:20px;">Đang tải Banner...</p>
                    </div>
                    <div class="slider-nav" style="position: absolute; top: 50%; width: 100%; display: flex; justify-content: space-between; transform: translateY(-50%); pointer-events: none;">
                        <i class="fas fa-chevron-left" onclick="window.moveSlide(-1)" style="cursor: pointer; pointer-events: auto; background: rgba(0,0,0,0.3); color: white; padding: 10px 15px; border-radius: 0 5px 5px 0;"></i>
                        <i class="fas fa-chevron-right" onclick="window.moveSlide(1)" style="cursor: pointer; pointer-events: auto; background: rgba(0,0,0,0.3); color: white; padding: 10px 15px; border-radius: 5px 0 0 5px;"></i>
                    </div>
                </div>
                <div class="slider-content-left-bottom" id="api-banner-tabs"></div>
            </div>
            <div class="slider-content-right" id="api-banner-right">
                <li><a href="#"><img src="img/img1.png" alt=""></a></li>
                <li><a href="#"><img src="img/img2.png" alt=""></a></li>
                <li><a href="#"><img src="img/img3.png" alt=""></a></li>
                <li><a href="#"><img src="img/img4.png" alt=""></a></li>
            </div>
        </div>
    </div>
</section>

<section class="category-section" style="margin-top: 30px;">
    <div class="container">
        <h2 class="category-title" style="margin-bottom: 15px;">Danh mục sản phẩm</h2>
        <div class="category-list" id="api-categories" style="display: flex; gap: 15px; overflow-x: auto;">
            <p style="text-align: center; width: 100%;">Đang tải danh mục...</p>
        </div>
    </div>
</section>

<section style="margin-top: 30px;">
    <div class="container">
        <h2 class="category-title" style="color: #d0011b; border-bottom: 2px solid #d0011b; padding-bottom: 10px;">🔥 Sản phẩm bán chạy</h2>
        <div class="slider-product-one-content-items" id="api-best-sellers" style="display: flex; flex-wrap: wrap; margin: 0 -10px; margin-top: 20px;">
             <p style="text-align: center; width: 100%;">Đang tải sản phẩm...</p>
        </div>
    </div>
</section>

<section style="margin-top: 30px;">
    <div class="container">
        <h2 class="category-title" style="color: #28a745; border-bottom: 2px solid #28a745; padding-bottom: 10px;">✨ Sản phẩm mới</h2>
        <div class="slider-product-one-content-items" id="api-new-products" style="display: flex; flex-wrap: wrap; margin: 0 -10px; margin-top: 20px;">
             <p style="text-align: center; width: 100%;">Đang tải sản phẩm...</p>
        </div>
    </div>
</section>

<section style="margin-top: 30px; margin-bottom: 50px;">
    <div class="container">
        <h2 class="category-title" style="color: #007bff; border-bottom: 2px solid #007bff; padding-bottom: 10px;">💡 Gợi ý cho bạn</h2>
        <div class="slider-product-one-content-items" id="api-all-products" style="display: flex; flex-wrap: wrap; margin: 0 -10px; margin-top: 20px;">
             <p style="text-align: center; width: 100%;">Đang tải sản phẩm...</p>
        </div>
    </div>
</section>

<script src="js/home_api.js"></script>

<?php require_once __DIR__ . '/../inc/footer.php'; ?>