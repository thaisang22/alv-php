<?php
session_start();
require_once 'functions_tutorial.php'; // Include file chứa các functions

// Biến lưu thông báo
$msg = '';
$msg_type = '';

// Thiết lập thư mục upload
$upload_dir = 'uploads/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// ==========================================
// 1. KIỂM TRA COOKIE (GHI NHỚ ĐĂNG NHẬP)
// ==========================================
// Lưu ý: Code này tự động đăng nhập nếu user có cookie "remembered_user"
if (!isset($_SESSION['logged_in_user']) && isset($_COOKIE['remembered_user'])) {
    $cookie_user = $_COOKIE['remembered_user'];
    $users = get_users();
    if (isset($users[$cookie_user])) {
        $_SESSION['logged_in_user'] = $cookie_user;
    }
}

// ==========================================
// 2. XỬ LÝ CÁC FORM SUBMIT (POST / GET)
// ==========================================

// Xử lý Đăng Ký
if (isset($_POST['btn_register'])) {
    $user = trim($_POST['reg_username']);
    $pass = trim($_POST['reg_password']);
    
    if (empty($user) || empty($pass)) {
        $msg = "Vui lòng nhập đầy đủ tài khoản và mật khẩu.";
        $msg_type = "error";
    } else {
        if (save_user($user, $pass)) {
            $msg = "Đăng ký thành công! Bạn có thể đăng nhập ở form bên cạnh.";
            $msg_type = "success";
        } else {
            $msg = "Tên đăng nhập đã tồn tại. Vui lòng chọn tên khác.";
            $msg_type = "error";
        }
    }
}

// Xử lý Đăng Nhập
if (isset($_POST['btn_login'])) {
    $user = trim($_POST['log_username']);
    $pass = trim($_POST['log_password']);
    $remember = isset($_POST['remember']);
    
    if (empty($user) || empty($pass)) {
        $msg = "Vui lòng nhập tài khoản và mật khẩu.";
        $msg_type = "error";
    } else {
        if (check_credentials($user, $pass)) {
            $_SESSION['logged_in_user'] = $user;
            
            // Nếu tick "Ghi nhớ", set Cookie lưu 7 ngày
            if ($remember) {
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

// Xử lý Đăng Xuất
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    unset($_SESSION['logged_in_user']);
    setcookie('remembered_user', '', time() - 3600, "/"); // Hủy cookie
    header("Location: tutorial_bai_thi.php");
    exit;
}

// Xử lý Upload File
if (isset($_POST['btn_upload'])) {
    if (!isset($_SESSION['logged_in_user'])) {
        $msg = "Bạn phải đăng nhập để sử dụng tính năng upload.";
        $msg_type = "error";
    } else {
        if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] == 0) {
            // Gọi hàm handle_upload từ functions_tutorial.php
            $result = handle_upload($_FILES['file_upload'], $upload_dir);
            $msg = $result['msg'];
            $msg_type = $result['status'] ? 'success' : 'error';
        } else {
            $msg = "Vui lòng chọn file hợp lệ.";
            $msg_type = "error";
        }
    }
}

// Xử lý Tải File / Xóa File
if (isset($_GET['action'])) {
    if (!isset($_SESSION['logged_in_user'])) {
        $msg = "Bạn phải đăng nhập để thao tác với file.";
        $msg_type = "error";
    } else {
        $action = $_GET['action'];
        if ($action === 'download' && isset($_GET['file'])) {
            $error_msg = handle_download($_GET['file'], $upload_dir);
            if ($error_msg) {
                $msg = $error_msg;
                $msg_type = 'error';
            }
        } elseif ($action === 'delete' && isset($_GET['file'])) {
            $result = handle_delete($_GET['file'], $upload_dir);
            $msg = $result['msg'];
            $msg_type = $result['status'] ? 'success' : 'error';
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
    <title>Tutorial Ôn Tập: PHP Session, Cookies & File Handling</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0f4f8; color: #333; margin: 0; padding: 20px; line-height: 1.6; }
        .container { max-width: 1000px; margin: 0 auto; }
        .box { background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 25px; }
        .tutorial-note { background-color: #fff3cd; border-left: 5px solid #ffc107; padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .tutorial-note h4 { margin-top: 0; color: #856404; }
        h2 { margin-top: 0; color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="password"], input[type="file"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { background-color: #3498db; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; font-size: 15px; transition: 0.3s; }
        button:hover { background-color: #2980b9; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #eee; padding: 12px; text-align: left; }
        th { background-color: #f8f9fa; }
        a.action-btn { text-decoration: none; padding: 6px 12px; border-radius: 4px; font-size: 13px; color: white; }
        a.btn-download { background-color: #10b981; }
        a.btn-delete { background-color: #ef4444; }
        pre { background: #2d3748; color: #f7fafc; padding: 15px; border-radius: 5px; overflow-x: auto; }
        code { font-family: Consolas, monospace; }
    </style>
</head>
<body>

<div class="container">
    <h1 style="text-align: center; color: #1e3a8a; margin-bottom: 40px;">HƯỚNG DẪN THỰC HÀNH: ĐĂNG KÝ, ĐĂNG NHẬP & UPLOAD FILE</h1>

    <!-- KHU VỰC HIỂN THỊ THÔNG BÁO CHUNG -->
    <?= show_message($msg, $msg_type) ?>

    <?php if (!$is_logged_in): ?>
        <!-- PHẦN 1: AUTHENTICATION -->
        <div class="tutorial-note">
            <h4>💡 LƯU Ý PHẦN 1: Session & Cookies (Đăng nhập / Ghi nhớ)</h4>
            <ul>
                <li><strong>Session:</strong> Phải gọi <code>session_start()</code> ở dòng ĐẦU TIÊN của file. Dùng <code>$_SESSION['ten_bien']</code> để lưu trạng thái đăng nhập.</li>
                <li><strong>Cookies:</strong> Dùng <code>setcookie()</code> để thiết lập tính năng "Ghi nhớ đăng nhập". Nhớ truyền thời gian sống (VD: <code>time() + 86400</code>).</li>
                <li>Hàm xử lý database đã được đưa ra ngoài file <code>functions_tutorial.php</code> để code HTML gọn gàng hơn.</li>
            </ul>
        </div>

        <div class="grid">
            <div class="box">
                <h2>1. Mô hình Đăng Ký</h2>
                <form action="tutorial_bai_thi.php" method="POST">
                    <div class="form-group">
                        <label>Tên đăng nhập:</label>
                        <input type="text" name="reg_username" required>
                    </div>
                    <div class="form-group">
                        <label>Mật khẩu:</label>
                        <input type="password" name="reg_password" required>
                    </div>
                    <button type="submit" name="btn_register">Thực hiện Đăng Ký</button>
                </form>
            </div>

            <div class="box">
                <h2>2. Mô hình Đăng Nhập</h2>
                <form action="tutorial_bai_thi.php" method="POST">
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
                            <input type="checkbox" name="remember"> <em>Ghi nhớ đăng nhập (Sử dụng Cookie)</em>
                        </label>
                    </div>
                    <button type="submit" name="btn_login">Thực hiện Đăng Nhập</button>
                </form>
            </div>
        </div>

    <?php else: ?>
        <!-- PHẦN 2: FILE UPLOAD -->
        <div class="box" style="background-color: #e0f2fe; border-left: 5px solid #0ea5e9;">
            <p style="margin: 0; font-size: 18px;">
                Đang đăng nhập dưới tư cách: <strong style="color: #0ea5e9;"><?= htmlspecialchars($_SESSION['logged_in_user']) ?></strong>
                <a href="tutorial_bai_thi.php?action=logout" style="float: right; color: #ef4444; text-decoration: none; font-weight: bold;">[Đăng Xuất]</a>
            </p>
        </div>

        <div class="tutorial-note">
            <h4>💡 LƯU Ý PHẦN 2: Xử lý Upload & Bảo mật File</h4>
            <ul>
                <li><strong>Form HTML:</strong> Bắt buộc phải có thuộc tính <code>enctype="multipart/form-data"</code>, nếu không <code>$_FILES</code> sẽ trống.</li>
                <li><strong>Kiểm soát Upload:</strong> 
                    <ul>
                        <li><code>Size:</code> Kiểm tra <code>$_FILES['...']['size']</code> so với mức trần (VD: 2MB = 2 * 1024 * 1024 bytes).</li>
                        <li><code>Type:</code> Dùng <code>pathinfo()</code> lấy phần đuôi file (extension) và so sánh với mảng đuôi cho phép (jpg, png...).</li>
                        <li><code>Đổi tên:</code> Nối chuỗi để file không bị đè. Công thức phổ biến: <em>MSSV + Date + Tên gốc</em>.</li>
                    </ul>
                </li>
                <li><strong>Xóa/Tải file:</strong> Khi truyền tham số file qua URL (GET), phải dùng hàm <code>basename($_GET['file'])</code> để chống tấn công nhảy thư mục (Path Traversal).</li>
            </ul>
        </div>

        <div class="box">
            <h2>3. Upload File An Toàn</h2>
            <form action="tutorial_bai_thi.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Chọn file (Minh họa: Tối đa 2MB, Định dạng: jpg, png, pdf, docx):</label>
                    <input type="file" name="file_upload" required>
                </div>
                <button type="submit" name="btn_upload">Tiến hành Tải Lên</button>
            </form>
        </div>

        <div class="box">
            <h2>4. Quản lý File Server</h2>
            <table>
                <thead>
                    <tr>
                        <th>Tên File (Sau khi đã đổi tên)</th>
                        <th>Kích thước</th>
                        <th style="width: 150px;">Hành động</th>
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
                            <td><code><?= htmlspecialchars($f) ?></code></td>
                            <td><?= round(filesize($path) / 1024, 2) ?> KB</td>
                            <td>
                                <a href="tutorial_bai_thi.php?action=download&file=<?= urlencode($f) ?>" class="action-btn btn-download">Tải</a>
                                <a href="tutorial_bai_thi.php?action=delete&file=<?= urlencode($f) ?>" class="action-btn btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa file này?');">Xóa</a>
                            </td>
                        </tr>
                    <?php
                        endforeach;
                    else:
                    ?>
                        <tr>
                            <td colspan="3" style="text-align: center;">Thư mục trống. Hãy upload file!</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    <?php endif; ?>

</div>

</body>
</html>
