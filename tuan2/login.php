<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <h1 style="background:black;width:1200px;color:white;">
        BANNER WEBSITE
    </h1>

    <?php
    if (isset($_POST["sbdangnhap"])) {

        if (
            $_POST["txtemail"] == "admin" &&
            $_POST["txtpassword"] == "123Aa"
        ) {
            echo "Chúc mừng đăng nhập thành công!";
        } 
        else {
            echo "Đăng nhập thất bại";
        }
    }

    ?>

    <form action="" method="post">

        <h1>Tài khoản</h1>
        <input name="txtemail" type="text">
        <h1>Mật khẩu</h1>
        <input name="txtpassword" type="password">

        <br>
        <button name="sbdangnhap" value="Đăng nhập">
            Đăng nhập
        </button>

    </form>

</body>

</html>