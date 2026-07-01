<div class="card">
    <div class="header-action" style="margin-bottom: 20px; display: flex; justify-content: space-between;">
        <h3><i class="fas fa-tag"></i> Danh sách Thương hiệu</h3>
        <a href="index.php?act=admin_brand_add" class="btn-primary" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">
            <i class="fas fa-plus"></i> Thêm mới
        </a>
    </div>

    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #f8f9fa; border-bottom: 2px solid #ddd;">
                <th style="padding:10px;">ID</th>
                <th style="padding:10px;">Logo</th>
                <th style="padding:10px;">Tên Thương hiệu</th>
                <th style="padding:10px;">Mô tả</th>
                <th style="padding:10px;">Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data['brands'] as $b): ?>
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding:10px; text-align: center;">#<?php echo $b['id']; ?></td>
                <td style="padding:10px; text-align: center;">
                    <?php if($b['image']): ?>
                        <img src="../public/img/<?php echo $b['image']; ?>" style="height: 50px; object-fit: contain;">
                    <?php else: ?>
                        <span>(Không có ảnh)</span>
                    <?php endif; ?>
                </td>
                <td style="padding:10px; font-weight: bold;"><?php echo $b['name']; ?></td>
                <td style="padding:10px; color: #666;"><?php echo $b['description']; ?></td>
                <td style="padding:10px; text-align: center;">
                    <a href="index.php?act=admin_brand_edit&id=<?php echo $b['id']; ?>" style="color: blue; margin-right: 10px;"><i class="fas fa-edit"></i> Sửa</a>
                    <a href="index.php?act=admin_brand_delete&id=<?php echo $b['id']; ?>" onclick="return confirm('Xóa thương hiệu này?')" style="color: red;"><i class="fas fa-trash"></i> Xóa</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>