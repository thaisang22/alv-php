<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="login.php" method="post">
        <h1>Đăng nhập</h1>
        <p>username</p>
        <input type="text" name="txtusername">
        <p>password</p>
        <input type="password" name="txtpassword">
        <br>
        ghi nhớ đăng nhập <input type="checkbox" name="remember_user" value="remember_user">
        <br>
        <button type="submit" name="btndangnhap">ĐĂNG NHẬP</button>
    </form>
</body>

</html>