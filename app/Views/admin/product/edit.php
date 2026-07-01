<style>
    /* CSS FORM ADMIN */
    .form-card {
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .form-title {
        font-size: 20px;
        font-weight: bold;
        color: #333;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    .form-row {
        display: flex;
        gap: 30px;
        margin-bottom: 20px;
    }

    .form-group {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-weight: 600;
        margin-bottom: 8px;
        color: #555;
        font-size: 14px;
    }

    .form-control {
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        transition: 0.3s;
        outline: none;
    }

    .form-control:focus {
        border-color: #d0011b;
        box-shadow: 0 0 5px rgba(208, 1, 27, 0.2);
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 10px;
        height: 42px;
    }
    .checkbox-group input { width: 18px; height: 18px; cursor: pointer; }
    .checkbox-group label { margin: 0; cursor: pointer; font-weight: normal; }

    .current-img-box {
        margin-bottom: 10px;
        padding: 5px;
        border: 1px solid #eee;
        border-radius: 4px;
        display: inline-block;
    }
    .current-img-box img { height: 80px; display: block; object-fit: cover; }

    .btn-group {
        margin-top: 30px;
        display: flex;
        gap: 15px;
    }
    .btn-save {
        background: #007bff;
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 4px;
        font-weight: bold;
        cursor: pointer;
        font-size: 16px;
    }
    .btn-save:hover { background: #0056b3; }
    .btn-cancel {
        background: #6c757d;
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 4px;
        font-weight: bold;
        cursor: pointer;
        text-decoration: none;
        font-size: 16px;
        display: inline-block;
    }
    .btn-cancel:hover { background: #5a6268; }
</style>

<div class="form-card">
    <h3 class="form-title"><i class="fas fa-edit"></i> CẬP NHẬT SẢN PHẨM</h3>

    <form id="editProductForm" onsubmit="submitEditForm(event)">
        <input type="hidden" name="id" id="product_id">

        <div class="form-group" style="margin-bottom: 20px;">
            <label>Tên sản phẩm <span style="color:red">*</span></label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Danh mục <span style="color:red">*</span></label>
                <select name="category_id" id="category_id" class="form-control" required>
                    <option value="">-- Đang tải danh mục... --</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Nhà sản xuất (Thương hiệu)</label>
                <select name="brand_id" id="brand_id" class="form-control">
                    <option value="0">-- Chọn Thương hiệu --</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Giá bán (VNĐ) <span style="color:red">*</span></label>
                <input type="number" name="price" id="price" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Giá gốc (để gạch ngang)</label>
                <input type="number" name="old_price" id="old_price" class="form-control">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Giảm giá (%)</label>
                <input type="number" name="discount" id="discount" class="form-control">
            </div>
            <div class="form-group">
                <label>Đơn vị tính <span style="color:red">*</span></label>
                <input type="text" name="unit" id="unit" class="form-control" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Hình ảnh sản phẩm</label>
                <div class="current-img-box" id="preview_box" style="display: none;">
                    <img id="preview_img" src="" alt="Ảnh hiện tại">
                </div>
                <input type="file" name="image" class="form-control" style="padding: 7px;">
                <small style="color: #888; margin-top: 5px;">* Để trống nếu không muốn đổi ảnh</small>
            </div>
            <div class="form-group">
                <label>Mô tả chi tiết sản phẩm:</label>
                <textarea name="description" id="description" class="form-control" rows="5"></textarea>
            </div>

            <div class="form-group">
                <label>Số lượng trong kho <span style="color:red">*</span></label>
                <input type="number" name="quantity" id="quantity" class="form-control" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Lượt xem (Giả lập)</label>
                <input type="number" name="views" id="views" class="form-control">
            </div>
            <div class="form-group">
                <label>Tùy chọn hiển thị</label>
                <div class="checkbox-group">
                    <input type="checkbox" name="is_new" id="is_new" value="1">
                    <label for="is_new">Đánh dấu là hàng <b>Mới về</b></label>
                </div>
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn-save"><i class="fas fa-save"></i> LƯU CẬP NHẬT</button>
            <a href="index.php?act=admin_product_list" class="btn-cancel">Hủy bỏ</a>
        </div>
    </form>
</div>

<script src="js/edit.js?v=<?php echo time(); ?>"></script>