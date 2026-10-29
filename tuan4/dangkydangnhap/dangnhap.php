<?php
session_start();

$error = "";
if (isset($_POST['login'])) {
    $email = $_POST['txtemailloggin'] ?? '';
    $password = $_POST['passwordlogin'] ?? '';

    if (isset($_SESSION['email']) && isset($_SESSION['password'])) {
        if ($email === $_SESSION['email'] && md5($password) === $_SESSION['password']) {
            setcookie('status', 'true', time() + 3600, "/");
            header("Location: trangchu.php");
            exit();
        } else {
            $error = "Thông tin đăng nhập không chính xác!";
        }
    } else {
        $error = "Chưa có tài khoản nào được đăng ký!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
</head>
<body>
    <h2>Đăng nhập</h2>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form action="" method="post">
        <p>Email</p> 
        <input type="text" name="txtemailloggin" required>
        <p>Password</p> 
        <input type="password" name="passwordlogin" required>
        <br><br>
        <button type="submit" name="login">ĐĂNG NHẬP</button>
    </form>
</body>
</html>