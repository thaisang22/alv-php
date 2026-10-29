<?php
require_once 'config.php';

// Kiểm tra quyền truy cập: phải đăng nhập mới được upload
if (!checkAuth()) {
    header('Location: index.php?error=' . urlencode('Vui lòng đăng nhập để thực hiện thao tác!'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fileUpload'])) {
    $file = $_FILES['fileUpload'];

    // 1. Kiểm tra lỗi Upload từ PHP
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $upload_errors = [
            UPLOAD_ERR_INI_SIZE   => 'File vượt quá kích thước tối đa cho phép của hệ thống (upload_max_filesize trong php.ini).',
            UPLOAD_ERR_FORM_SIZE  => 'File vượt quá kích thước quy định trong Form HTML.',
            UPLOAD_ERR_PARTIAL    => 'File chỉ mới được upload một phần, chưa hoàn tất.',
            UPLOAD_ERR_NO_FILE    => 'Chưa chọn file nào để upload.',
            UPLOAD_ERR_NO_TMP_DIR => 'Thiếu thư mục tạm trên server.',
            UPLOAD_ERR_CANT_WRITE => 'Không thể ghi file vào đĩa.',
            UPLOAD_ERR_EXTENSION  => 'Quá trình upload bị dừng bởi một extension PHP.'
        ];
        $error_message = $upload_errors[$file['error']] ?? 'Có lỗi xảy ra trong quá trình upload.';
        header('Location: trangchu.php?error=' . urlencode($error_message));
        exit;
    }

    $file_name     = $file['name'];
    $file_size     = $file['size'];
    $file_tmp      = $file['tmp_name'];
    $file_type     = $file['type'];

    // Lấy phần mở rộng (extension) của file
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // 2. KIỂM SOÁT SIZE (Kích thước file)
    if ($file_size > MAX_FILE_SIZE) {
        $error_message = "Dung lượng file lớn quá quy định! Tối đa cho phép là " . formatFileSize(MAX_FILE_SIZE) . " (File của bạn: " . formatFileSize($file_size) . ")";
        header('Location: trangchu.php?error=' . urlencode($error_message));
        exit;
    }

    // 3. KIỂM SOÁT TYPE (Định dạng file cho phép)
    if (!in_array($file_ext, $ALLOWED_EXTENSIONS)) {
        $error_message = "Định dạng file [." . $file_ext . "] không hợp lệ! Chỉ chấp nhận: " . implode(', ', $ALLOWED_EXTENSIONS);
        header('Location: trangchu.php?error=' . urlencode($error_message));
        exit;
    }

    // 4. ĐỔI TÊN FILE SAU KHỊ UPLOAD (Đảm bảo tên file duy nhất, tránh trùng lặp & bảo mật)
    // Ví dụ đặt tên: file_20261002_120000_654321.jpg
    $safe_basename = preg_replace('/[^a-zA-Z0-9_\-]/', '_', pathinfo($file_name, PATHINFO_FILENAME));
    $new_file_name = $safe_basename . '_' . date('Ymd_His') . '_' . substr(md5(uniqid()), 0, 6) . '.' . $file_ext;
    $destination = UPLOAD_DIR . $new_file_name;

    // 5. DI CHUYỂN FILE TỪ THƯ MỤC TẠM (move_uploaded_file)
    if (move_uploaded_file($file_tmp, $destination)) {
        // Lưu thông tin file vào file metadata.json để quản lý chi tiết tên gốc, ngày upload, v.v.
        $meta_file = UPLOAD_DIR . 'files_meta.json';
        $meta_data = file_exists($meta_file) ? json_decode(file_get_contents($meta_file), true) : [];
        if (!is_array($meta_data)) {
            $meta_data = [];
        }

        $meta_data[$new_file_name] = [
            'original_name' => $file_name,
            'stored_name'   => $new_file_name,
            'size'          => $file_size,
            'formatted_size'=> formatFileSize($file_size),
            'extension'     => $file_ext,
            'mime_type'     => $file_type,
            'uploaded_by'   => $_SESSION['user'] ?? 'Guest',
            'upload_time'   => date('H:i:s d/m/Y')
        ];

        file_put_contents($meta_file, json_encode($meta_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $msg = "Upload thành công file <strong>" . htmlspecialchars($file_name) . "</strong> (Đã đổi tên thành: <em>" . htmlspecialchars($new_file_name) . "</em>)";
        header('Location: trangchu.php?msg=' . urlencode($msg));
        exit;
    } else {
        header('Location: trangchu.php?error=' . urlencode('Không thể lưu file vào thư mục uploads! Kiểm tra quyền ghi thư mục.'));
        exit;
    }
} else {
    header('Location: trangchu.php');
    exit;
}