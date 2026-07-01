<div class="card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
    <div class="header-action" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 15px;">
        <h3 style="margin: 0; color: #333;"><i class="fas fa-file-invoice" style="color: #007bff;"></i> Đơn hàng #<span id="display_order_id">...</span></h3>
        <a href="index.php?act=admin" style="background: #6c757d; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; font-weight: bold;">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
        
        <div style="background: #f8f9fa; padding: 20px; border-radius: 5px; border: 1px solid #eee;">
            <h4 style="margin-top: 0; color: #333; border-bottom: 1px solid #ddd; padding-bottom: 10px;">Thông tin người nhận</h4>
            <p><strong>Họ tên:</strong> <span id="customer_name">...</span></p>
            <p><strong>Số điện thoại:</strong> <span id="customer_phone">...</span></p>
            <p><strong>Địa chỉ:</strong> <span id="customer_address">...</span></p>
            <p><strong>Ngày đặt:</strong> <span id="order_date">...</span></p>
            <p><strong>Ghi chú:</strong> <em style="color: #d0011b;" id="order_note">Không có ghi chú</em></p>
        </div>

        <div style="background: #e9ecef; padding: 20px; border-radius: 5px; border: 1px solid #ddd;">
            <h4 style="margin-top: 0; color: #333; border-bottom: 1px solid #ccc; padding-bottom: 10px;">Xử lý đơn hàng</h4>
            
            <form id="updateOrderForm" onsubmit="submitUpdateOrderStatus(event)">
                <input type="hidden" name="order_id" id="input_order_id">
                
                <label style="font-weight: bold; color: #555;">Trạng thái hiện tại:</label>
                <select name="status" id="order_status" style="width: 100%; padding: 10px; margin: 10px 0 20px 0; border-radius: 4px; border: 1px solid #ccc; font-size: 15px;">
                    <option value="1">Đơn mới (Chờ duyệt)</option>
                    <option value="2">Đang giao hàng</option>
                    <option value="3">Đã giao (Hoàn thành)</option>
                    <option value="4">Đã hủy</option>
                </select>
                
                <button type="submit" id="btn-update-status" style="background: #28a745; color: white; border: none; padding: 12px 20px; border-radius: 4px; font-weight: bold; cursor: pointer; width: 100%; font-size: 15px; transition: 0.3s;">
                    <i class="fas fa-save"></i> CẬP NHẬT TRẠNG THÁI
                </button>
            </form>
        </div>
    </div>

    <h4 style="margin-bottom: 15px; color: #333; border-left: 4px solid #d0011b; padding-left: 10px;">Chi tiết sản phẩm</h4>
    <table style="width: 100%; border-collapse: collapse; border: 1px solid #eee;">
        <thead>
            <tr style="background: #333; color: white;">
                <th style="padding: 12px; text-align: left;">Sản phẩm</th>
                <th style="padding: 12px; text-align: center;">Hình ảnh</th>
                <th style="padding: 12px; text-align: center;">Giá</th>
                <th style="padding: 12px; text-align: center;">Số lượng</th>
                <th style="padding: 12px; text-align: right;">Thành tiền</th>
            </tr>
        </thead>
        <tbody id="api-order-details">
            <tr>
                <td colspan="5" style="padding: 30px; text-align: center; color: #666;">
                    <i class="fas fa-spinner fa-spin"></i> Đang tải dữ liệu sản phẩm...
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr style="background: #f8f9fa;">
                <td colspan="4" style="padding: 15px; text-align: right; font-weight: bold; font-size: 16px; color: #333;">TỔNG CỘNG:</td>
                <td id="order_total" style="padding: 15px; text-align: right; font-weight: bold; font-size: 20px; color: #d0011b;">
                    ...
                </td>
            </tr>
        </tfoot>
    </table>
</div>

<script>
const formatter = new Intl.NumberFormat('vi-VN');

// 1. NGAY KHI VÀO TRANG, LẤY ID TRÊN URL VÀ LOAD DỮ LIỆU
document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    const orderId = urlParams.get('id');

    if (!orderId) {
        alert("Không tìm thấy mã đơn hàng!");
        window.location.href = 'index.php?act=admin';
        return;
    }

    // Gắn ID vào giao diện và Form ẩn
    document.getElementById('display_order_id').innerText = orderId;
    document.getElementById('input_order_id').value = orderId;

    loadAdminOrderDetail(orderId);
});

// 2. HÀM TẢI DỮ LIỆU ĐƠN HÀNG (Bạn đã vô tình xóa hàm này lúc nãy)
async function loadAdminOrderDetail(id) {
    const tbody = document.getElementById('api-order-details');
    
    try {
        const response = await fetch(`index.php?act=api_order_detail&id=${id}`);
        const res = await response.json();

        if (res.status === 200) {
            const orderInfo = res.data.order;
            const orderDetails = res.data.details;

            // Đổ thông tin người nhận
            document.getElementById('customer_name').innerText = orderInfo.fullname || 'Khách vãng lai';
            document.getElementById('customer_phone').innerText = orderInfo.phone || 'N/A';
            document.getElementById('customer_address').innerText = orderInfo.address || 'N/A';
            document.getElementById('order_date').innerText = orderInfo.created_at || 'N/A';
            
            if (orderInfo.note) {
                document.getElementById('order_note').innerText = orderInfo.note;
                document.getElementById('order_note').style.color = '#333';
            }

            // Gắn trạng thái hiện tại
            document.getElementById('order_status').value = orderInfo.status;

            // Đổ danh sách sản phẩm
            let html = '';
            let total = 0;

            if (!orderDetails || orderDetails.length === 0) {
                html = `<tr><td colspan="5" style="text-align:center; padding: 20px;">Đơn hàng trống.</td></tr>`;
            } else {
                orderDetails.forEach(item => {
                    const price = parseFloat(item.price) || 0;
                    const num = parseInt(item.num) || 0;
                    const subtotal = price * num;
                    total += subtotal;
                    
                    html += `
                        <tr style="border-bottom: 1px solid #ddd;">
                            <td style="padding: 12px;"><strong>${item.name}</strong></td>
                            <td style="padding: 12px; text-align: center;">
                                <img src="img/${item.image}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #ccc;" onerror="this.src='img/no-image.png'">
                            </td>
                            <td style="padding: 12px; text-align: center; color: #555;">${formatter.format(price)}đ</td>
                            <td style="padding: 12px; text-align: center; font-weight: bold;">${num}</td>
                            <td style="padding: 12px; text-align: right; font-weight: bold; color: #d0011b;">${formatter.format(subtotal)}đ</td>
                        </tr>
                    `;
                });
            }

            tbody.innerHTML = html;
            // Dùng tổng tiền từ Database (total_money) thay vì cộng dồn (đề phòng có mã giảm giá)
            const finalTotal = orderInfo.total_money ? parseFloat(orderInfo.total_money) : total;
            document.getElementById('order_total').innerText = formatter.format(finalTotal) + ' VNĐ';

        } else {
            alert("Lỗi: " + res.message);
            window.location.href = 'index.php?act=admin';
        }
    } catch (error) {
        console.error("Lỗi:", error);
        tbody.innerHTML = `<tr><td colspan="5" style="text-align:center; padding: 30px; color:red;">Mất kết nối đến máy chủ!</td></tr>`;
    }
}

// 3. HÀM CẬP NHẬT TRẠNG THÁI
async function submitUpdateOrderStatus(event) {
    event.preventDefault(); 
    
    const form = document.getElementById('updateOrderForm');
    const formData = new FormData(form);
    
    const submitBtn = document.getElementById('btn-update-status');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ĐANG XỬ LÝ...';
    submitBtn.disabled = true;

    try {
        const response = await fetch('index.php?act=api_order_update', {
            method: 'POST',
            body: formData
        });

        const rawText = await response.text();
        let res;
        try {
            res = JSON.parse(rawText);
        } catch (parseError) {
            console.error("Lỗi PHP văng ra:", rawText);
            alert("Lỗi Backend! Nhấn F12 -> Console để xem chi tiết.\n" + rawText.substring(0, 150));
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            return; 
        }

        if (res.status === 200) {
            alert("🎉 Đã cập nhật trạng thái đơn hàng thành công!");
            const orderId = document.getElementById('input_order_id').value;
            loadAdminOrderDetail(orderId); // Load lại dữ liệu cái rụp
        } else {
            alert("Lỗi: " + res.message);
        }
    } catch (error) {
        console.error("Lỗi Update:", error);
        alert("Mất kết nối đến máy chủ khi cập nhật!");
    } finally {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }
}
</script>