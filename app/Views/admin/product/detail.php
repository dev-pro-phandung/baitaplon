<div class="card">
    <div class="header-action" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 1px solid #eee; padding-bottom: 20px;">
        <h2 style="margin: 0; color: #333;">Chi tiết sản phẩm: #<span id="detail-id">...</span></h2>
        <div style="display: flex; gap: 5px;">
            <a id="btn-edit" href="#" style="background: #ffc107; color: #333; padding: 8px 15px; border-radius: 4px; text-decoration: none; font-weight: bold;">
                <i class="fas fa-edit"></i> Sửa
            </a>
            
            <button id="btn-delete" onclick="deleteCurrentProduct()" style="background: #dc3545; color: white; border: none; cursor: pointer; padding: 8px 15px; border-radius: 4px; font-weight: bold;">
                <i class="fas fa-trash"></i> Xóa
            </button>
            
            <a href="index.php?act=admin_product_list" style="background: #6c757d; color: white; padding: 8px 15px; border-radius: 4px; text-decoration: none; font-weight: bold;">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="detail-grid" style="display: grid; grid-template-columns: 300px 1fr; gap: 40px;">
        <div class="img-box" style="text-align: center; border: 1px solid #ddd; padding: 20px; border-radius: 8px;">
            <img id="detail-img" src="" alt="Ảnh sản phẩm" style="max-width: 100%; height: auto; display: none;">
            <p style="margin-top: 10px; color: #999; font-size: 14px;">Ảnh đại diện</p>
        </div>

        <div class="info-box">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <th style="width: 150px; text-align: left; padding: 12px 0; color: #666; border-bottom: 1px solid #eee;">Tên sản phẩm:</th>
                    <td id="detail-name" style="padding: 12px 0; border-bottom: 1px solid #eee; font-size: 18px; font-weight: bold; color: #d0011b;">
                        Đang tải...
                    </td>
                </tr>
                <tr>
                    <th style="text-align: left; padding: 12px 0; color: #666; border-bottom: 1px solid #eee;">Danh mục:</th>
                    <td id="detail-category" style="padding: 12px 0; border-bottom: 1px solid #eee;">Đang tải...</td>
                </tr>
                <tr>
                    <th style="text-align: left; padding: 12px 0; color: #666; border-bottom: 1px solid #eee;">Giá bán:</th>
                    <td id="detail-price" style="padding: 12px 0; border-bottom: 1px solid #eee; color: #d0011b; font-weight: bold;">
                        Đang tải...
                    </td>
                </tr>
                <tr>
                    <th style="text-align: left; padding: 12px 0; color: #666; border-bottom: 1px solid #eee;">Giá gốc:</th>
                    <td id="detail-old-price" style="padding: 12px 0; border-bottom: 1px solid #eee;">Đang tải...</td>
                </tr>
                <tr>
                    <th style="text-align: left; padding: 12px 0; color: #666; border-bottom: 1px solid #eee;">Đơn vị tính:</th>
                    <td id="detail-unit" style="padding: 12px 0; border-bottom: 1px solid #eee;">Đang tải...</td>
                </tr>
                <tr>
                    <th style="text-align: left; padding: 12px 0; color: #666; border-bottom: 1px solid #eee;">Trạng thái:</th>
                    <td id="detail-status" style="padding: 12px 0; border-bottom: 1px solid #eee;">
                        Đang tải...
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<script>
let currentProductId = null;

document.addEventListener("DOMContentLoaded", async function() {
    const urlParams = new URLSearchParams(window.location.search);
    currentProductId = urlParams.get('id');

    if (!currentProductId) {
        alert("Không tìm thấy ID sản phẩm!");
        window.location.href = 'index.php?act=admin_product_list';
        return;
    }

    // Load danh mục để lấy tên ánh xạ với category_id của sản phẩm
    const categoriesMap = await fetchCategoriesMap();
    
    // Tải chi tiết sản phẩm
    loadProductDetail(currentProductId, categoriesMap);
});

// Hàm gọi API lấy danh mục biến thành Object {id: "Tên danh mục"}
async function fetchCategoriesMap() {
    let map = {};
    try {
        const response = await fetch('index.php?act=api_categories');
        const res = await response.json();
        if (res.status === 200) {
            res.data.categories.forEach(cat => {
                map[cat.id] = cat.name;
            });
        }
    } catch (err) {
        console.error("Lỗi lấy danh mục:", err);
    }
    return map;
}

// Hàm lấy và hiển thị chi tiết sản phẩm
async function loadProductDetail(id, catMap) {
    try {
        const response = await fetch(`index.php?act=api_product_detail&id=${id}`);
        const rawText = await response.text();
        const res = JSON.parse(rawText);

        if (res.status === 200) {
            const p = res.data.info;

            // Cập nhật text hiển thị
            document.getElementById('detail-id').innerText = p.id;
            document.getElementById('detail-name').innerText = p.name;
            document.getElementById('detail-unit').innerText = p.unit || '-';
            
            // Xử lý giá tiền
            let formatter = new Intl.NumberFormat('vi-VN');
            document.getElementById('detail-price').innerText = formatter.format(p.price) + ' VNĐ';
            document.getElementById('detail-old-price').innerText = (p.old_price > 0) ? formatter.format(p.old_price) + ' VNĐ' : '-';

            // Xử lý Danh mục
            document.getElementById('detail-category').innerText = catMap[p.category_id] ? catMap[p.category_id] : ('ID: ' + p.category_id);

            // Xử lý Hình ảnh
            if (p.image) {
                const imgEl = document.getElementById('detail-img');
                imgEl.src = 'img/' + p.image;
                imgEl.style.display = 'inline-block';
            }

            // Xử lý Trạng thái (Mới về)
            const statusEl = document.getElementById('detail-status');
            if (p.is_new == 1) {
                statusEl.innerHTML = '<span style="background: #28a745; color: white; padding: 3px 8px; border-radius: 10px; font-size: 12px;">Hàng mới</span>';
            } else {
                statusEl.innerHTML = '<span style="background: #6c757d; color: white; padding: 3px 8px; border-radius: 10px; font-size: 12px;">Bình thường</span>';
            }

            // Cập nhật link cho nút Sửa
            document.getElementById('btn-edit').href = `index.php?act=admin_product_edit&id=${p.id}`;

        } else {
            alert("Sản phẩm không tồn tại!");
            window.location.href = 'index.php?act=admin_product_list';
        }
    } catch (err) {
        console.error("Lỗi:", err);
        alert("Có lỗi xảy ra khi tải dữ liệu.");
    }
}

// Hàm gọi API Xóa trực tiếp từ trang chi tiết
async function deleteCurrentProduct() {
    if (!currentProductId) return;

    if (confirm("CẢNH BÁO: Bạn chắc chắn muốn xóa sản phẩm này?")) {
        try {
            const response = await fetch(`index.php?act=api_product_delete&id=${currentProductId}`, {
                method: "DELETE" 
            });

            const res = await response.json();

            if (res.status === 200) {
                alert("Đã xóa sản phẩm thành công!");
                // Xóa xong thì đá văng về trang danh sách
                window.location.href = 'index.php?act=admin_product_list';
            } else {
                alert("Lỗi: " + res.message);
            }
        } catch (err) {
            console.error("Lỗi gọi API Delete:", err);
            alert("Mất kết nối! Có thể sản phẩm này đang nằm trong đơn hàng.");
        }
    }
}
</script>