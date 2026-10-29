<?php
// ==========================================
// FILE: functions_tutorial.php
// Mục đích: Chứa toàn bộ các hàm dùng chung để tái sử dụng
// ==========================================

// 1. Hàm hiển thị thông báo lỗi / thành công ra màn hình
// Lưu ý: Luôn cần hàm này để echo lỗi trực quan cho người dùng.
function show_message($message, $type = 'error') {
    if (empty($message)) return '';
    $color = ($type === 'error') ? '#f44336' : '#4caf50';
    $bgColor = ($type === 'error') ? '#ffebee' : '#e8f5e9';
    return "<div style='padding: 10px; margin-bottom: 15px; border: 1px solid $color; background-color: $bgColor; color: $color; border-radius: 4px;'>
                <strong>" . ($type === 'error' ? 'Lỗi:' : 'Thành công:') . "</strong> $message
            </div>";
}

// ==========================================
// PHẦN XỬ LÝ DATABASE GIẢ LẬP (Bằng JSON)
// ==========================================
$users_file = 'users.json';

// 2. Hàm lấy danh sách user
function get_users() {
    global $users_file;
    if (!file_exists($users_file)) {
        file_put_contents($users_file, json_encode([]));
    }
    return json_decode(file_get_contents($users_file), true);
}

// 3. Hàm lưu user mới (Dùng cho Đăng ký)
function save_user($username, $password) {
    global $users_file;
    $users = get_users();
    if (isset($users[$username])) {
        return false; // User đã tồn tại
    }
    $users[$username] = $password;
    file_put_contents($users_file, json_encode($users));
    return true;
}

// 4. Hàm kiểm tra đăng nhập (Dùng cho Đăng nhập)
function check_credentials($username, $password) {
    $users = get_users();
    if (isset($users[$username]) && $users[$username] === $password) {
        return true;
    }
    return false;
}

// ==========================================
// PHẦN XỬ LÝ FILE (UPLOAD, DOWNLOAD, DELETE)
// ==========================================

// 5. Hàm xử lý Upload File
function handle_upload($file, $upload_dir) {
    // a. Kiểm soát dung lượng (ví dụ: max 2MB)
    $max_size = 2 * 1024 * 1024; 
    if ($file['size'] > $max_size) {
        return ["status" => false, "msg" => "File vượt quá kích thước cho phép (2MB)."];
    }

    // b. Kiểm soát định dạng file (Type)
    $allowed_exts = ['jpg', 'png', 'pdf', 'docx'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($ext, $allowed_exts)) {
        return ["status" => false, "msg" => "Chỉ cho phép upload file jpg, png, pdf, docx."];
    }

    // c. Đổi tên file sau khi upload (Tránh trùng lặp file)
    // Cấu trúc: [MSSV]_[Thời gian]_[Tên gốc].[Đuôi]
    $original_name = pathinfo($file['name'], PATHINFO_FILENAME);
    $mssv = "2024001"; // Lấy từ Session hoặc gán cứng theo đề bài
    $time_str = date('Ymd_His');
    
    $new_filename = $mssv . '_' . $time_str . '_' . $original_name . '.' . $ext;
    $destination = $upload_dir . $new_filename;

    // d. Di chuyển file vào thư mục đích
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return ["status" => true, "msg" => "Upload file thành công: $new_filename"];
    } else {
        return ["status" => false, "msg" => "Không thể lưu file vào thư mục đích do lỗi hệ thống."];
    }
}

// 6. Hàm xử lý Tải Xuống (Download)
function handle_download($filename, $upload_dir) {
    // Chống Path Traversal bằng hàm basename
    $filename = basename($filename);
    $filepath = $upload_dir . $filename;

    if (file_exists($filepath) && is_file($filepath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit;
    } else {
        return "File không tồn tại để tải xuống.";
    }
}

// 7. Hàm xử lý Xóa File (Delete)
function handle_delete($filename, $upload_dir) {
    $filename = basename($filename); // Bắt buộc dùng basename để an toàn
    $filepath = $upload_dir . $filename;

    if (file_exists($filepath) && is_file($filepath)) {
        if (unlink($filepath)) {
            return ["status" => true, "msg" => "Xóa file thành công."];
        } else {
            return ["status" => false, "msg" => "Không thể xóa file. Kiểm tra lại quyền thư mục."];
        }
    } else {
        return ["status" => false, "msg" => "File không tồn tại."];
    }
}
?>
