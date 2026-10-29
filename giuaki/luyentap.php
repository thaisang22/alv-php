<?php
require_once 'config.php';

if (!checkAuth()) {
    header('Location: index.php?error=' . urlencode('Vui lòng đăng nhập để truy cập trang bài luyện tập!'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bài Luyện Tập Tổng Hợp - Ôn Tập Giữa Kỳ PHP</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="container nav-wrapper">
        <a href="trangchu.php" class="brand">
            <div class="brand-icon">📚</div>
            <span>Bộ Bài Tập & Lý Thuyết Ôn Thi Giữa Kỳ</span>
        </a>
        <ul class="nav-links">
            <li><a href="trangchu.php">Trang Chủ</a></li>
            <li><a href="luyentap.php" class="active">📚 Bài Luyện Tập & Tổng hợp</a></li>
            <li>
                <div class="user-badge">
                    <div class="avatar"><?= strtoupper(substr($_SESSION['user'] ?? 'U', 0, 1)) ?></div>
                    <span style="font-size: 0.9rem; font-weight: 600; color: #fff;"><?= htmlspecialchars($_SESSION['user'] ?? 'User') ?></span>
                </div>
            </li>
            <li>
                <a href="logout.php" class="btn btn-danger btn-sm">🚪 Đăng Xuất</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container" style="margin-top: 30px;">

    <div style="margin-bottom: 24px;">
        <h1 style="font-size: 1.8rem; font-weight: 700; color: var(--accent-purple);">🎯 Tổng Hợp Kịch Bản Xử Lý Giữa Kỳ</h1>
        <p style="color: var(--text-secondary); font-size: 0.95rem;">Tài liệu tổng hợp lại toàn bộ lý thuyết, mẫu code chuẩn và các bẫy thường gặp trong đề thi Lập trình Web với PHP.</p>
    </div>

    <!-- BÀI LUYỆN TẬP 1 -->
    <div class="exercise-card">
        <div class="exercise-header">
            <span class="exercise-title">Bài 1: Xử lý Upload File (Kiểm soát Size, Type & Đổi Tên)</span>
            <span class="badge badge-purple">File Upload</span>
        </div>
        <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 12px;">
            <strong>Yêu cầu đề thi:</strong> Viết đoạn mã tiếp nhận file từ Form HTML, kiểm tra kích thước file không quá 5MB, đuôi file phải là jpg/png/pdf và đổi tên file ngẫu nhiên tránh trùng lặp.
        </p>
        
        <div style="margin-bottom: 12px;">
            <strong style="font-size: 0.85rem; color: var(--accent-green);"> Mẫu Code Frontend (HTML Form Upload):</strong>
            <pre class="code-block"><code>&lt;form action="upload.php" method="POST" enctype="multipart/form-data"&gt;
    &lt;label&gt;Chọn file:&lt;/label&gt;
    &lt;input type="file" name="fileUpload" required&gt;
    &lt;button type="submit"&gt;Tải lên&lt;/button&gt;
&lt;/form&gt;</code></pre>
        </div>

        <div style="margin-bottom: 12px;">
            <strong style="font-size: 0.85rem; color: var(--accent-green);"> Mẫu Code Backend (PHP Core Xử Lý):</strong>
            <pre class="code-block"><code>&lt;?php
// 1. Kiểm tra Form có gửi file không
if (isset($_FILES['fileUpload']) && $_FILES['fileUpload']['error'] === UPLOAD_ERR_OK) {
    $file_size = $_FILES['fileUpload']['size'];
    $file_tmp  = $_FILES['fileUpload']['tmp_name'];
    $file_name = $_FILES['fileUpload']['name'];
    
    // 2. Kiểm soát Dung lượng (Size) - Ví dụ 5MB
    $max_size = 5 * 1024 * 1024;
    if ($file_size > $max_size) {
        die("Lỗi: Kích thước file lớn hơn 5MB!");
    }
    
    // 3. Kiểm soát Định dạng (Type/Extension)
    $allowed_ext = ['jpg', 'png', 'pdf'];
    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed_ext)) {
        die("Lỗi: Định dạng file không hợp lệ!");
    }
    
    // 4. Đổi tên file sau khi upload (Tạo tên độc nhất với uniqid và timestamp)
    $new_name = date('Ymd_His') . '_' . uniqid() . '.' . $ext;
    $target_path = 'uploads/' . $new_name;
    
    // 5. Di chuyển file từ thư mục tạm sang thư mục upload
    if (move_uploaded_file($file_tmp, $target_path)) {
        echo "Upload thành công! File lưu tại: " . $new_name;
    }
}
?&gt;</code></pre>
        </div>

        <div style="background: rgba(251,113,133,0.1); border: 1px solid rgba(251,113,133,0.3); border-radius: var(--radius-sm); padding: 12px; font-size: 0.85rem; color: #fca5a5;">
            ⚠️ <strong>Bẫy cần tránh trong bài thi:</strong> Quên thuộc tính <code>enctype="multipart/form-data"</code> trong Form HTML sẽ khiến <code>$_FILES</code> bị rỗng!
        </div>
    </div>

    <!-- BÀI LUYỆN TẬP 2 -->
    <div class="exercise-card">
        <div class="exercise-header">
            <span class="exercise-title">Bài 2: Tải File An Toàn (Force Download File)</span>
            <span class="badge badge-blue">Download File</span>
        </div>
        <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 12px;">
            <strong>Yêu cầu đề thi:</strong> Viết trang <code>downloadfile.php</code> tiếp nhận tên file qua đường dẫn (GET), kiểm tra file có tồn tại hay không và phát lực tải file về máy thay vì mở trực tiếp trên trình duyệt.
        </p>

        <div style="margin-bottom: 12px;">
            <strong style="font-size: 0.85rem; color: var(--accent-green);"> Mẫu Code PHP Chuẩn:</strong>
            <pre class="code-block"><code>&lt;?php
$filename = $_GET['file'] ?? '';

// Chống lỗi tấn công Path Traversal bằng basename()
$filename = basename($filename);
$filepath = 'uploads/' . $filename;

if (!empty($filename) && file_exists($filepath)) {
    // Cấu hình các Header buộc trình duyệt phải Download
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . filesize($filepath));
    
    // Đọc file gửi tới Client
    readfile($filepath);
    exit;
} else {
    echo "File không tồn tại!";
}
?&gt;</code></pre>
        </div>

        <div style="background: rgba(251,191,36,0.1); border: 1px solid rgba(251,191,36,0.3); border-radius: var(--radius-sm); padding: 12px; font-size: 0.85rem; color: #fde047;">
            💡 <strong>Mẹo chấm điểm cao:</strong> Sử dụng <code>basename()</code> để ngăn chặn đường dẫn dạng <code>../../config.php</code> gây lộ mã nguồn!
        </div>
    </div>

    <!-- BÀI LUYỆN TẬP 3 -->
    <div class="exercise-card">
        <div class="exercise-header">
            <span class="exercise-title">Bài 3: Xóa File Khỏi Server</span>
            <span class="badge badge-amber">Delete File</span>
        </div>
        <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 12px;">
            <strong>Yêu cầu đề thi:</strong> Viết mã xóa file theo tham số truyền vào từ URL, sử dụng hàm <code>unlink()</code>.
        </p>

        <div style="margin-bottom: 12px;">
            <strong style="font-size: 0.85rem; color: var(--accent-green);"> Mẫu Code PHP Chuẩn:</strong>
            <pre class="code-block"><code>&lt;?php
$filename = basename($_GET['file'] ?? '');
$filepath = 'uploads/' . $filename;

if (file_exists($filepath) && is_file($filepath)) {
    if (unlink($filepath)) {
        echo "Xóa file thành công!";
    } else {
        echo "Không thể xóa file!";
    }
} else {
    echo "File không tồn tại!";
}
?&gt;</code></pre>
        </div>
    </div>

    <!-- BÀI LUYỆN TẬP 4 -->
    <div class="exercise-card">
        <div class="exercise-header">
            <span class="exercise-title">Bài 4: Xử Lý Đăng Nhập & Ghi Nhớ Đăng Nhập (Session + Cookie)</span>
            <span class="badge badge-green">Session & Cookie</span>
        </div>
        <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 12px;">
            <strong>Yêu cầu đề thi:</strong> Đăng nhập hệ thống, nếu chọn "Ghi nhớ đăng nhập" thì lưu thông tin vào Cookie với thời hạn 30 ngày. Các trang con phải kiểm tra Session/Cookie trước khi cho phép xem nội dung.
        </p>

        <div style="margin-bottom: 12px;">
            <strong style="font-size: 0.85rem; color: var(--accent-green);"> Mẫu Code Frontend (HTML Form Đăng Nhập):</strong>
            <pre class="code-block"><code>&lt;form action="login.php" method="POST"&gt;
    &lt;label&gt;Tên đăng nhập:&lt;/label&gt;
    &lt;input type="text" name="txtusername" required&gt;
    &lt;br&gt;
    &lt;label&gt;Mật khẩu:&lt;/label&gt;
    &lt;input type="password" name="txtpassword" required&gt;
    &lt;br&gt;
    &lt;label&gt;
        &lt;input type="checkbox" name="remember_me"&gt; Ghi nhớ đăng nhập
    &lt;/label&gt;
    &lt;br&gt;
    &lt;button type="submit"&gt;Đăng nhập&lt;/button&gt;
&lt;/form&gt;</code></pre>
        </div>

        <div style="margin-bottom: 12px;">
            <strong style="font-size: 0.85rem; color: var(--accent-green);"> Mẫu Code Backend (PHP Core Xử Lý Đăng nhập & Lưu Cookie):</strong>
            <pre class="code-block"><code>&lt;?php
session_start();

$user = $_POST['txtusername'] ?? '';
$pass = $_POST['txtpassword'] ?? '';
$remember = isset($_POST['remember_me']);

if ($user === 'admin' && $pass === '123456') {
    // 1. Ghi nhận Session
    $_SESSION['user'] = $user;

    // 2. Ghi nhận Cookie nếu tick chọn "Ghi nhớ đăng nhập"
    if ($remember) {
        setcookie('remember_user', $user, time() + (86400 * 30), "/");
    }

    header('Location: trangchu.php');
    exit;
}
?&gt;</code></pre>
        </div>

        <div style="margin-bottom: 12px;">
            <strong style="font-size: 0.85rem; color: var(--accent-green);"> Mẫu Code PHP Chuẩn (Kiểm Tra Đăng Nhập Trên Trang Chủ):</strong>
            <pre class="code-block"><code>&lt;?php
session_start();

// Kiểm tra Session hoặc Cookie
if (!isset($_SESSION['user'])) {
    if (isset($_COOKIE['remember_user'])) {
        $_SESSION['user'] = $_COOKIE['remember_user'];
    } else {
        // Chưa đăng nhập -> Đẩy về login
        header('Location: index.php');
        exit;
    }
}
?&gt;</code></pre>
        </div>
    </div>

    <!-- BÀI LUYỆN TẬP 5 -->
    <div class="exercise-card">
        <div class="exercise-header">
            <span class="exercise-title">Bài 5: Xử Lý Form Đăng Ký & Checkbox Sở Thích (Mảng)</span>
            <span class="badge badge-purple">Form & Array</span>
        </div>
        <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 12px;">
            <strong>Yêu cầu đề thi:</strong> Thiết kế Form Đăng ký bao gồm: Họ tên, Email, Mật khẩu, và Checkbox Sở thích (Nhiều lựa chọn). Viết mã PHP để nhận dữ liệu, kiểm tra không được để trống thông tin cơ bản, nối mảng Sở thích thành chuỗi để hiển thị hoặc lưu vào CSDL.
        </p>

        <div style="margin-bottom: 12px;">
            <strong style="font-size: 0.85rem; color: var(--accent-green);"> Mẫu Code Frontend (HTML Form Đăng Ký):</strong>
            <pre class="code-block"><code>&lt;form action="register.php" method="POST"&gt;
    &lt;label&gt;Họ và tên:&lt;/label&gt;
    &lt;input type="text" name="fullname" required&gt;
    &lt;br&gt;
    &lt;label&gt;Email:&lt;/label&gt;
    &lt;input type="email" name="email" required&gt;
    &lt;br&gt;
    &lt;label&gt;Mật khẩu:&lt;/label&gt;
    &lt;input type="password" name="password" required&gt;
    &lt;br&gt;
    &lt;label&gt;Sở thích:&lt;/label&gt;
    &lt;!-- Lưu ý name="hobbies[]" có ngoặc vuông để tạo mảng --&gt;
    &lt;label&gt;&lt;input type="checkbox" name="hobbies[]" value="Đọc sách"&gt; Đọc sách&lt;/label&gt;
    &lt;label&gt;&lt;input type="checkbox" name="hobbies[]" value="Nghe nhạc"&gt; Nghe nhạc&lt;/label&gt;
    &lt;label&gt;&lt;input type="checkbox" name="hobbies[]" value="Thể thao"&gt; Thể thao&lt;/label&gt;
    &lt;br&gt;
    &lt;button type="submit" name="btnRegister"&gt;Đăng ký&lt;/button&gt;
&lt;/form&gt;</code></pre>
        </div>

        <div style="margin-bottom: 12px;">
            <strong style="font-size: 0.85rem; color: var(--accent-green);"> Mẫu Code Backend (PHP Core Xử Lý Đăng Ký):</strong>
            <pre class="code-block"><code>&lt;?php
if (isset($_POST['btnRegister'])) {
    // 1. Lấy dữ liệu an toàn
    $fullname = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    // Lấy mảng checkbox (nếu người dùng không chọn gì thì gán mảng rỗng)
    $hobbies  = $_POST['hobbies'] ?? [];

    // 2. Validate cơ bản
    if (empty($fullname) || empty($email) || empty($password)) {
        echo "Lỗi: Vui lòng nhập đầy đủ thông tin bắt buộc!";
    } else {
        // 3. Xử lý dữ liệu mảng Checkbox
        // Hàm implode() chuyển mảng thành chuỗi phân cách bởi dấu phẩy
        $hobbies_string = empty($hobbies) ? 'Không có sở thích' : implode(", ", $hobbies);

        // Hiển thị kết quả (Thực tế sẽ viết câu lệnh INSERT INTO bảng CSDL)
        echo "Đăng ký thành công!&lt;br&gt;";
        echo "Họ tên: " . htmlspecialchars($fullname) . "&lt;br&gt;";
        echo "Email: " . htmlspecialchars($email) . "&lt;br&gt;";
        echo "Sở thích: " . htmlspecialchars($hobbies_string);
    }
}
?&gt;</code></pre>
        </div>

        <div style="background: rgba(251,113,133,0.1); border: 1px solid rgba(251,113,133,0.3); border-radius: var(--radius-sm); padding: 12px; font-size: 0.85rem; color: #fca5a5;">
            ⚠️ <strong>Bẫy cần tránh trong bài thi:</strong> 
            Trường hợp Checkbox, thuộc tính &lt;code&gt;name&lt;/code&gt; bắt buộc phải có dấu ngoặc vuông &lt;code&gt;[]&lt;/code&gt; (VD: &lt;code&gt;name="hobbies[]"&lt;/code&gt;). Nếu quên, PHP sẽ không lấy được mảng mà chỉ lấy được giá trị của thẻ checkbox cuối cùng được check!
        </div>
    </div>

</div>

</body>
</html>
