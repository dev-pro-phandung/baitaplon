<!-- <div class="card">
    <div class="header-action" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0;"><i class="fas fa-list"></i> Danh sách Danh mục</h3>
        
        <a href="index.php?act=admin_add_category" class="btn-primary"><i class="fas fa-plus"></i> Thêm mới</a>
    </div>

    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #f8f9fa; border-bottom: 2px solid #ddd;">
                <th style="padding:10px;">ID</th>
                <th style="padding:10px;">Tên danh mục</th>
                <th style="padding:10px; text-align: center;">Icon/Ảnh</th>
                <th style="padding:10px; text-align: center;">Danh mục cha</th>
                <th style="padding:10px; text-align: center;">Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data['categories'] as $cat): ?>
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding:10px;">#<?php echo $cat['id']; ?></td>
                <td style="padding:10px; font-weight: bold; color: #333;"><?php echo $cat['name']; ?></td>
                <td style="padding:10px; text-align: center;">
                    <?php if(!empty($cat['image'])): ?>
                        <img src="img/<?php echo $cat['image']; ?>" style="height: 40px; border-radius: 4px;">
                    <?php else: ?>
                        <span style="color:#999; font-size:12px;">Không có ảnh</span>
                    <?php endif; ?>
                </td>
                <td style="padding:10px; text-align: center;">
                    <?php 
                        if($cat['parent_id'] == 0) echo '<span style="color:#28a745; font-weight:bold;">Gốc</span>';
                        else echo 'ID: ' . $cat['parent_id']; 
                    ?>
                </td>
                <td style="padding:10px; text-align: center;">
                    <a href="index.php?act=admin_category_edit&id=<?php echo $cat['id']; ?>" class="btn-action" style="background:#ffc107; color:black; padding:5px 10px; border-radius:3px; text-decoration:none;">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="index.php?act=admin_category_delete&id=<?php echo $cat['id']; ?>" onclick="return confirm('Xóa danh mục này?')" class="btn-action" style="background:#dc3545; color:white; padding:5px 10px; border-radius:3px; text-decoration:none;">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div> -->