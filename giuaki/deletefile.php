<?php
require_once 'config.php';

if (!checkAuth()) {
    header('Location: index.php?error=' . urlencode('Vui lòng đăng nhập để thực hiện thao tác xóa!'));
    exit;
}

$filename = $_GET['file'] ?? '';
$safe_filename = basename($filename);
$filepath = UPLOAD_DIR . $safe_filename;

// Kiểm tra loại bỏ file đặc biệt (như files_meta.json)
if ($safe_filename === 'files_meta.json') {
    header('Location: trangchu.php?error=' . urlencode('Không thể xóa file hệ thống!'));
    exit;
}

if (!empty($safe_filename) && file_exists($filepath) && is_file($filepath)) {
    // 1. Thực hiện xóa file khỏi thư mục ổ cứng bằng hàm unlink()
    if (unlink($filepath)) {

        // 2. Cập nhật lại file metadata.json
        $meta_file = UPLOAD_DIR . 'files_meta.json';
        if (file_exists($meta_file)) {
            $meta_data = json_decode(file_get_contents($meta_file), true);
            if (isset($meta_data[$safe_filename])) {
                unset($meta_data[$safe_filename]);
                file_put_contents($meta_file, json_encode($meta_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }
        }

        $msg = "Đã xóa thành công file: <strong>" . htmlspecialchars($safe_filename) . "</strong>";
        header('Location: trangchu.php?msg=' . urlencode($msg));
        exit;
    } else {
        header('Location: trangchu.php?error=' . urlencode('Không thể xóa file! Kiểm tra lại phân quyền hệ thống.'));
        exit;
    }
} else {
    header('Location: trangchu.php?error=' . urlencode('File không tồn tại hoặc đã bị xóa trước đó!'));
    exit;
}
