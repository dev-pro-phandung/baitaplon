<?php require_once __DIR__ . '/../inc/header.php'; ?>

<style>
    /* CSS Register Page */
    .register-page {
        padding: 60px 0;
        background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%);
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .register-card {
        background: #fff;
        width: 100%;
        max-width: 800px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .register-header {
        background: #d0011b;
        color: white;
        padding: 30px;
        text-align: center;
    }

    .register-header h2 { margin: 0; font-size: 28px; text-transform: uppercase; letter-spacing: 1px; }
    .register-header p { margin: 10px 0 0; opacity: 0.9; }

    .register-body { padding: 40px; }

    /* Grid Layout: Chia 2 cột */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-group { margin-bottom: 10px; position: relative; }
    
    /* Class này để ô input chiếm trọn 2 cột (như ô Email) */
    .form-group.full-width { grid-column: span 2; }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #444;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .input-wrapper { position: relative; }
    
    .input-wrapper i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px 12px 45px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 15px;
        transition: all 0.3s;
        background: #f9f9f9;
        outline: none;
        box-sizing: border-box; /* Quan trọng để không bị vỡ layout */
    }

    .form-control:focus {
        border-color: #d0011b;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(208, 1, 27, 0.1);
    }

    .btn-submit {
        width: 100%;
        padding: 15px;
        background: #d0011b;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.3s;
        text-transform: uppercase;
        margin-top: 20px;
    }
    .btn-submit:hover { background: #b00015; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(208, 1, 27, 0.3); }

    .login-redirect {
        text-align: center;
        margin-top: 25px;
        font-size: 14px;
        color: #666;
        grid-column: span 2;
    }
    .login-redirect a { color: #d0011b; font-weight: bold; text-decoration: none; }
    .login-redirect a:hover { text-decoration: underline; }

    /* Responsive cho mobile */
    @media (max-width: 600px) {
        .form-grid { grid-template-columns: 1fr; }
        .form-group.full-width { grid-column: span 1; }
    }
</style>

<div class="register-page">
    <div class="register-card">
        <div class="register-header">
            <h2>Đăng ký thành viên</h2>
            <p>Nhập thông tin để tạo tài khoản mới</p>
        </div>
        
        <div class="register-body">
            <form action="index.php?act=register" method="POST" class="form-grid">
                
                <div class="form-group">
                    <label>Họ và tên (*)</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" name="fullname" class="form-control" placeholder="Nguyễn Văn A" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Số điện thoại (Nhận OTP) (*)</label>
                    <div class="input-wrapper">
                        <i class="fas fa-phone"></i>
                        <input type="text" name="phone" class="form-control" placeholder="09xx..." required>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label>Email (Tùy chọn - Để khôi phục mật khẩu)</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" class="form-control" placeholder="example@gmail.com">
                    </div>
                </div>

                <div class="form-group">
                    <label>Mật khẩu (*)</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" class="form-control" placeholder="Tối thiểu 6 ký tự" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Nhập lại mật khẩu (*)</label>
                    <div class="input-wrapper">
                        <i class="fas fa-check-circle"></i>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Xác nhận lại" required>
                    </div>
                </div>

                <div style="grid-column: span 2;">
                    <button type="submit" class="btn-submit">Tiếp tục (Gửi OTP)</button>
                </div>

                <div class="login-redirect">
                    Bạn đã có tài khoản? <a href="index.php?act=login">Đăng nhập ngay</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../inc/footer.php'; ?>