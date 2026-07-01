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

    /* Grid Layout */
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
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
    .form-control:focus { border-color: #007bff; outline: none; box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1); }
    textarea.form-control { resize: vertical; min-height: 100px; font-family: inherit; }

    /* Khu vực ảnh */
    .image-preview-box {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border: 1px dashed #ced4da;
        text-align: center;
        height: 100%; 
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: #999;
    }
    
    /* Nút bấm */
    .form-actions {
        margin-top: 20px;
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
        font-size: 14px;
    }
    .btn-primary { background: #007bff; color: white; }
    .btn-primary:hover { background: #0056b3; transform: translateY(-1px); }
    
    .btn-secondary { background: #e9ecef; color: #495057; margin-left: 10px; }
    .btn-secondary:hover { background: #dee2e6; color: #333; }

    @media (max-width: 768px) {
        .form-row { grid-template-columns: 1fr; }
    }
</style>

<div class="admin-card">
    <div class="card-header">
        <i class="fas fa-plus-circle" style="color: #007bff; font-size: 20px;"></i>
        <h3>Thêm Thương hiệu mới</h3>
    </div>

    <div class="card-body">
        <form id="addBrandForm" onsubmit="submitAddBrandForm(event)">

            <div class="form-row">
                <div class="left-col">
                    <div class="form-group">
                        <label>Tên Thương hiệu <span style="color:red">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="Nhập tên thương hiệu...">
                    </div>

                    <div class="form-group">
                        <label>Chọn Logo đại diện</label>
                        <input type="file" name="image" class="form-control" accept="image/*" style="padding: 6px;">
                        <small style="color:#888; display:block; margin-top:5px;">Định dạng: JPG, PNG, JPEG</small>
                    </div>
                </div>

                <div class="right-col">
                    <label style="display:block; margin-bottom:8px; font-weight:600; color:#495057; font-size:14px;">Khung hiển thị</label>
                    <div class="image-preview-box">
                        <i class="fas fa-image" style="font-size: 40px; margin-bottom: 10px; opacity: 0.5;"></i>
                        <span style="font-style:italic; font-size: 13px;">Logo sẽ được hiển thị tại đây</span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Mô tả thương hiệu</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Nhập mô tả ngắn về thương hiệu..."></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" id="btn-submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> LƯU THƯƠNG HIỆU
                </button>
                <a href="index.php?act=admin_brand_list" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Hủy bỏ
                </a>
            </div>
        </form>
    </div>
</div>

<script>
async function submitAddBrandForm(event) {
    event.preventDefault(); // Chặn tải lại trang

    const form = document.getElementById('addBrandForm');
    const formData = new FormData(form);

    // Đổi trạng thái nút bấm
    const submitBtn = document.getElementById('btn-submit');
    const originalBtnText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ĐANG XỬ LÝ...';
    submitBtn.disabled = true;

    try {
        // Gọi API Thêm thương hiệu
        const response = await fetch('index.php?act=api_brand_add', {
            method: 'POST',
            body: formData
        });

        const res = await response.json();

        if (res.status === 201 || res.status === 200) {
            alert('🎉 Thêm thương hiệu thành công!');
            // Chuyển về trang danh sách thương hiệu
            window.location.href = 'index.php?act=admin_brand_list';
        } else {
            alert('Lỗi: ' + res.message);
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;
        }
    } catch (error) {
        console.error('Lỗi kết nối:', error);
        alert('Mất kết nối đến máy chủ! Vui lòng thử lại.');
        submitBtn.innerHTML = originalBtnText;
        submitBtn.disabled = false;
    }
}
</script>