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
</style>

<div class="admin-card">
    <div class="card-header">
        <i class="fas fa-image" style="color: #d0011b; font-size: 20px;"></i>
        <h3>Thêm Banner Quảng Cáo</h3>
    </div>

    <div class="card-body">
        <form id="addBannerForm" onsubmit="submitAddBannerForm(event)">
            <div class="form-row">
                <div class="form-group">
                    <label>Tiêu đề Banner (Ghi chú) <span style="color:red">*</span></label>
                    <input type="text" name="title" class="form-control" required placeholder="VD: Banner Tết 2025...">
                </div>
                <div class="form-group">
                    <label>Vị trí hiển thị:</label>
                    <select name="position" class="form-control">
                        <option value="slider">Slider Chính (Chạy ngang đầu trang)</option>
                        <option value="banner_body">Banner Giữa trang (Quảng cáo)</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Link liên kết (Khi bấm vào ảnh)</label>
                <div style="position: relative;">
                    <input type="text" name="link" class="form-control" placeholder="http://..." style="padding-left: 35px;">
                    <i class="fas fa-link" style="position: absolute; left: 12px; top: 12px; color: #999;"></i>
                </div>
            </div>

            <div class="form-group">
                <label>Hình ảnh Banner <span style="color:red">*</span></label>
                <input type="file" name="image" class="form-control" accept="image/*" required style="padding: 7px;">
                <p style="margin:5px 0 0; color:#666; font-size:12px;">Kích thước khuyến nghị: 1200x400px (JPG, PNG)</p>
            </div>

            <div class="form-actions">
                <button type="submit" id="btn-submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> LƯU BANNER
                </button>
                <a href="index.php?act=admin_banner_list" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </form>
    </div>
</div>

<script>
async function submitAddBannerForm(event) {
    event.preventDefault();
    const form = document.getElementById('addBannerForm');
    const formData = new FormData(form);
    
    const submitBtn = document.getElementById('btn-submit');
    const originalBtnText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ĐANG XỬ LÝ...';
    submitBtn.disabled = true;

    try {
        const response = await fetch('index.php?act=api_banner_add', {
            method: 'POST',
            body: formData
        });
        const res = await response.json();

        if (res.status === 201 || res.status === 200) {
            alert('🎉 Thêm banner thành công!');
            window.location.href = 'index.php?act=admin_banner_list';
        } else {
            alert('Lỗi: ' + res.message);
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;
        }
    } catch (error) {
        alert('Mất kết nối đến máy chủ! Vui lòng thử lại.');
        submitBtn.innerHTML = originalBtnText;
        submitBtn.disabled = false;
    }
}
</script>