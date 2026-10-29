<?php
require_once 'config.php';

// Kiểm tra nếu chưa đăng nhập thì đẩy về trang login
if (!checkAuth()) {
    header('Location: index.php?error=' . urlencode('Bạn cần đăng nhập để truy cập trang chủ!'));
    exit;
}

$error = $_GET['error'] ?? '';
$msg   = $_GET['msg'] ?? '';

// Đọc thông tin file uploads & metadata
$meta_file = UPLOAD_DIR . 'files_meta.json';
$files_meta = file_exists($meta_file) ? json_decode(file_get_contents($meta_file), true) : [];
if (!is_array($files_meta)) $files_meta = [];

// Quét thư mục uploads để liệt kê tất cả các file thực tế
$all_files = array_diff(scandir(UPLOAD_DIR), array('.', '..', 'files_meta.json', '.htaccess'));
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ - Quản Lý File & Session/Cookie</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <div class="container nav-wrapper">
        <a href="trangchu.php" class="brand">
            <div class="brand-icon">📁</div>
            <span>Hệ Thống Ôn Tập Giữa Kỳ</span>
        </a>
        <ul class="nav-links">
            <li><a href="trangchu.php" class="active">Trang Chủ</a></li>
            <li><a href="luyentap.php">📚 Bài Luyện Tập & Tổng hợp</a></li>
            <li>
                <div class="user-badge">
                    <div class="avatar"><?= strtoupper(substr($_SESSION['user'] ?? 'U', 0, 1)) ?></div>
                    <span style="font-size: 0.9rem; font-weight: 600; color: #fff;"><?= htmlspecialchars($_SESSION['user'] ?? 'User') ?></span>
                </div>
            </li>
            <li>
                <a href="logout.php" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất?')">
                    🚪 Đăng Xuất
                </a>
            </li>
        </ul>
    </div>
</nav>

<div class="container" style="margin-top: 30px;">

    <!-- Thông báo lỗi / thành công -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-error">
            ❌ <?= $error ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($msg)): ?>
        <div class="alert alert-success">
            ✅ <?= $msg ?>
        </div>
    <?php endif; ?>

    <!-- Panel Theo Dõi Người Dùng (Session & Cookie Inspector) -->
    <div class="glass-card" style="margin-bottom: 28px;">
        <div class="card-header">
            <h2 class="card-title">
                <span>🕵️ Theo Dõi Trạng Thái Đăng Nhập (Session & Cookie)</span>
            </h2>
            <span class="badge badge-purple">Live Inspector</span>
        </div>
        <div class="grid-3" style="margin-top: 0;">
            <div style="background: rgba(15,23,42,0.6); padding: 16px; border-radius: var(--radius-sm); border: 1px solid var(--card-border);">
                <div style="color: var(--text-secondary); font-size: 0.85rem; font-weight: 600;">TÀI KHOẢN PHIÊN PHIÊN</div>
                <div style="font-size: 1.1rem; font-weight: 700; color: var(--accent-blue); margin-top: 4px;">
                    <?= htmlspecialchars($_SESSION['user'] ?? 'Chưa xác định') ?>
                </div>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 4px;">
                    Session ID: <code><?= session_id() ?></code>
                </div>
            </div>

            <div style="background: rgba(15,23,42,0.6); padding: 16px; border-radius: var(--radius-sm); border: 1px solid var(--card-border);">
                <div style="color: var(--text-secondary); font-size: 0.85rem; font-weight: 600;">GHI NHỚ ĐĂNG NHẬP (COOKIE)</div>
                <div style="font-size: 1.1rem; font-weight: 700; margin-top: 4px;">
                    <?php if (isset($_COOKIE['remember_user'])): ?>
                        <span style="color: var(--accent-green);">Đang hoạt động (<?= htmlspecialchars($_COOKIE['remember_user']) ?>)</span>
                    <?php else: ?>
                        <span style="color: var(--text-muted);">Không kích hoạt</span>
                    <?php endif; ?>
                </div>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 4px;">
                    Cookie Key: <code>remember_user</code>
                </div>
            </div>

            <div style="background: rgba(15,23,42,0.6); padding: 16px; border-radius: var(--radius-sm); border: 1px solid var(--card-border);">
                <div style="color: var(--text-secondary); font-size: 0.85rem; font-weight: 600;">THỜI GIAN ĐĂNG NHẬP</div>
                <div style="font-size: 1.1rem; font-weight: 700; color: var(--accent-amber); margin-top: 4px;">
                    <?= isset($_SESSION['login_time']) ? date('H:i:s - d/m/Y', $_SESSION['login_time']) : 'N/A' ?>
                </div>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 4px;">
                    Phương thức: <code><?= htmlspecialchars($_SESSION['auth_method'] ?? 'Session') ?></code>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content: Upload Form + Files Table -->
    <div class="grid-2">
        
        <!-- Form Upload File -->
        <div class="glass-card">
            <div class="card-header">
                <h2 class="card-title">📤 Upload File Mới</h2>
            </div>
            <form action="uploadfile.php" method="POST" enctype="multipart/form-data" id="uploadForm">
                <div class="upload-zone" onclick="document.getElementById('fileInput').click()">
                    <div class="upload-icon">☁️</div>
                    <div style="font-weight: 600; font-size: 1.05rem;">Nhấp vào đây để chọn file</div>
                    <div style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 6px;" id="fileChosenText">
                        Kéo thả file hoặc click để duyệt từ máy tính
                    </div>
                    <input type="file" name="fileUpload" id="fileInput" style="display: none;" onchange="updateFileName(this)">
                </div>

                <div style="margin-top: 16px; font-size: 0.85rem; color: var(--text-muted); line-height: 1.5;">
                    📌 <strong>Quy định Upload:</strong><br>
                    • Dung lượng tối đa: <span style="color: var(--accent-amber);">5 MB</span><br>
                    • Định dạng cho phép: <span style="color: var(--accent-blue);">jpg, png, gif, webp, pdf, txt, docx, zip</span><br>
                    • File sẽ tự động được <span style="color: var(--accent-purple);">đổi tên duy nhất</span> để bảo mật.
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 20px;">
                    ⬆️ Tiến Hành Upload File
                </button>
            </form>
        </div>

        <!-- Thống kê & Hướng dẫn nhanh -->
        <div class="glass-card">
            <div class="card-header">
                <h2 class="card-title">💡 Hướng Dẫn Kỹ Thuật Đã Áp Dụng</h2>
            </div>
            <div style="font-size: 0.9rem; color: var(--text-secondary); display: flex; flex-direction: column; gap: 14px;">
                <div style="background: rgba(255,255,255,0.03); padding: 12px; border-radius: var(--radius-sm); border-left: 3px solid var(--accent-purple);">
                    <strong style="color: #fff;">1. Kiểm soát Upload:</strong> Sử dụng <code>$_FILES</code>, kiểm tra <code>error</code>, <code>size</code> và đuôi file bằng <code>pathinfo()</code>.
                </div>
                <div style="background: rgba(255,255,255,0.03); padding: 12px; border-radius: var(--radius-sm); border-left: 3px solid var(--accent-blue);">
                    <strong style="color: #fff;">2. Đổi Tên Tự Động:</strong> Sử dụng hàm <code>uniqid()</code> kết hợp với timestamp <code>date('Ymd_His')</code> và <code>md5()</code> để tạo tên file duy nhất chống đè file.
                </div>
                <div style="background: rgba(255,255,255,0.03); padding: 12px; border-radius: var(--radius-sm); border-left: 3px solid var(--accent-green);">
                    <strong style="color: #fff;">3. Download An Toàn:</strong> Sử dụng PHP Headers (<code>Content-Disposition: attachment</code>) tránh lộ đường dẫn file tuyệt đối.
                </div>
                <div style="background: rgba(255,255,255,0.03); padding: 12px; border-radius: var(--radius-sm); border-left: 3px solid var(--accent-rose);">
                    <strong style="color: #fff;">4. Xóa File An Toàn:</strong> Dùng <code>unlink()</code> kết hợp với <code>basename()</code> để chặn tấn công <em>Directory Traversal (../../)</em>.
                </div>
            </div>
        </div>

    </div>

    <!-- Danh Sách File Đã Upload -->
    <div class="glass-card" style="margin-top: 28px;">
        <div class="card-header">
            <h2 class="card-title">
                <span>📑 Danh Sách File Trên Server</span>
                <span class="badge badge-blue"><?= count($all_files) ?> File</span>
            </h2>
        </div>

        <?php if (empty($all_files)): ?>
            <div style="text-align: center; padding: 40px; color: var(--text-muted);">
                📂 Chưa có file nào được upload. Hãy chọn file phía trên để bắt đầu thử nghiệm!
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Loại File</th>
                            <th>Tên File Gốc</th>
                            <th>Tên Đã Đổi Trên Server</th>
                            <th>Dung Lượng</th>
                            <th>Thời Gian Upload</th>
                            <th style="text-align: right;">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_files as $f): 
                            $filepath = UPLOAD_DIR . $f;
                            $meta = $files_meta[$f] ?? [];
                            $orig_name = $meta['original_name'] ?? $f;
                            $f_size = isset($meta['formatted_size']) ? $meta['formatted_size'] : formatFileSize(filesize($filepath));
                            $f_time = $meta['upload_time'] ?? date('H:i:s d/m/Y', filemtime($filepath));
                            $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));

                            $icon = '📄';
                            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) $icon = '🖼️';
                            elseif ($ext === 'pdf') $icon = '📕';
                            elseif ($ext === 'txt') $icon = '📝';
                            elseif ($ext === 'zip') $icon = '📦';
                            elseif (in_array($ext, ['doc', 'docx'])) $icon = '📘';
                        ?>
                        <tr>
                            <td style="font-size: 1.4rem; width: 60px; text-align: center;"><?= $icon ?></td>
                            <td>
                                <strong><?= htmlspecialchars($orig_name) ?></strong>
                                <br><span style="font-size: 0.75rem; color: var(--text-muted);">Format: .<?= $ext ?></span>
                            </td>
                            <td><code style="color: var(--accent-purple); font-size: 0.85rem;"><?= htmlspecialchars($f) ?></code></td>
                            <td><span class="badge badge-amber"><?= $f_size ?></span></td>
                            <td style="color: var(--text-secondary); font-size: 0.85rem;"><?= $f_time ?></td>
                            <td style="text-align: right;">
                                <a href="downloadfile.php?file=<?= urlencode($f) ?>" class="btn btn-secondary btn-sm" title="Tải xuống file">
                                    ⬇️ Download
                                </a>
                                <a href="deletefile.php?file=<?= urlencode($f) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa file <?= htmlspecialchars($orig_name) ?> này không?')" title="Xóa file">
                                    🗑️ Xóa
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

</div>

<script>
function updateFileName(input) {
    const textElem = document.getElementById('fileChosenText');
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const sizeFormatted = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
        textElem.innerHTML = `<strong style="color: var(--accent-green)">Đã chọn: ${file.name}</strong> (${sizeFormatted})`;
    } else {
        textElem.innerHTML = 'Kéo thả file hoặc click để duyệt từ máy tính';
    }
}
</script>

</body>
</html>