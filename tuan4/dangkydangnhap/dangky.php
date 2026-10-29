<?php
session_start();

if (isset($_POST['dangky'])) {  
    $_SESSION['email'] = $_POST['txtemail'];
    $_SESSION['password'] = md5($_POST['txtpassword']);
    $_SESSION['username'] = $_POST['txtusername'];
    $_SESSION['infor'] = $_POST['txtinfor'];
    $_SESSION['fav'] = $_POST['txtfav'];
    
    setcookie('status', 'false', time() + 3600, "/");
    
    header("Location: dangnhap.php");
    exit();
} 
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký</title>
</head>
<body>
    <h2>Đăng ký</h2>
    <form action="" method="post">
        <p>Email</p>
        <input type="email" name="txtemail" required>
        <p>Password</p>
        <input type="password" name="txtpassword" required>
        <p>Username</p>
        <input type="text" name="txtusername" required>
        <p>Thông tin cá nhân</p>
        <input type="text" name="txtinfor">
        <p>Sở thích</p>
        <input type="text" name="txtfav">
        <br><br>
        <button type="submit" name="dangky">Đăng ký</button>
    </form>
</body>
</html>