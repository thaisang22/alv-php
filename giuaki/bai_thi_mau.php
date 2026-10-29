<?php
session_start();

// ==========================================
// PHẦN 1: CÁC HÀM XỬ LÝ (BACKEND & UTILS)
// ==========================================

// Hàm hiển thị thông báo lỗi (Yêu cầu đề bài)
function show_message($message, $type = 'error') {
    if (empty($message)) return '';
    $color = ($type === 'error') ? '#f44336' : '#4caf50';
    $bgColor = ($type === 'error') ? '#ffebee' : '#e8f5e9';
    return "<div style='padding: 10px; margin-bottom: 15px; border: 1px solid $color; background-color: $bgColor; color: $color; border-radius: 4px;'>
                <strong>" . ($type === 'error' ? 'Lỗi:' : 'Thành công:') . "</strong> $message
            </div>";
}

// Hàm lưu user vào file (giả lập Database)
$users_file = 'users.json';
function get_users() {
    global $users_file;
    if (!file_exists($users_file)) {
        file_put_contents($users_file, json_encode([]));
    }
    return json_decode(file_get_contents($users_file), true);
}

function save_user($username, $password) {
    global $users_file;
    $users = get_users();
    if (isset($users[$username])) {
        return false; // User đã tồn tại
    }
    // Trong thực tế cần dùng password_hash, nhưng thi thường yêu cầu đơn giản
    $users[$username] = $password;
    file_put_contents($users_file, json_encode($users));
    return true;
}

function check_credentials($username, $password) {
    $users = get_users();
    if (isset($users[$username]) && $users[$username] === $password) {
        return true;
    }
    return false;
}

// Kiểm tra Session và Cookie
if (!isset($_SESSION['logged_in_user']) && isset($_COOKIE['remembered_user'])) {
    // Tự suy diễn theo dõi người dùng: nếu có cookie thì tự đăng nhập lại (Ghi nhớ đăng nhập)
    $cookie_user = $_COOKIE['remembered_user'];
    $users = get_users();
    if (isset($users[$cookie_user])) {
        $_SESSION['logged_in_user'] = $cookie_user;
    }
}

// Khởi tạo biến thông báo
$msg = '';
$msg_type = '';

// Thư mục lưu file
$upload_dir = 'uploads/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// ==========================================
// PHẦN 2: XỬ LÝ CÁC ACTION (POST / GET)
// ==========================================

// 2.1 Xử lý Đăng Ký
if (isset($_POST['btn_register'])) {
    $user = trim($_POST['reg_username']);
    $pass = trim($_POST['reg_password']);
    
    if (empty($user) || empty($pass)) {
        $msg = "Vui lòng nhập đầy đủ tài khoản và mật khẩu.";
        $msg_type = "error";
    } else {
        if (save_user($user, $pass)) {
            $msg = "Đăng ký thành công! Bạn có thể đăng nhập ngay.";
            $msg_type = "success";
        } else {
            $msg = "Tên đăng nhập đã tồn tại.";
            $msg_type = "error";
        }
    }
}

// 2.2 Xử lý Đăng Nhập
if (isset($_POST['btn_login'])) {
    $user = trim($_POST['log_username']);
    $pass = trim($_POST['log_password']);
    $remember = isset($_POST['remember']);
    
    if (empty($user) || empty($pass)) {
        $msg = "Vui lòng nhập tài khoản và mật khẩu.";
        $msg_type = "error";
    } else {
        if (check_credentials($user, $pass)) {
            // Lưu Session
            $_SESSION['logged_in_user'] = $user;
            
            // Xử lý Ghi nhớ đăng nhập (Cookie)
            if ($remember) {
                // Lưu cookie trong 7 ngày
                setcookie('remembered_user', $user, time() + (7 * 24 * 3600), "/");
            }
            
            $msg = "Đăng nhập thành công!";
            $msg_type = "success";
        } else {
            $msg = "Tài khoản hoặc mật khẩu không chính xác.";
            $msg_type = "error";
        }
    }
}

// 2.3 Xử lý Đăng Xuất
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    unset($_SESSION['logged_in_user']);
    setcookie('remembered_user', '', time() - 3600, "/"); // Xóa cookie
    header("Location: bai_thi_mau.php");
    exit;
}

// 2.4 Xử lý Upload File (Chỉ cho phép khi đã đăng nhập)
if (isset($_POST['btn_upload'])) {
    if (!isset($_SESSION['logged_in_user'])) {
        $msg = "Bạn phải đăng nhập để upload file.";
        $msg_type = "error";
    } else {
        if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] == 0) {
            $file = $_FILES['file_upload'];
            
            // 1. Kiểm soát kích thước (Size) - ví dụ max 2MB
            $max_size = 2 * 1024 * 1024;
            if ($file['size'] > $max_size) {
                $msg = "Lỗi: File vượt quá kích thước cho phép (2MB).";
                $msg_type = "error";
            } else {
                // 2. Kiểm soát định dạng (Type)
                $allowed_exts = ['jpg', 'png', 'pdf', 'docx'];
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                
                if (!in_array($ext, $allowed_exts)) {
                    $msg = "Lỗi: Chỉ cho phép upload file jpg, png, pdf, docx.";
                    $msg_type = "error";
                } else {
                    // 3. Đổi tên file (Theo yêu cầu: mssv + ngày giờ + filename)
                    // CMT để dễ customize: 
                    // - Lấy tên gốc của file
                    $original_name = pathinfo($file['name'], PATHINFO_FILENAME);
                    // - Thông tin MSSV (có thể lấy từ session nếu có)
                    $mssv = "2024001"; // Gán cứng hoặc thay bằng $_SESSION['mssv']
                    // - Thời gian
                    $time_str = date('Ymd_His');
                    
                    // Nối chuỗi để tạo tên mới
                    $new_filename = $mssv . '_' . $time_str . '_' . $original_name . '.' . $ext;
                    
                    $destination = $upload_dir . $new_filename;
                    
                    // 4. Di chuyển file
                    if (move_uploaded_file($file['tmp_name'], $destination)) {
                        $msg = "Upload file thành công: $new_filename";
                        $msg_type = "success";
                    } else {
                        $msg = "Lỗi: Không thể lưu file vào thư mục đích.";
                        $msg_type = "error";
                    }
                }
            }
        } else {
            $msg = "Lỗi: Không có file nào được chọn hoặc có lỗi trong quá trình truyền.";
            $msg_type = "error";
        }
    }
}

// 2.5 Xử lý Tải xuống (Download)
if (isset($_GET['action']) && $_GET['action'] == 'download' && isset($_GET['file'])) {
    if (!isset($_SESSION['logged_in_user'])) {
        die("Bạn phải đăng nhập để tải file.");
    }
    
    $filename = basename($_GET['file']); // basename để chống path traversal
    $filepath = $upload_dir . $filename;
    
    if (file_exists($filepath)) {
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
        $msg = "Lỗi: File không tồn tại để tải xuống.";
        $msg_type = "error";
    }
}

// 2.6 Xử lý Xóa file
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['file'])) {
    if (!isset($_SESSION['logged_in_user'])) {
        $msg = "Bạn phải đăng nhập để xóa file.";
        $msg_type = "error";
    } else {
        $filename = basename($_GET['file']);
        $filepath = $upload_dir . $filename;
        
        if (file_exists($filepath)) {
            if (unlink($filepath)) {
                $msg = "Xóa file thành công.";
                $msg_type = "success";
            } else {
                $msg = "Lỗi: Không thể xóa file.";
                $msg_type = "error";
            }
        } else {
            $msg = "Lỗi: File không tồn tại để xóa.";
            $msg_type = "error";
        }
    }
}

$is_logged_in = isset($_SESSION['logged_in_user']);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bài Thi Mẫu - Đăng Ký, Đăng Nhập & Upload File</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; color: #333; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        .box { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
        h2 { margin-top: 0; color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="password"], input[type="file"] { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background-color: #3498db; color: white; border: none; padding: 10px 15px; cursor: pointer; border-radius: 4px; font-size: 14px; }
        button:hover { background-color: #2980b9; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        a.action-btn { text-decoration: none; padding: 5px 10px; border-radius: 3px; font-size: 12px; }
        a.btn-download { background-color: #4caf50; color: white; }
        a.btn-delete { background-color: #f44336; color: white; }
    </style>
</head>
<body>

<div class="container">
    <h1 style="text-align: center; color: #2c3e50;">TRANG ÔN TẬP TỔNG HỢP</h1>

    <!-- HIỂN THỊ THÔNG BÁO -->
    <?= show_message($msg, $msg_type) ?>

    <?php if (!$is_logged_in): ?>
        <!-- GIAO DIỆN CHƯA ĐĂNG NHẬP -->
        <div class="grid">
            <!-- Form Đăng Ký -->
            <div class="box">
                <h2>1. Đăng Ký</h2>
                <form action="bai_thi_mau.php" method="POST">
                    <div class="form-group">
                        <label>Tên đăng nhập:</label>
                        <input type="text" name="reg_username" required>
                    </div>
                    <div class="form-group">
                        <label>Mật khẩu:</label>
                        <input type="password" name="reg_password" required>
                    </div>
                    <button type="submit" name="btn_register">Đăng Ký</button>
                </form>
            </div>

            <!-- Form Đăng Nhập -->
            <div class="box">
                <h2>2. Đăng Nhập</h2>
                <form action="bai_thi_mau.php" method="POST">
                    <div class="form-group">
                        <label>Tên đăng nhập:</label>
                        <input type="text" name="log_username" required>
                    </div>
                    <div class="form-group">
                        <label>Mật khẩu:</label>
                        <input type="password" name="log_password" required>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: normal;">
                            <input type="checkbox" name="remember"> Ghi nhớ đăng nhập
                        </label>
                    </div>
                    <button type="submit" name="btn_login">Đăng Nhập</button>
                </form>
            </div>
        </div>

    <?php else: ?>
        <!-- GIAO DIỆN ĐÃ ĐĂNG NHẬP -->
        <div class="box" style="background-color: #e3f2fd;">
            <p style="margin: 0;">Xin chào, <strong><?= htmlspecialchars($_SESSION['logged_in_user']) ?></strong>! 
            <a href="bai_thi_mau.php?action=logout" style="color: #d32f2f; font-weight: bold; margin-left: 10px;">[Đăng Xuất]</a></p>
        </div>

        <div class="box">
            <h2>3. Upload File</h2>
            <form action="bai_thi_mau.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Chọn file để upload (Tối đa 2MB, Định dạng: jpg, png, pdf, docx):</label>
                    <input type="file" name="file_upload" required>
                </div>
                <button type="submit" name="btn_upload">Tải File Lên</button>
            </form>
        </div>

        <div class="box">
            <h2>4. Quản lý File (Tải xuống & Xóa)</h2>
            <table>
                <thead>
                    <tr>
                        <th>Tên File</th>
                        <th>Kích thước</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $files = array_diff(scandir($upload_dir), array('.', '..'));
                    if (count($files) > 0):
                        foreach ($files as $f):
                            $path = $upload_dir . $f;
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($f) ?></td>
                            <td><?= round(filesize($path) / 1024, 2) ?> KB</td>
                            <td>
                                <a href="bai_thi_mau.php?action=download&file=<?= urlencode($f) ?>" class="action-btn btn-download">Tải xuống</a>
                                <a href="bai_thi_mau.php?action=delete&file=<?= urlencode($f) ?>" class="action-btn btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa file này?');">Xóa</a>
                            </td>
                        </tr>
                    <?php
                        endforeach;
                    else:
                    ?>
                        <tr>
                            <td colspan="3" style="text-align: center;">Chưa có file nào được tải lên.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    <?php endif; ?>

</div>

</body>
</html>
