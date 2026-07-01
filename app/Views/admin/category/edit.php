<style>
    .admin-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        overflow: hidden;
        max-width: 900px;
        margin: 20px auto;
        font-family: Arial, sans-serif;
    }
    .card-header {
        background: #f8f9fa;
        padding: 15px 20px;
        border-bottom: 1px solid #eee;
    }
    .card-header h3 { margin: 0; color: #333; font-size: 18px; text-transform: uppercase; }
    .card-body { padding: 25px; }
    
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; font-size: 14px; }
    
    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        box-sizing: border-box; 
    }
    .form-control:focus { border-color: #d0011b; outline: none; }

    .image-preview-box {
        display: flex;
        align-items: flex-start;
        gap: 20px;
        background: #f9f9f9;
        padding: 15px;
        border-radius: 8px;
        border: 1px dashed #ccc;
    }
    .current-img-wrapper { text-align: center; }
    .current-img-wrapper img {
        max-height: 120px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        display: block;
        margin-bottom: 5px;
        background: #fff;
    }
    .upload-btn-wrapper { flex: 1; }
    
    .btn-action {
        display: inline-block;
        padding: 12px 25px;
        border: none;
        border-radius: 5px;
        font-weight: bold;
        cursor: pointer;
        text-transform: uppercase;
        font-size: 14px;
        transition: 0.3s;
    }
    .btn-save { background: #d0011b; color: white; }
    .btn-save:hover { background: #b00015; }
    
    .btn-back { background: #e9ecef; color: #333; text-decoration: none; margin-left: 10px; }
    .btn-back:hover { background: #ddd; }

    @media (max-width: 600px) {
        .form-grid, .image-preview-box { grid-template-columns: 1fr; display: block; }
        .current-img-wrapper { margin-bottom: 15px; }
    }
</style>

<div class="admin-card">
    <div class="card-header">
        <h3><i class="fas fa-edit"></i> Chỉnh sửa Danh mục</h3>
    </div>

    <div class="card-body">
        <form id="editCategoryForm" onsubmit="submitEditCategory(event)">
            <input type="hidden" name="id" id="category_id">

            <div class="form-grid">
                <div class="form-group">
                    <label>Tên danh mục <span style="color:red">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" required placeholder="Nhập tên danh mục...">
                </div>

                <div class="form-group">
                    <label>Thuộc danh mục cha</label>
                    <select name="parent_id" id="parent_id" class="form-control">
                        <option value="0">Đang tải dữ liệu...</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Hình ảnh đại diện</label>
                <div class="image-preview-box">
                    <div class="current-img-wrapper">
                        <span style="font-size:12px; color:#666; display:block; margin-bottom:5px;">Ảnh hiện tại:</span>
                        <img id="preview_img" src="" alt="Đang tải ảnh..." style="display: none;">
                    </div>

                    <div class="upload-btn-wrapper">
                        <label for="file-upload" style="cursor:pointer; display:block; margin-bottom:5px;">Chọn ảnh mới thay thế:</label>
                        <input type="file" name="image" id="file-upload" class="form-control" accept="image/*" style="padding: 5px;">
                        <p style="font-size: 12px; color: #888; margin-top: 5px;">
                            <i class="fas fa-info-circle"></i> Chỉ chấp nhận file ảnh (jpg, png, jpeg). Nếu không chọn, ảnh cũ sẽ được giữ nguyên.
                        </p>
                    </div>
                </div>
            </div>

            <div style="margin-top: 30px; text-align: right;">
                <button type="submit" class="btn-action btn-save">
                    <i class="fas fa-save"></i> Lưu cập nhật
                </button>
                <a href="index.php?act=admin_category_list" class="btn-action btn-back">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Lấy ID danh mục từ thanh URL
    const urlParams = new URLSearchParams(window.location.search);
    const catId = urlParams.get('id');

    if (!catId) {
        alert("Không tìm thấy ID danh mục!");
        window.location.href = 'index.php?act=admin_category_list';
        return;
    }

    // 2. Gọi hàm tải dữ liệu danh mục
    loadCategoryData(catId);
});

async function loadCategoryData(targetId) {
    try {
        // Gọi API lấy toàn bộ danh mục
        const response = await fetch('index.php?act=api_categories');
        const res = await response.json();

        if (res.status === 200) {
            const categories = res.data.categories;
            let currentCategory = null;
            let optionsHtml = '<option value="0">--- Là danh mục gốc ---</option>';

            // Duyệt mảng: Tìm danh mục hiện tại VÀ ghép thẻ Option cho danh mục cha
            categories.forEach(cat => {
                if (cat.id == targetId) {
                    currentCategory = cat; // Lưu lại dữ liệu của danh mục đang sửa
                } else {
                    // Các danh mục khác thì đưa vào ô Chọn danh mục cha
                    optionsHtml += `<option value="${cat.id}">${cat.name}</option>`;
                }
            });

            // Nếu không tìm thấy danh mục này trong Database
            if (!currentCategory) {
                alert("Danh mục không tồn tại hoặc đã bị xóa!");
                window.location.href = 'index.php?act=admin_category_list';
                return;
            }

            // 3. Đổ dữ liệu vào Form
            document.getElementById('parent_id').innerHTML = optionsHtml;
            document.getElementById('category_id').value = currentCategory.id || '';
            document.getElementById('name').value = currentCategory.name || '';
            
            // Nếu có parent_id thì set select, không có thì để gốc
            if (currentCategory.parent_id) {
                document.getElementById('parent_id').value = currentCategory.parent_id;
            }

            // Xử lý hiển thị ảnh cũ
            const previewImg = document.getElementById('preview_img');
            if (currentCategory.image) {
                previewImg.src = 'img/' + currentCategory.image;
            } else {
                previewImg.src = 'img/no-image.png'; // Ảnh mặc định nếu chưa có
            }
            previewImg.style.display = 'block';

        } else {
            alert("Danh mục này không tồn tại!");
            window.location.href = 'index.php?act=admin_category_list';
        }
    } catch (err) {
        console.error("Lỗi API:", err);
        alert("Mất kết nối đến máy chủ khi tải dữ liệu danh mục!");
    }
}

// 4. Xử lý Gửi Form Cập nhật lên API
async function submitEditCategory(event) {
    event.preventDefault(); 
    const form = document.getElementById('editCategoryForm');
    const formData = new FormData(form);

    const submitBtn = form.querySelector('.btn-save');
    const originalBtnText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ĐANG XỬ LÝ...';
    submitBtn.disabled = true;

    try {
        const response = await fetch('index.php?act=api_category_update', {
            method: 'POST',
            body: formData
        });

        const res = await response.json();

        if (res.status === 200) {
            alert('🎉 Cập nhật danh mục thành công!');
            window.location.href = 'index.php?act=admin_category_list';
        } else {
            alert('Lỗi: ' + res.message);
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;
        }
    } catch (error) {
        console.error("Lỗi Update:", error);
        alert('Mất kết nối đến máy chủ! Vui lòng thử lại.');
        submitBtn.innerHTML = originalBtnText;
        submitBtn.disabled = false;
    }
}
</script>