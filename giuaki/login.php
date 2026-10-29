<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btndangnhap'])) {
    $username = trim($_POST['txtusername'] ?? '');
    $password = trim($_POST['txtpassword'] ?? '');
    $remember = isset($_POST['remember_me']) ? true : false;

    // Kiểm tra thông tin đăng nhập
    if ($username === VALID_USERNAME && $password === VALID_PASSWORD) {
        // 1. Lưu Session
        $_SESSION['user'] = $username;
        $_SESSION['login_time'] = time();
        $_SESSION['auth_method'] = 'session';

        // 2. Xử lý Ghi nhớ đăng nhập (Cookie)
        if ($remember) {
            // Lưu Cookie trong 30 ngày (86400 * 30 giây)
            // Tham số: name, value, expire, path, domain, secure, httponly
            setcookie('remember_user', $username, time() + (86400 * 30), "/", "", false, true);
        } else {
            // Nếu người dùng không chọn, xóa cookie cũ nếu có
            if (isset($_COOKIE['remember_user'])) {
                setcookie('remember_user', '', time() - 3600, "/");
            }
        }

        // Chuyển hướng đến Trang chủ
        header('Location: trangchu.php');
        exit;
    } else {
        // Đăng nhập thất bại
        $error_msg = urlencode("Tài khoản hoặc mật khẩu không đúng!");
        header("Location: index.php?error={$error_msg}");
        exit;
    }
} else {
    // Nếu truy cập trực tiếp file login.php không qua form POST
    header('Location: index.php');
    exit;
}