<?php
// config.php - Cấu hình chung và các hàm tiện ích cho ứng dụng

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Thư mục lưu trữ file upload
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5 MB

// Định dạng file được phép upload
$ALLOWED_TYPES = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/gif'  => 'gif',
    'image/webp' => 'webp',
    'application/pdf' => 'pdf',
    'text/plain' => 'txt',
    'application/msword' => 'doc',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
    'application/zip' => 'zip'
];

$ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'txt', 'doc', 'docx', 'zip'];

// Đảm bảo thư mục uploads tồn tại
if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0777, true);
}

// Khai báo tài khoản mặc định để kiểm tra Đăng nhập
define('VALID_USERNAME', 'admin');
define('VALID_PASSWORD', '123456');

/**
 * Kiểm tra xem người dùng đã đăng nhập chưa (qua Session hoặc Cookie Ghi nhớ)
 */
function checkAuth() {
    // 1. Kiểm tra qua Session
    if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
        return true;
    }

    // 2. Kiểm tra qua Cookie "Ghi nhớ đăng nhập" (Remember Me)
    if (isset($_COOKIE['remember_user']) && !empty($_COOKIE['remember_user'])) {
        // Trong thực tế sẽ hash token, ở đây mô phỏng lấy lại session từ cookie hợp lệ
        $username = $_COOKIE['remember_user'];
        if ($username === VALID_USERNAME) {
            $_SESSION['user'] = $username;
            $_SESSION['login_time'] = time();
            $_SESSION['auth_method'] = 'cookie';
            return true;
        }
    }

    return false;
}

/**
 * Chuyển đổi dung lượng file sang dạng dễ đọc (KB, MB)
 */
function formatFileSize($bytes) {
    if ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        return $bytes . ' bytes';
    } elseif ($bytes == 1) {
        return '1 byte';
    } else {
        return '0 bytes';
    }
}
