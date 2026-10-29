<?php
require_once 'config.php';

if (!checkAuth()) {
    header('Location: index.php?error=' . urlencode('Vui lòng đăng nhập để tải file!'));
    exit;
}

$filename = $_GET['file'] ?? '';

// Chống lỗi Path Traversal (Chỉ lấy tên file thực tế, loại bỏ path dạng ../../)
$safe_filename = basename($filename);
$filepath = UPLOAD_DIR . $safe_filename;

if (!empty($safe_filename) && file_exists($filepath) && is_file($filepath)) {
    // Đọc metadata để lấy lại tên file gốc ban đầu nếu có
    $meta_file = UPLOAD_DIR . 'files_meta.json';
    $original_name = $safe_filename;

    if (file_exists($meta_file)) {
        $meta_data = json_decode(file_get_contents($meta_file), true);
        if (isset($meta_data[$safe_filename]['original_name'])) {
            $original_name = $meta_data[$safe_filename]['original_name'];
        }
    }

    // Thiết lập HTTP Headers để trình duyệt tự động tải xuống (Force Download)
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . rawurlencode($original_name) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filepath));

    // Đọc file và gửi về client
    ob_clean();
    flush();
    readfile($filepath);
    exit;
} else {
    header('Location: trangchu.php?error=' . urlencode('File không tồn tại hoặc đã bị xóa!'));
    exit;
}
