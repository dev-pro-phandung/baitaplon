<?php
// Nhúng thư viện PHPMailer
require_once __DIR__ . '/../libs/PHPMailer/Exception.php';
require_once __DIR__ . '/../libs/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../libs/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService {
    
    // CẤU HÌNH EMAIL
    private $mail_host = 'smtp.gmail.com';
    private $mail_username = 'kamejokola207@gmail.com'; 
    private $mail_password = 'dqfl fkrp fywb czqq'; // Mã ứng dụng bạn đã cung cấp
    private $mail_port = 587;
    private $mail_from_name = 'Mini Mart Thong Bao';

    public function sendOrderConfirmation($toEmail, $toName, $orderId, $totalMoney) {
        $mail = new PHPMailer(true);

        try {
            // =================================================================
            // 1. CẤU HÌNH DEBUG
            // =================================================================
            // Số 2: Hiện chi tiết lỗi (Dùng khi đang sửa lỗi)
            // Số 0: Tắt thông báo (Dùng khi web đã chạy ổn định)
            $mail->SMTPDebug = 2; 
            $mail->Debugoutput = 'html'; 

            // =================================================================
            // 2. CẤU HÌNH SERVER
            // =================================================================
            $mail->isSMTP();
            $mail->Host       = $this->mail_host;
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->mail_username;
            $mail->Password   = $this->mail_password;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
            $mail->Port       = $this->mail_port;

            // --- [QUAN TRỌNG] FIX LỖI GỬI MAIL TRÊN LOCALHOST/XAMPP ---
            // Đoạn này giúp bỏ qua lỗi chứng chỉ bảo mật SSL của XAMPP
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            // ----------------------------------------------------------

            $mail->CharSet    = 'UTF-8';

            // =================================================================
            // 3. NGƯỜI GỬI & NGƯỜI NHẬN
            // =================================================================
            $mail->setFrom($this->mail_username, $this->mail_from_name);
            
            // Kiểm tra email nhận có hợp lệ không
            if (filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
                $mail->addAddress($toEmail, $toName);
            } else {
                echo "<h3 style='color:red'>Lỗi: Email người nhận không đúng định dạng ($toEmail)</h3>";
                return false;
            }

            // =================================================================
            // 4. NỘI DUNG EMAIL
            // =================================================================
            $mail->isHTML(true);
            $mail->Subject = "Xác nhận đơn hàng #$orderId - Mini Mart";
            
            $bodyContent = "
                <div style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                    <h3 style='color: #d0011b;'>Cảm ơn bạn $toName đã đặt hàng tại Mini Mart!</h3>
                    <p>Đơn hàng <b>#$orderId</b> của bạn đã được ghi nhận thành công.</p>
                    <p>Tổng giá trị đơn hàng: <b style='color:red; font-size: 18px;'>" . number_format($totalMoney) . " VNĐ</b></p>
                    <hr>
                    <p>Chúng tôi sẽ sớm liên hệ để giao hàng.</p>
                    <p style='font-size: 12px; color: #777;'>Đây là email tự động, vui lòng không trả lời.</p>
                </div>
            ";
            
            $mail->Body = $bodyContent;
            $mail->AltBody = "Don hang #$orderId cua ban da duoc ghi nhan. Tong tien: " . number_format($totalMoney);

            $mail->send();
            return true; // Gửi thành công

        } catch (Exception $e) {
            // Hiện lỗi chi tiết ra màn hình để debug
            echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border: 1px solid #f5c6cb; margin: 20px;'>";
            echo "<h3>LỖI GỬI EMAIL KHÔNG THÀNH CÔNG:</h3>";
            echo "<b>Chi tiết lỗi:</b> " . $mail->ErrorInfo;
            echo "</div>";
            return false;
        }
    }
    // --- HÀM GỬI EMAIL CHUNG (Dùng cho OTP, Thông báo, v.v...) ---
    public function sendBaseEmail($toEmail, $subject, $bodyContent) {
        $mail = new PHPMailer(true);
        try {
            // Cấu hình Server
            $mail->SMTPDebug = 0; // Tắt debug để không hiện code lạ lên màn hình
            $mail->isSMTP();
            $mail->Host       = $this->mail_host;
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->mail_username;
            $mail->Password   = $this->mail_password;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $this->mail_port;
            $mail->CharSet    = 'UTF-8';

            // Fix lỗi SSL Localhost
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            // Người gửi & Nhận
            $mail->setFrom($this->mail_username, $this->mail_from_name);
            $mail->addAddress($toEmail);

            // Nội dung
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $bodyContent;
            $mail->AltBody = strip_tags($bodyContent); // Nội dung thô cho trình duyệt cũ

            $mail->send();
            return true;
        } catch (Exception $e) {
            // Ghi log lỗi nếu cần
            return false;
        }
    }
}
?>