<style>
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block; 
        margin-bottom: 8px; 
        font-weight: bold;
        color: #333;
    }
    .form-group input[type="text"], 
    .form-group select, 
    .form-group input[type="file"] {
        width: 100%; 
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box; 
    }
    .form-group p.note {
        margin-top: 8px;
        font-size: 13px;
        color: #666;
        line-height: 1.5;
    }
</style>

<div class="card">
    <div class="header-action" style="margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 15px;">
        <h3 style="margin: 0;"><i class="fas fa-plus"></i> Thêm Danh mục mới</h3>
    </div>

    <form id="addCategoryForm" onsubmit="submitCategoryForm(event)">
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
            
            <div>
                <div class="form-group">
                    <label>Tên danh mục (*)</label>
                    <input type="text" name="name" placeholder="Ví dụ: Rau củ, Thịt trứng..." required>
                </div>

                <div class="form-group">
                    <label>Danh mục Cha</label>
                    <select name="parent_id" id="parent_id">
                        <option value="0">-- Đang tải danh mục... --</option>
                    </select>
                    <p class="note">
                        - Chọn <b>"Là danh mục gốc"</b> nếu đây là danh mục lớn.<br>
                        - Chọn một danh mục khác nếu bạn muốn tạo danh mục con cho nó.
                    </p>
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label>Hình ảnh / Icon</label>
                    <input type="file" name="image">
                </div>
                
                <div style="margin-top: 10px; background: #f8f9fa; padding: 15px; border-radius: 5px; border: 1px dashed #ccc;">
                    <label style="color: #d0011b;">Lưu ý:</label>
                    <ul style="padding-left: 20px; font-size: 14px; color: #555; margin: 5px 0;">
                        <li>Tên danh mục không được để trống.</li>
                        <li>Ảnh nên dùng icon trong suốt (PNG) hoặc hình vuông nhỏ (100x100px) để hiển thị đẹp nhất.</li>
                    </ul>
                </div>
            </div>
        </div>

        <hr style="margin: 30px 0; border: 0; border-top: 1px solid #eee;">
        
        <button type="submit" id="btn-submit" class="btn-primary" style="padding: 12px 25px; font-size: 16px;">
            <i class="fas fa-save"></i> LƯU DANH MỤC
        </button>
        <a href="index.php?act=admin_category_list" style="margin-left: 15px; color: #666; text-decoration: none;">Hủy bỏ</a>
    </form>
</div>

<script src="js/submit.js?v=<?php echo time(); ?>"></script>