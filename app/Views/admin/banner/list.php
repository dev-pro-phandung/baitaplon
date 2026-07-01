<div class="card">
    <div class="header-action" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
        <h3 style="margin: 0;"><i class="fas fa-images"></i> Danh sách Banner</h3>
        
        <div style="display: flex; gap: 10px; align-items: center;">
            <form id="searchForm" onsubmit="handleSearch(event)" style="display: flex; gap: 5px;">
                <input type="text" id="searchInput" placeholder="Tìm tiêu đề banner..." 
                       style="padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; width: 200px;">
                
                <button type="submit" style="background: #333; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer;">
                    <i class="fas fa-search"></i>
                </button>

                <button type="button" id="clearSearchBtn" onclick="clearSearch()" title="Xóa bộ lọc" 
                       style="display: none; background: #ccc; color: #333; padding: 8px 12px; border-radius: 4px; border: none; cursor: pointer; align-items: center;">
                    <i class="fas fa-times"></i>
                </button>
            </form>

            <a href="index.php?act=admin_add_banner" class="btn-primary" 
               style="background: #007bff; color: white; padding: 8px 15px; border-radius: 4px; text-decoration: none; display: inline-flex; align-items: center; gap: 5px;">
                <i class="fas fa-plus"></i> Thêm mới
            </a>
        </div>
    </div>

    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #f8f9fa; border-bottom: 2px solid #ddd;">
                <th style="padding:10px; text-align: center; width: 60px;">ID</th>
                <th style="padding:10px; text-align: center; width: 150px;">Hình ảnh</th>
                <th style="padding:10px; text-align: left;">Tiêu đề</th>
                <th style="padding:10px; text-align: left;">Link trỏ đến</th>
                <th style="padding:10px; text-align: center; width: 100px;">Vị trí</th>
                <th style="padding:10px; text-align: center; width: 120px;">Hành động</th>
            </tr>
        </thead>
        <tbody id="api-banner-list">
            <tr>
                <td colspan="6" style="padding:30px; text-align:center; color: #666;">
                    Đang tải dữ liệu banner...
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script src="js/list_banner_api.js?v=<?php echo time(); ?>"></script>