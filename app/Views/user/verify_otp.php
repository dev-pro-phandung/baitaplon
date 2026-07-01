<?php require_once __DIR__ . '/../inc/header.php'; ?>

<style>
    .otp-page {
        padding: 60px 0;
        background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%);
        min-height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .otp-card {
        background: #fff;
        width: 100%;
        max-width: 450px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        padding: 40px;
        text-align: center;
    }

    .otp-header h3 { color: #333; margin-bottom: 10px; text-transform: uppercase; }
    .otp-header p { color: #666; font-size: 14px; margin-bottom: 30px; }

    .otp-input {
        width: 100%;
        padding: 15px;
        font-size: 24px;
        letter-spacing: 10px;
        text-align: center;
        border: 2px solid #ddd;
        border-radius: 8px;
        margin-bottom: 25px;
        outline: none;
        transition: 0.3s;
    }
    .otp-input:focus { border-color: #d0011b; }

    .btn-verify {
        width: 100%;
        padding: 14px;
        background: #28a745; /* Màu xanh lá */
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        text-transform: uppercase;
        transition: 0.3s;
    }
    .btn-verify:hover { background: #218838; transform: translateY(-2px); }

    .resend-link { margin-top: 20px; font-size: 14px; color: #666; }
    .resend-link a { color: #d0011b; font-weight: bold; text-decoration: none; }
</style>

<div class="otp-page">
    <div class="otp-card">
        <div class="otp-header">
            <h3>Xác thực OTP</h3>
            <p>Mã xác thực 6 số đã được gửi đến:<br>
                <strong style="color: #d0011b; font-size: 16px;">
                    <?php echo isset($_SESSION['temp_register']['phone']) ? $_SESSION['temp_register']['phone'] : '...'; ?>
                </strong>
            </p>
        </div>
        
        <form action="index.php?act=check_otp" method="POST">
            <input type="text" name="otp" class="otp-input" placeholder="000000" maxlength="6" required autofocus>
            
            <button type="submit" class="btn-verify">Xác nhận</button>
        </form>
        
        <div class="resend-link">
            Chưa nhận được mã? <a href="index.php?act=register">Gửi lại</a> hoặc <a href="index.php?act=register" style="color: #555;">Đăng ký lại</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../inc/footer.php'; ?>