<style>
    /* CSS RIÊNG CHO TRANG LOGIN/FORGOT PASSWORD */
    body {
        background-color: #f0f2f5; /* Màu nền xám nhẹ cho toàn trang */
    }
    
    .login-wrapper {
        min-height: 80vh; /* Chiếm tối thiểu 80% chiều cao màn hình */
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .login-container {
        background: #ffffff;
        width: 100%;
        max-width: 450px; /* Giới hạn chiều rộng */
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.12); /* Đổ bóng mềm mại */
        text-align: left;
    }

    .login-container h2 {
        text-align: center;
        color: #d0011b; /* Màu đỏ thương hiệu */
        font-size: 26px;
        font-weight: 700;
        margin-bottom: 10px;
        text-transform: uppercase;
    }
    
    .login-desc {
        text-align: center;
        color: #666;
        font-size: 14px;
        margin-bottom: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
        font-size: 14px;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        font-size: 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        transition: all 0.3s;
        outline: none;
    }

    .form-control:focus {
        border-color: #d0011b;
        box-shadow: 0 0 0 3px rgba(208, 1, 27, 0.1);
    }

    .btn-submit {
        width: 100%;
        padding: 12px;
        background: #d0011b;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.3s;
        margin-top: 10px;
    }

    .btn-submit:hover {
        background: #a80015; /* Màu đỏ đậm hơn khi hover */
    }

    .back-link {
        display: block;
        text-align: center;
        margin-top: 20px;
        color: #555;
        font-size: 14px;
        text-decoration: none;
        transition: 0.2s;
    }

    .back-link:hover {
        color: #d0011b;
        text-decoration: underline;
    }
    
    .back-link i {
        margin-right: 5px;
    }
</style>

<div class="login-wrapper">
    <div class="login-container">
        <h2>Quên mật khẩu?</h2>
        <p class="login-desc">Đừng lo lắng! Hãy nhập email bạn đã đăng ký, chúng tôi sẽ gửi mã OTP để đặt lại mật khẩu.</p>
        
        <form action="index.php?act=send_reset_otp" method="POST">
            <div class="form-group">
                <label for="email">Email đăng ký:</label>
                <input type="email" id="email" name="email" class="form-control" required placeholder="Ví dụ: dung@gmail.com...">
            </div>
            
            <button type="submit" class="btn-submit">
                <i class="fas fa-paper-plane"></i> Gửi mã OTP
            </button>
        </form>

        <a href="index.php?act=login" class="back-link">
            <i class="fas fa-arrow-left"></i> Quay lại trang Đăng nhập
        </a>
    </div>
</div>