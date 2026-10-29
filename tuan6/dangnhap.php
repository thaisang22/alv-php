<?php
session_start();
include("classtmdt/clskhachhang.php");

$p = new khachhang();
$tb_dn = "";
$tb_dk = "";
if (isset($_POST["btn_dangnhap"])) {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if ($email != "" && $password != "") {
        if ($p->dangnhap($email, $password)) {
            if (isset($_GET["yeucau"]) && $_GET["yeucau"] == "dathang") {
                header("Location: giohang.php");
            } else if (isset($_GET["yeucau"]) && $_GET["yeucau"] == "admin") {
                header("Location: quantri.php");
            } else {
                if ($p->isAdmin()) {
                    header("Location: quantri.php");
                } else {
                    header("Location: index.php");
                }
            }
            exit();
        } else {
            $tb_dn = "Sai email hoặc mật khẩu!";
        }
    } else {
        $tb_dn = "Vui lòng nhập đầy đủ thông tin!";
    }
}

// Xu ly dang ky
if (isset($_POST["btn_dangky"])) {
    $email         = trim($_POST["dk_email"]);
    $password      = $_POST["dk_password"];
    $hodem         = trim($_POST["dk_hodem"]);
    $ten           = trim($_POST["dk_ten"]);
    $diachi        = trim($_POST["dk_diachi"]);
    $diachinhanhang = trim($_POST["dk_diachinhanhang"]);
    $dienthoai     = trim($_POST["dk_dienthoai"]);

    if ($email != "" && $password != "" && $ten != "") {
        $kq = $p->dangky($email, $password, $hodem, $ten, $diachi, $diachinhanhang, $dienthoai);
        if ($kq === true) {
            $tb_dk = "Đăng ký thành công! Bạn có thể đăng nhập ngay.";
        } else {
            $tb_dk = $kq;
        }
    } else {
        $tb_dk = "Vui lòng điền các trường bắt buộc (Email, Mật khẩu, Tên)!";
    }
}

$congty = $p->XemDSCongTy();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập & Đăng ký</title>
    <link rel="stylesheet" href="style/styletmdt.css">
    <style>
        .auth-container {
            display: flex;
            gap: 20px;
            margin-top: 10px;
        }
        .auth-box {
            flex: 1;
            border: 1px solid #ccc;
            padding: 15px;
            background: #fff;
        }
        .auth-box h3 {
            margin-bottom: 12px;
            padding-bottom: 6px;
            border-bottom: 2px solid #336699;
            color: #336699;
        }
        .auth-box .form-row {
            margin-bottom: 10px;
        }
        .auth-box label {
            display: block;
            margin-bottom: 3px;
            font-size: 13px;
            font-weight: bold;
        }
        .auth-box input[type="text"],
        .auth-box input[type="password"],
        .auth-box input[type="email"] {
            width: 100%;
            padding: 6px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            font-size: 13px;
        }
        .thongbao-loi {
            color: #d9534f;
            margin-bottom: 10px;
            font-size: 13px;
            font-weight: bold;
        }
        .thongbao-tc {
            color: #28a745;
            margin-bottom: 10px;
            font-size: 13px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="wrapper">

    <div class="banner">BANNER WEBSITE</div>

    <div class="main-container">

        <!-- Menu trai -->
        <div class="menu">
            <h3>MENU</h3>
            <ul>
                <li><a href="index.php">Trang chủ</a></li>
                <li><a href="#">Công ty</a></li>
                <?php for ($i = 0; $i < count($congty); $i++) { ?>
                <li><a href="index.php?idcty=<?php echo $congty[$i]["idcty"]; ?>"><?php echo $congty[$i]["tencty"]; ?></a></li>
                <?php } ?>
            </ul>
            <br>
            <a href="giohang.php" class="btn">Giỏ hàng (<?php echo isset($_SESSION["giohang"]) ? count($_SESSION["giohang"]) : 0; ?>)</a>
        </div>

        <div class="content">
            <?php if (isset($_GET["yeucau"]) && $_GET["yeucau"] == "dathang") { ?>
                <div class="thongbao-loi" style="background:#fff3cd; padding:8px; border:1px solid #ffeeba;">
                    (*) Bạn cần phải đăng nhập tài khoản để thực hiện đặt hàng!
                </div>
            <?php } else if (isset($_GET["yeucau"]) && $_GET["yeucau"] == "admin") { ?>
                <div class="thongbao-loi" style="background:#fff3cd; padding:10px; border:1px solid #ffeeba; color:#856404;">
                    (*) Khu vực dành riêng cho <strong>Quản trị viên</strong>! Vui lòng đăng nhập tài khoản Admin (ví dụ: <code>admin@gmail.com</code> / mk: <code>admin123</code>).
                </div>
            <?php } ?>

            <div class="auth-container">
                <div class="auth-box">
                    <h3>ĐĂNG NHẬP</h3>
                    <?php if ($tb_dn != "") { echo '<div class="thongbao-loi">'.$tb_dn.'</div>'; } ?>
                    <form method="post" action="">
                        <div class="form-row">
                            <label>Email (Tài khoản):</label>
                            <input type="email" name="email" required>
                        </div>
                        <div class="form-row">
                            <label>Mật khẩu:</label>
                            <input type="password" name="password" required>
                        </div>
                        <div style="margin-top:15px;">
                            <input type="submit" name="btn_dangnhap" value="Đăng nhập" class="btn">
                        </div>
                    </form>
                </div>

                <!-- FORM DANG KY -->
                <div class="auth-box">
                    <h3>ĐĂNG KÝ TÀI KHOẢN</h3>
                    <?php 
                    if ($tb_dk != "") { 
                        if ($tb_dk == "Đăng ký thành công! Bạn có thể đăng nhập ngay.") {
                            echo '<div class="thongbao-tc">'.$tb_dk.'</div>'; 
                        } else {
                            echo '<div class="thongbao-loi">'.$tb_dk.'</div>'; 
                        }
                    } 
                    ?>
                    <form method="post" action="">
                        <div class="form-row">
                            <label>Email (*):</label>
                            <input type="email" name="dk_email" required>
                        </div>
                        <div class="form-row">
                            <label>Mật khẩu (MD5 mã hóa) (*):</label>
                            <input type="password" name="dk_password" required>
                        </div>
                        <div class="form-row">
                            <label>Họ đệm:</label>
                            <input type="text" name="dk_hodem">
                        </div>
                        <div class="form-row">
                            <label>Tên (*):</label>
                            <input type="text" name="dk_ten" required>
                        </div>
                        <div class="form-row">
                            <label>Địa chỉ:</label>
                            <input type="text" name="dk_diachi">
                        </div>
                        <div class="form-row">
                            <label>Địa chỉ nhận hàng:</label>
                            <input type="text" name="dk_diachinhanhang">
                        </div>
                        <div class="form-row">
                            <label>Điện thoại:</label>
                            <input type="text" name="dk_dienthoai">
                        </div>
                        <div style="margin-top:15px;">
                            <input type="submit" name="btn_dangky" value="Đăng ký" class="btn btn-green">
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <div class="footer">Footer website</div>
</div>
</body>
</html>
