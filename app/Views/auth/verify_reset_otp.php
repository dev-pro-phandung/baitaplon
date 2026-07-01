<style>
    /* ================= CSS GIAO DIỆN FORM (ĐỒNG BỘ) ================= */
    body {
        background-color: #f0f2f5;
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
    }
    
    .login-wrapper {
        min-height: 85vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .login-container {
        background: #ffffff;
        width: 100%;
        max-width: 500px;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .login-container h2 {
        text-align: center;
        color: #d0011b;
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 25px;
        text-transform: uppercase;
        border-bottom: 2px solid #f5f5f5;
        padding-bottom: 15px;
    }

    /* Khung thông báo email */
    .notify-box {
        background: #e3f2fd;
        color: #0d47a1;
        padding: 15px;
        border-radius: 8px;
        font-size: 14px;
        margin-bottom: 25px;
        border: 1px solid #bbdefb;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .notify-box i { font-size: 18px; }

    /* Form Group */
    .form-group { margin-bottom: 20px; position: relative; }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #444;
        font-size: 14px;
    }

    /* Input có icon bên trong */
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
        padding: 12px 15px 12px 45px; /* Padding trái lớn để né icon */
        font-size: 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        transition: all 0.3s;
        outline: none;
    }

    .form-control:focus {
        border-color: #d0011b;
        box-shadow: 0 0 0 4px rgba(208, 1, 27, 0.1);
    }

    /* Nút bấm */
    .btn-submit {
        width: 100%;
        padding: 14px;
        background: linear-gradient(to right, #d0011b, #b00117);
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
        margin-top: 10px;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(208, 1, 27, 0.3);
    }

    .otp-input {
        letter-spacing: 5px;
        font-weight: bold;
        font-size: 18px;
        text-align: center;
        padding-left: 15px !important; /* OTP căn giữa nên không cần né icon */
    }
</style>

<div class="login-wrapper">
    <div class="login-container">
        <h2><i class="fas fa-lock-open"></i> Đặt lại mật khẩu</h2>
        
        <div class="notify-box">
            <i class="fas fa-envelope-open-text"></i>
            <div>
                Mã OTP xác thực đã được gửi tới:<br>
                <b><?php echo isset($_SESSION['reset_email']) ? $_SESSION['reset_email'] : '...'; ?></b>
            </div>
        </div>
        
        <form action="index.php?act=reset_password" method="POST">
            
            <div class="form-group">
                <label>Nhập mã OTP (6 số):</label>
                <div class="input-wrapper">
                    <input type="text" name="otp" class="form-control otp-input" required placeholder="XXXXXX" maxlength="6" autocomplete="off">
                </div>
            </div>
            
            <div class="form-group">
                <label>Mật khẩu mới:</label>
                <div class="input-wrapper">
                    <i class="fas fa-key"></i>
                    <input type="password" name="new_password" class="form-control" required placeholder="Nhập mật khẩu mới...">
                </div>
            </div>
            
            <div class="form-group">
                <label>Xác nhận mật khẩu:</label>
                <div class="input-wrapper">
                    <i class="fas fa-check-circle"></i>
                    <input type="password" name="confirm_password" class="form-control" required placeholder="Nhập lại mật khẩu trên...">
                </div>
            </div>
            
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> CẬP NHẬT MẬT KHẨU
            </button>
        </form>
    </div>
</div>