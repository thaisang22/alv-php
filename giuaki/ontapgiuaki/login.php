<?php
session_start();

$username = $_POST['txtusername'];
$password = $_POST['txtpassword'];
$remember = isset($_POST['remember_user']);

if (isset($_POST['btndangnhap'])) {
    if ($username == 'admin' && $password == '123123Aa') {
        $_SESSION['user'] = $username;
        if ($remember) {
            setcookie('remember', $username, time() + (86400 * 30), '/');
        }
        header('Location: trangchu.php');
        exit;
    } else {
        echo "Tài khoản hoặc mật khẩu không đúng";
    }
} else {
    header("Location: index.php");
    exit;
}


?>