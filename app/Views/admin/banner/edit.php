<style>
    .admin-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        max-width: 900px;
        margin: 20px auto;
        font-family: 'Segoe UI', Arial, sans-serif;
        overflow: hidden;
    }
    .card-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 15px 25px;
        border-bottom: 1px solid #dee2e6;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .card-header h3 { margin: 0; color: #333; font-size: 18px; text-transform: uppercase; font-weight: 700; }
    .card-body { padding: 30px; }

    .form-row {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #495057; font-size: 14px; }
    
    .form-control {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        font-size: 14px;
        box-sizing: border-box;
        transition: border-color 0.2s;
    }
    .form-control:focus { border-color: #d0011b; outline: none; box-shadow: 0 0 0 3px rgba(208, 1, 27, 0.1); }

    .image-upload-box {
        display: flex;
        gap: 30px;
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border: 1px dashed #ced4da;
        align-items: center;
    }
    .current-img {
        width: 200px;
        text-align: center;
        background: #fff;
        padding: 5px;
        border-radius: 6px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .current-img img { width: 100%; height: auto; border-radius: 4px; display: block; }
    .new-img-input { flex: 1; }

    .form-actions {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #eee;
        text-align: right;
    }
    .btn {
        padding: 10px 25px;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: 0.2s;
    }
    .btn-primary { background: #d0011b; color: white; }
    .btn-primary:hover { background: #b00015; transform: translateY(-1px); }
    .btn-secondary { background: #e9ecef; color: #495057; margin-left: 10px; }
    .btn-secondary:hover { background: #dee2e6; color: #333; }

    @media (max-width: 768px) {
        .form-row, .image-upload-box { display: block; }
        .current-img { margin: 0 auto 15px auto; }
    }
</style>

<div class="admin-card">
    <div class="card-header">
        <i class="fas fa-image" style="color: #d0011b; font-size: 20px;"></i>
        <h3>Chỉnh sửa Banner</h3>
    </div>

    <div class="card-body">
        <form id="editBannerForm" onsubmit="submitEditBannerForm(event)">
            <input type="hidden" name="id" id="banner_id">

            <div class="form-row">
                <div class="form-group">
                    <label>Tiêu đề Banner <span style="color:red">*</span></label>
                    <input type="text" name="title" id="title" class="form-control" required placeholder="Nhập tiêu đề banner...">
                </div>
                <div class="form-group">
                    <label>Vị trí hiển thị:</label>
                    <select name="position" id="position" class="form-control">
                        <option value="slider">Slider Chính (Chạy ngang đầu trang)</option>
                        <option value="banner_body">Banner Giữa trang (Quảng cáo)</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Link liên kết (Khi bấm vào ảnh)</label>
                <div style="position: relative;">
                    <input type="text" name="link" id="link" class="form-control" placeholder="http://..." style="padding-left: 35px;">
                    <i class="fas fa-link" style="position: absolute; left: 12px; top: 12px; color: #999;"></i>
                </div>
            </div>

            <div class="form-group">
                <label>Hình ảnh Banner</label>
                <div class="image-upload-box">
                    <div class="current-img">
                        <span style="font-size:12px; color:#666; display:block; margin-bottom:5px;">Hiện tại:</span>
                        <img id="preview_img" src="" style="display: none;" alt="Banner Image">
                        <span id="no_img_text" style="color:#999; font-size: 13px; font-style: italic;">Đang tải ảnh...</span>
                    </div>

                    <div class="new-img-input">
                        <label for="banner_img" style="cursor:pointer; font-weight:bold; color:#d0011b;">
                            <i class="fas fa-cloud-upload-alt"></i> Chọn ảnh khác thay thế
                        </label>
                        <input type="file" name="image" id="banner_img" class="form-control" accept="image/*" style="margin-top:5px; padding: 6px;">
                        <p style="margin:5px 0 0; color:#666; font-size:12px;">Kích thước khuyến nghị: 1200x400px (JPG, PNG)</p>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" id="btn-submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> LƯU CẬP NHẬT
                </button>
                <a href="index.php?act=admin_banner_list" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    loadBannerData();
});

// 1. Lấy dữ liệu API đổ vào Form
async function loadBannerData() {
    const bannerId = new URLSearchParams(window.location.search).get('id');
    if (!bannerId) {
        alert("ID banner không hợp lệ!");
        window.location.href = 'index.php?act=admin_banner_list';
        return;
    }

    try {
        const response = await fetch(`index.php?act=api_banner_detail&id=${bannerId}`);
        const res = await response.json();

        if (res.status === 200) {
            const banner = res.data.banner;
            
            // Lắp dữ liệu vào Input
            document.getElementById('banner_id').value = banner.id;
            document.getElementById('title').value = banner.title || '';
            document.getElementById('position').value = banner.position || 'slider';
            document.getElementById('link').value = banner.link || '';
            
            // Xử lý hiển thị ảnh
            const previewImg = document.getElementById('preview_img');
            const noImgText = document.getElementById('no_img_text');
            if (banner.image) {
                previewImg.src = 'img/' + banner.image;
                previewImg.style.display = 'block';
                noImgText.style.display = 'none';
            } else {
                noImgText.innerText = "(Không có ảnh)";
            }
        } else {
            alert("Banner này không tồn tại!");
            window.location.href = 'index.php?act=admin_banner_list';
        }
    } catch (error) {
        console.error('Lỗi tải dữ liệu:', error);
        alert('Mất kết nối máy chủ!');
    }
}

// 2. Gửi Form Sửa lên API
async function submitEditBannerForm(event) {
    event.preventDefault(); 
    const form = document.getElementById('editBannerForm');
    const formData = new FormData(form);

    const submitBtn = document.getElementById('btn-submit');
    const originalBtnText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ĐANG XỬ LÝ...';
    submitBtn.disabled = true;

    try {
        const response = await fetch('index.php?act=api_banner_update', {
            method: 'POST',
            body: formData
        });
        const res = await response.json();

        if (res.status === 200) {
            alert('🎉 Cập nhật banner thành công!');
            window.location.href = 'index.php?act=admin_banner_list';
        } else {
            alert('Lỗi: ' + res.message);
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;
        }
    } catch (error) {
        alert('Mất kết nối đến máy chủ!');
        submitBtn.innerHTML = originalBtnText;
        submitBtn.disabled = false;
    }
}
</script>