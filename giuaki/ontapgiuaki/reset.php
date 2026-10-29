<?php
session_start();

// Xóa toàn bộ session
$_SESSION = [];
session_destroy();

// Xóa cookie remember
if (isset($_COOKIE['remember'])) {
    setcookie('remember', '', time() - 3600, '/');
}

// Quay lại trang đăng nhập
header('Location: index.php');
exit;
?>
