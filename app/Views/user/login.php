<?php require_once __DIR__ . '/../inc/header.php'; ?>

<style>
    /* CSS Login giữ nguyên style bạn thích */
    .login-page {
        padding: 60px 0;
        background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%);
        min-height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-card {
        background: #fff;
        width: 100%;
        max-width: 450px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
        padding: 40px;
        position: relative;
    }

    .login-header { text-align: center; margin-bottom: 30px; }
    .login-header h2 { font-size: 26px; color: #333; text-transform: uppercase; margin-bottom: 10px; }
    .login-header p { color: #666; font-size: 14px; }

    .form-group { margin-bottom: 20px; position: relative; }
    .form-group label { display: block; font-weight: 600; color: #444; margin-bottom: 8px; font-size: 14px; }

    .input-wrapper { position: relative; }
    
    /* Icon bên trái */
    .input-wrapper > i:not(.toggle-password) {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        z-index: 1;
    }

    /* Icon mắt bên phải */
    .toggle-password {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #999;
        z-index: 10;
    }
    .toggle-password:hover { color: #d0011b; }

    .form-control {
        width: 100%;
        padding: 12px 40px 12px 45px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 15px;
        outline: none;
        transition: all 0.3s;
        background: #f9f9f9;
    }
    .form-control:focus { border-color: #333; background: #fff; }

    .login-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13px;
        margin-bottom: 25px;
    }
    .remember-me { display: flex; align-items: center; gap: 5px; cursor: pointer; color: #555; }
    .remember-me input { width: 16px; height: 16px; accent-color: #d0011b; cursor: pointer; }
    .forgot-link { color: #d0011b; font-weight: bold; text-decoration: none; }

    .btn-submit {
        width: 100%;
        padding: 14px;
        background: #333;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
        text-transform: uppercase;
    }
    .btn-submit:hover { background: #000; transform: translateY(-2px); }

    .register-link { text-align: center; margin-top: 25px; font-size: 14px; color: #666; }
    .register-link a { color: #d0011b; font-weight: bold; text-decoration: none; }
</style>

<div class="login-page">
    <div class="login-card">
        <div class="login-header">
            <h2>Đăng nhập</h2>
            <p>Chào mừng bạn quay trở lại!</p>
        </div>

        <form action="index.php?act=login" method="POST">
            
            <div class="form-group">
                <label>Số điện thoại</label>
                <div class="input-wrapper">
                    <i class="fas fa-phone"></i>
                    <input type="text" name="phone" class="form-control" placeholder="Nhập số điện thoại..." required>
                </div>
            </div>

            <div class="form-group">
                <label>Mật khẩu</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" id="passwordInput" class="form-control" placeholder="Nhập mật khẩu..." required>
                    <i class="fas fa-eye toggle-password" onclick="togglePassword()"></i>
                </div>
            </div>

            <div class="login-options">
                <label class="remember-me">
                    <input type="checkbox" name="remember"> Ghi nhớ đăng nhập
                </label>
                <a href="index.php?act=forgot_password" class="forgot-link" style="color: #d0011b; font-size: 13px; text-decoration: none;">Quên mật khẩu?</a>
            </div>

            <button type="submit" class="btn-submit">Đăng nhập ngay</button>

            <div class="register-link">
                Bạn chưa có tài khoản? <a href="index.php?act=register">Đăng ký mới</a>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePassword() {
        var input = document.getElementById("passwordInput");
        var icon = document.querySelector(".toggle-password");
        
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }
</script>

<?php require_once __DIR__ . '/../inc/footer.php'; ?>