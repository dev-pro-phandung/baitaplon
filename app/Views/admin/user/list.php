<div class="card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
    <div class="header-action" style="margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 15px;">
        <h3 style="margin: 0; color: #333;"><i class="fas fa-users" style="color: #007bff;"></i> Danh sách Khách hàng</h3>
    </div>

    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #f8f9fa; border-bottom: 2px solid #ddd;">
                <th style="padding:12px; text-align: left;">ID</th>
                <th style="padding:12px; text-align: left;">Họ và tên</th>
                <th style="padding:12px; text-align: left;">Email</th>
                <th style="padding:12px; text-align: left;">Số điện thoại</th>
                <th style="padding:12px; text-align: left;">Địa chỉ</th>
                <th style="padding:12px; text-align: center;">Hành động</th>
            </tr>
        </thead>
        <tbody id="api-user-list">
            <tr>
                <td colspan="6" style="padding:30px; text-align:center; color:#666;">
                    <i class="fas fa-spinner fa-spin"></i> Đang tải dữ liệu khách hàng...
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    loadUsers();
});

// 1. Hàm gọi API lấy danh sách Khách hàng
async function loadUsers() {
    const tbody = document.getElementById('api-user-list');
    try {
        // Giả định API của bạn là api_users (Bạn cần có hàm này trong ApiUserController)
        const response = await fetch('index.php?act=api_users');
        const res = await response.json();

        if (res.status === 200) {
            let users = res.data.users;
            let html = '';

            if (users.length === 0) {
                html = `<tr><td colspan="6" style="padding:30px; text-align:center; color:#666;">Chưa có khách hàng nào đăng ký.</td></tr>`;
            } else {
                users.forEach(u => {
                    html += `
                    <tr style="border-bottom: 1px solid #eee; transition: background 0.3s;" onmouseover="this.style.background='#f9f9f9'" onmouseout="this.style.background='white'">
                        <td style="padding:12px;">#${u.id}</td>
                        <td style="padding:12px;"><strong>${u.fullname || 'Chưa cập nhật'}</strong></td>
                        <td style="padding:12px; color: #666;">${u.email || 'Chưa cập nhật'}</td>
                        <td style="padding:12px;">${u.phone || 'Chưa cập nhật'}</td>
                        <td style="padding:12px;">${u.address || 'Chưa cập nhật'}</td>
                        <td style="padding:12px; text-align: center;">
                            <button onclick="deleteUser(${u.id})" 
                               title="Xóa Khách Hàng" 
                               style="background:#dc3545; color:white; padding:6px 12px; border-radius:4px; border:none; cursor:pointer; font-weight: bold;">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </td>
                    </tr>`;
                });
            }
            tbody.innerHTML = html;
        } else {
            tbody.innerHTML = `<tr><td colspan="6" style="padding:30px; text-align:center; color:red;">Lỗi: ${res.message}</td></tr>`;
        }
    } catch (error) {
        console.error("Lỗi tải danh sách:", error);
        tbody.innerHTML = `<tr><td colspan="6" style="padding:30px; text-align:center; color:red;">Mất kết nối đến máy chủ!</td></tr>`;
    }
}

// 2. Hàm gọi API Xóa Khách hàng (Chuẩn DELETE)
async function deleteUser(id) {
    if (confirm('CẢNH BÁO: Bạn có chắc muốn xóa khách hàng này? \nMọi đơn hàng liên quan của họ cũng có thể bị ảnh hưởng!')) {
        try {
            const response = await fetch(`index.php?act=api_user_delete&id=${id}`, {
                method: 'DELETE' // Dùng đúng chuẩn API
            });

            // Chống lỗi ngầm (Foreign Key trong Database nếu user đã mua hàng)
            const rawText = await response.text();
            let res;
            try {
                res = JSON.parse(rawText);
            } catch (err) {
                console.error("Lỗi Server:", rawText);
                alert("Không thể xóa khách hàng này! Rất có thể họ đã có dữ liệu trong bảng Đơn hàng (Khóa ngoại).");
                return;
            }

            if (res.status === 200) {
                alert('🎉 Đã xóa khách hàng thành công!');
                loadUsers(); // Load lại bảng ngay lập tức
            } else {
                alert('Lỗi: ' + res.message);
            }
        } catch (error) {
            console.error("Lỗi xóa:", error);
            alert('Mất kết nối đến máy chủ khi xóa!');
        }
    }
}
</script>