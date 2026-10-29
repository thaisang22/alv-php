<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form thông tin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $trypassword = $_POST['trypassword'] ?? '';
        $fullname = $_POST['fullname'] ?? '';
        $quequan = $_POST['quequan'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $gioitinh = $_POST['gioitinh'] ?? '';
        $sothich = $_POST['sothich'] ?? [];
        $mauyeuthich = $_POST['mauyeuthich'] ?? '';
        echo "Username: " . $username . "<br>";
        echo "Email: " . $email . "<br>";
        echo "Password: " . $password . "<br>";
        echo "Nhập lại password: " . $trypassword . "<br>";
        echo "Họ tên: " . $fullname . "<br>";
        echo "Quê quán: " . $quequan . "<br>";
        echo "Số điện thoại: " . $phone . "<br>";
        echo "Giới tính: " . $gioitinh . "<br>";

        echo "Sở thích: ";
        echo "<br>";
        echo "Màu yêu thích: " . $mauyeuthich. "<br>";
    }
    ?>


    <div class="container">

        <div class="header">
            <h1>ĐĂNG KÝ THÔNG TIN</h1>
        </div>

        <div class="main">

            <div class="nav">
                <h3>MENU</h3>
                <p>Trang chủ</p>
                <p>Đăng ký</p>
                <p>Thông tin</p>
            </div>

            <div class="content">

                <form action="" method="post">

                    <div class="form-group">
                        <label>Username:</label>
                        <input type="text" name="username">
                    </div>

                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" name="email">
                    </div>

                    <div class="form-group">
                        <label>Password:</label>
                        <input type="password" name="password">
                    </div>

                    <div class="form-group">
                        <label>Nhập lại password:</label>
                        <input type="password" name="trypassword">
                    </div>

                    <div class="form-group">
                        <label>Họ tên:</label>
                        <input type="text" name="fullname">
                    </div>

                    <div class="form-group">
                        <label>Quê quán:</label>

                        <select name="quequan">
                            <option value="Hà Nội">Hà Nội</option>
                            <option value="Hồ Chí Minh">Hồ Chí Minh</option>
                            <option value="Đà Nẵng">Đà Nẵng</option>
                            <option value="Cần Thơ">Cần Thơ</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Số điện thoại:</label>
                        <input type="number" name="phone">
                    </div>

                    <div class="form-group">
                        <label>Giới tính:</label>

                        <input type="radio" name="gioitinh" value="Nam">
                        Nam

                        <input type="radio" name="gioitinh" value="Nữ">
                        Nữ
                    </div>

                    <div class="form-group">
                        <label>Sở thích:</label>

                        <input type="checkbox" name="sothich[]" value="Đọc sách">
                        Đọc sách

                        <input type="checkbox" name="sothich[]" value="Chơi game">
                        Chơi game

                        <input type="checkbox" name="sothich[]" value="Nghe nhạc">
                        Nghe nhạc
                    </div>

                    <div class="form-group">
                        <label>Màu yêu thích:</label>

                        <input type="radio" name="mauyeuthich" value="Xanh">
                        Xanh

                        <input type="radio" name="mauyeuthich" value="Đỏ">
                        Đỏ
                    </div>

                    <button type="submit">
                        Xuất thông tin
                    </button>

                </form>

            </div>

        </div>

        <div class="footer">
            Footer
        </div>

    </div>

</body>

</html>