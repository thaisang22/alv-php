<?php
require_once 'config.php';

// Nếu đã đăng nhập (hoặc có cookie ghi nhớ hợp lệ), chuyển hướng ngay đến trang chủ
if (checkAuth()) {
    header('Location: trangchu.php');
    exit;
}

$error = $_GET['error'] ?? '';
$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - Ôn Tập Giữa Kỳ PHP</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container login-wrapper">
    <div class="glass-card login-box">
        <div style="text-align: center; margin-bottom: 24px;">
            <div class="brand-icon" style="margin: 0 auto 12px; width: 50px; height: 50px; font-size: 1.5rem;">🔐</div>
            <h2 style="font-size: 1.6rem; font-weight: 700;">Đăng Nhập Hệ Thống</h2>
            <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 4px;">
                Quản lý File & Xử lý Session / Cookie
            </p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                ⚠️ <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($msg)): ?>
            <div class="alert alert-success">
                ✅ <?= htmlspecialchars($msg) ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label class="form-label" for="txtusername">Tên đăng nhập</label>
                <input type="text" id="txtusername" name="txtusername" class="form-input" placeholder="Nhập admin..." required value="admin">
            </div>

            <div class="form-group">
                <label class="form-label" for="txtpassword">Mật khẩu</label>
                <input type="password" id="txtpassword" name="txtpassword" class="form-input" placeholder="Nhập 123456..." required value="123456">
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
                <label class="form-checkbox">
                    <input type="checkbox" name="remember_me" value="1">
                    <span>Ghi nhớ đăng nhập (Sử dụng Cookie)</span>
                </label>
            </div>

            <button type="submit" name="btndangnhap" class="btn btn-primary" style="width: 100%;">
                🚀 Đăng Nhập
            </button>
        </form>

        <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--card-border); font-size: 0.85rem; color: var(--text-muted); text-align: center;">
            💡 <strong>Tài khoản thử nghiệm:</strong> User: <code style="color: var(--accent-purple);">admin</code> | Pass: <code style="color: var(--accent-purple);">123456</code>
        </div>
    </div>
</div>

</body>
</html>