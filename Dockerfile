# 1. Bắt đầu từ image PHP phiên bản 8.2 có tích hợp sẵn web server Apache
FROM php:8.2-apache

# 2. Cài đặt các thư viện cần thiết để PHP có thể nói chuyện được với cơ sở dữ liệu MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# 3. Kích hoạt module 'rewrite' của Apache (Điều kiện bắt buộc để các đường dẫn URL trong kiến trúc MVC hoạt động trơn tru)
RUN a2enmod rewrite

# 4. (Tùy chọn) Thiết lập thư mục làm việc mặc định của web server
WORKDIR /var/www/html
