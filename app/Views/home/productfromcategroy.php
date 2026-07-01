<?php require_once __DIR__ . '/../inc/header.php'; ?>

<section style="margin-top: 30px;">
    <div class="container">
        <h2 class="category-title" style="color: #d0011b; border-bottom: 2px solid #d0011b; padding-bottom: 10px;">
            Sản phẩm tại danh mục <?php echo htmlspecialchars($category['name']); ?>
        </h2>
        
        <div class="slider-product-one-content-items" id="api-product-from-category" style="display: flex; flex-wrap: wrap; margin: 0 -10px; margin-top: 20px;">
             <p style="text-align: center; width: 100%;">⏳ Đang tải sản phẩm...</p>
        </div>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Lấy ID danh mục từ PHP (Nhúng thẳng biến PHP vào JS)
    let categoryId = <?php echo isset($_GET['id']) ? intval($_GET['id']) : 0; ?>;
    
    // 2. Nếu có ID thì mới gọi API
    if (categoryId > 0) {
        // Thay url này bằng đường dẫn tới file API PHP của bạn
        let apiUrl = 'http://localhost/baitaplon/api/get_products_by_category.php?id=' + categoryId;

        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                let container = document.getElementById('api-product-from-category');
                container.innerHTML = ''; // Xóa sạch chữ "Đang tải..."
                
                if (data.length > 0) {
                    // 3. Vòng lặp đắp HTML cho từng sản phẩm
                    data.forEach(product => {
                        container.innerHTML += `
                            <div style="width: 20%; padding: 10px; box-sizing: border-box;">
                                <div style="border: 1px solid #ddd; padding: 10px; text-align: center;">
                                    <img src="public/uploads/${product.image}" alt="${product.name}" style="width: 100%; height: auto;">
                                    <h3 style="font-size: 16px; margin: 10px 0;">${product.name}</h3>
                                    <p style="color: #d0011b; font-weight: bold;">${product.price} VNĐ</p>
                                    <a href="index.php?act=product_detail&id=${product.id}" style="display: block; background: #333; color: white; padding: 5px; text-decoration: none; margin-top: 10px;">Xem chi tiết</a>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    container.innerHTML = '<p style="text-align: center; width: 100%; color: #888;">Chưa có sản phẩm nào trong danh mục này.</p>';
                }
            })
            .catch(error => {
                console.error('Lỗi khi gọi API:', error);
                document.getElementById('api-product-from-category').innerHTML = '<p style="color: red; text-align: center; width: 100%;">Lỗi kết nối máy chủ!</p>';
            });
    }
});
</script>

<?php require_once __DIR__ . '/../inc/footer.php'; ?>