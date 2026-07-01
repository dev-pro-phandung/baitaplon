<?php
require_once __DIR__ . '/../Models/UserModel.php';

// =======================================================================
// CẤU HÌNH GỬI MAIL (Kiểm tra xem file của bạn nằm ở đâu)
// =======================================================================
if (file_exists(__DIR__ . '/../Libraries/MailService.php')) {
    require_once __DIR__ . '/../Libraries/MailService.php';
} elseif (file_exists(__DIR__ . '/../Services/MailService.php')) {
    require_once __DIR__ . '/../Services/MailService.php';
}

class AuthController {

    // ==============================================================
    // 1. ĐĂNG NHẬP
    // ==============================================================
    public function login() {
        // Nếu đã đăng nhập thì chuyển về trang chủ
        if (isset($_SESSION['user'])) {
            header("Location: index.php");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $phone = $_POST['phone'];
            $password = $_POST['password'];

            $userModel = new UserModel();
            $user = $userModel->login($phone, $password);

            if ($user) {
                // Lưu thông tin người dùng vào Session
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'fullname' => $user['fullname'],
                    'phone' => $user['phone'],
                    'email' => $user['email'], 
                    'role' => $user['role']
                ];
                session_write_close();
                
                // Xử lý "Ghi nhớ đăng nhập"
                if (isset($_POST['remember'])) {
                    setcookie('remember_user', $user['id'], time() + (86400 * 30), "/"); 
                }

                // Chuyển hướng dựa trên quyền hạn
                if ($user['role'] == 1) {
                    header("Location: index.php?act=admin");
                } else {
                    header("Location: index.php");
                }
                exit;
            } else {
                echo "<script>alert('Sai số điện thoại hoặc mật khẩu!'); history.back();</script>";
            }
        }
        require_once __DIR__ . '/../Views/user/login.php';
    }

    // ==============================================================
    // 2. ĐĂNG XUẤT
    // ==============================================================
    public function logout() {
        unset($_SESSION['user']);
        // Xóa cookie ghi nhớ nếu có
        if (isset($_COOKIE['remember_user'])) {
            setcookie('remember_user', '', time() - 3600, "/");
        }
        header("Location: index.php");
        exit;
    }

    // ==============================================================
    // 3. ĐĂNG KÝ (BƯỚC 1: NHẬP THÔNG TIN)
    // ==============================================================
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $fullname = $_POST['fullname'];
            $phone = $_POST['phone'];
            $email = !empty($_POST['email']) ? $_POST['email'] : null;
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            if ($password != $confirm_password) {
                echo "<script>alert('Mật khẩu xác nhận không khớp!'); history.back();</script>";
                exit;
            }

            $userModel = new UserModel();
            if ($userModel->checkPhoneExists($phone)) {
                echo "<script>alert('Số điện thoại này đã được đăng ký!'); history.back();</script>";
                exit;
            }
            
            // Tạo mã OTP ngẫu nhiên
            $otp = rand(100000, 999999); 

            // Lưu tạm thông tin vào Session
            $_SESSION['temp_register'] = [
                'fullname' => $fullname,
                'phone' => $phone,
                'email' => $email,
                'password' => $password,
                'otp_code' => $otp,
                'otp_time' => time()
            ];

            // Thông báo mã OTP (Đăng ký thường dùng SĐT nên alert cho nhanh)
            echo "<script>
                alert('Mã OTP xác thực đăng ký của bạn là: $otp');
                window.location.href = 'index.php?act=verify_otp';
            </script>";
            exit;
        }
        require_once __DIR__ . '/../Views/user/register.php';
    }

    // ==============================================================
    // 4. ĐĂNG KÝ (BƯỚC 2: NHẬP MÃ OTP)
    // ==============================================================
    public function verifyOtpForm() {
        if (!isset($_SESSION['temp_register'])) {
            header("Location: index.php?act=register");
            exit;
        }
        require_once __DIR__ . '/../Views/user/verify_otp.php';
    }

    // ==============================================================
    // 5. ĐĂNG KÝ (BƯỚC 3: XỬ LÝ & LƯU TÀI KHOẢN)
    // ==============================================================
    public function verifyOtp() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $input_otp = $_POST['otp'];
            $tempData = isset($_SESSION['temp_register']) ? $_SESSION['temp_register'] : null;

            if (!$tempData) {
                header("Location: index.php?act=register");
                exit;
            }

            if ($input_otp == $tempData['otp_code']) {
                // Kiểm tra hết hạn (5 phút)
                if (time() - $tempData['otp_time'] > 300) { 
                    unset($_SESSION['temp_register']);
                    echo "<script>alert('Mã OTP đã hết hạn! Vui lòng đăng ký lại.'); window.location.href='index.php?act=register';</script>";
                    exit;
                }

                // Lưu vào Database
                $userModel = new UserModel();
                $result = $userModel->register(
                    $tempData['fullname'], 
                    $tempData['phone'], 
                    $tempData['password'],
                    $tempData['email']
                );

                if ($result) {
                    unset($_SESSION['temp_register']);
                    echo "<script>alert('Đăng ký thành công! Vui lòng đăng nhập.'); window.location.href='index.php?act=login';</script>";
                } else {
                    echo "<script>alert('Lỗi hệ thống: Không thể tạo tài khoản.'); history.back();</script>";
                }
            } else {
                echo "<script>alert('Mã OTP không chính xác!'); history.back();</script>";
            }
        }
    }

    // ==============================================================
    // 6. QUÊN MẬT KHẨU (HIỆN FORM NHẬP EMAIL)
    // ==============================================================
    public function forgotPasswordForm() {
        require_once __DIR__ . '/../Views/auth/forgot_password.php';
    }

    // ==============================================================
    // 7. QUÊN MẬT KHẨU (GỬI OTP QUA EMAIL)
    // ==============================================================
    public function sendResetOtp() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            
            // 1. Kiểm tra email có trong hệ thống không
            $userModel = new UserModel();
            // Đảm bảo UserModel.php đã có hàm getByEmail($email)
            $user = $userModel->getByEmail($email);

            if (!$user) {
                echo "<script>alert('Email này chưa được đăng ký tài khoản!'); history.back();</script>";
                return;
            }

            // 2. Tạo OTP ngẫu nhiên
            $otp = rand(100000, 999999);
            
            // 3. Lưu Session để lát xác thực
            $_SESSION['reset_otp'] = $otp;
            $_SESSION['reset_email'] = $email;
            $_SESSION['reset_time'] = time(); 

            // 4. Nội dung Email (Tiếng Việt)
            $subject = "Mã xác thực đổi mật khẩu - Mini Mart";
            $body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;'>
                    <h2 style='color: #d0011b; text-align: center;'>Hỗ trợ Mini Mart</h2>
                    <p>Xin chào <strong>" . $user['fullname'] . "</strong>,</p>
                    <p>Bạn vừa yêu cầu lấy lại mật khẩu. Vui lòng sử dụng mã OTP dưới đây:</p>
                    <div style='background-color: #f4f4f4; padding: 15px; text-align: center; font-size: 24px; font-weight: bold; letter-spacing: 5px; color: #333; margin: 20px 0;'>
                        $otp
                    </div>
                    <p style='color: red; font-size: 13px;'>* Mã này chỉ có hiệu lực trong vòng 5 phút.</p>
                    <p>Nếu bạn không yêu cầu, vui lòng bỏ qua email này.</p>
                    <hr>
                    <p style='font-size: 12px; color: #888; text-align: center;'>Mini Mart - Hệ thống siêu thị tiện lợi</p>
                </div>
            ";

            // 5. Gửi Email bằng MailService
            $mailService = new MailService();
            // Gọi hàm gửi email chung (sendBaseEmail) mà chúng ta đã thêm vào MailService
            $isSent = $mailService->sendBaseEmail($email, $subject, $body);

            if ($isSent) {
                echo "<script>
                    alert('Đã gửi mã OTP đến email: $email. Vui lòng kiểm tra hộp thư (kể cả mục Spam)!'); 
                    window.location.href='index.php?act=verify_reset_otp';
                </script>";
            } else {
                echo "<script>alert('Gửi email thất bại! Vui lòng thử lại sau.'); history.back();</script>";
            }
        }
    }

    // ==============================================================
    // 8. QUÊN MẬT KHẨU (NHẬP OTP & ĐỔI PASS MỚI)
    // ==============================================================
    public function verifyResetOtpForm() {
        // Chặn truy cập nếu chưa qua bước gửi mail
        if (!isset($_SESSION['reset_email'])) {
            header('Location: index.php?act=forgot_password');
            exit;
        }
        require_once __DIR__ . '/../Views/auth/verify_reset_otp.php';
    }

    public function resetPassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $otp_input = $_POST['otp'];
            $new_pass = $_POST['new_password'];
            $confirm_pass = $_POST['confirm_password'];

            // 1. Kiểm tra OTP
            if ($otp_input != $_SESSION['reset_otp']) {
                echo "<script>alert('Mã OTP không chính xác!'); history.back();</script>";
                return;
            }

            // 2. Kiểm tra thời gian
            if (time() - $_SESSION['reset_time'] > 300) {
                echo "<script>alert('Mã OTP đã hết hạn! Vui lòng thực hiện lại.'); window.location.href='index.php?act=forgot_password';</script>";
                return;
            }

            // 3. Kiểm tra mật khẩu khớp
            if ($new_pass != $confirm_pass) {
                echo "<script>alert('Mật khẩu xác nhận không khớp!'); history.back();</script>";
                return;
            }

            // 4. Cập nhật mật khẩu mới vào DB
            $userModel = new UserModel();
            // Đảm bảo UserModel.php có hàm updatePassword($email, $newPass)
            $userModel->updatePassword($_SESSION['reset_email'], $new_pass);

            // 5. Xóa Session
            unset($_SESSION['reset_otp']);
            unset($_SESSION['reset_email']);
            unset($_SESSION['reset_time']);

            echo "<script>alert('Đổi mật khẩu thành công! Vui lòng đăng nhập lại.'); window.location.href='index.php?act=login';</script>";
        }
    }
}
?>