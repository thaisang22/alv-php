<?php
require_once 'config.php';

// 1. Xóa toàn bộ Session
$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

// 2. Xóa Cookie ghi nhớ đăng nhập
if (isset($_COOKIE['remember_user'])) {
    setcookie('remember_user', '', time() - 3600, "/");
}

// 3. Chuyển hướng về trang đăng nhập
$msg = urlencode("Đã đăng xuất thành công!");
header("Location: index.php?msg={$msg}");
exit;
