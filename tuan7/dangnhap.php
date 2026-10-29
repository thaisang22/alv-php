<?php
if (!isset($p)) {
    include("classtmdt/clskhachhang.php");
    $p = new khachhang();
}

$tb_dn = "";
$tb_dk = "";

if (isset($_POST["btn_dangnhap"])) {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if ($email != "" && $password != "") {
        if ($p->dangnhap($email, $password)) {
            if (isset($_GET["yeucau"]) && $_GET["yeucau"] == "dathang") {
                header("Location: index.php?page=giohang");
            } else if (isset($_GET["yeucau"]) && $_GET["yeucau"] == "admin") {
                header("Location: index.php?page=quantri");
            } else {
                if ($p->isAdmin()) {
                    header("Location: index.php?page=quantri");
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

if (isset($_POST["btn_dangky"])) {
    $email = trim($_POST["dk_email"]);
    $password = $_POST["dk_password"];
    $hodem = trim($_POST["dk_hodem"]);
    $ten = trim($_POST["dk_ten"]);
    $diachi = trim($_POST["dk_diachi"]);
    $diachinhanhang = trim($_POST["dk_diachinhanhang"]);
    $dienthoai = trim($_POST["dk_dienthoai"]);

    if ($email != "" && $password != "" && $ten != "") {
        $kq = $p->dangky($email, $password, $hodem, $ten, $diachi, $diachinhanhang, $dienthoai);
        if ($kq === true) {
            $tb_dk = "Đăng ký thành công! Bạn có thể đăng nhập ngay.";
        } else {
            $tb_dk = $kq;
        }
    } else {
        $tb_dk = "Vui lòng nhập các mục bắt buộc (Email, Mật khẩu, Tên)!";
    }
}
?>

<h3>ĐĂNG NHẬP / ĐĂNG KÝ TÀI KHOẢN</h3>

<table width="100%" border="0" cellspacing="10" cellpadding="0">
    <tr valign="top">
        <td width="48%">
            <div>
                <h4>ĐĂNG NHẬP</h4>
                <?php if ($tb_dn != "") { ?>
                    <p><?php echo $tb_dn; ?></p>
                <?php } ?>
                <form method="post" action="">
                    <table width="100%" cellpadding="5">
                        <tr>
                            <td width="100">Email:</td>
                            <td><input type="email" name="email" required /></td>
                        </tr>
                        <tr>
                            <td>Mật khẩu:</td>
                            <td><input type="password" name="password" required /></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type="submit" name="btn_dangnhap" value="Đăng nhập" />
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </td>

        <td width="52%">
            <div>
                <h4>ĐĂNG KÝ TÀI KHOẢN</h4>
                <?php if ($tb_dk != "") { ?>
                    <p><?php echo $tb_dk; ?></p>
                <?php } ?>
                <form method="post" action="">
                    <table width="100%" cellpadding="5">
                        <tr>
                            <td width="120">Email (*):</td>
                            <td><input type="email" name="dk_email" required /></td>
                        </tr>
                        <tr>
                            <td>Mật khẩu (*):</td>
                            <td><input type="password" name="dk_password" required /></td>
                        </tr>
                        <tr>
                            <td>Họ đệm:</td>
                            <td><input type="text" name="dk_hodem" /></td>
                        </tr>
                        <tr>
                            <td>Tên (*):</td>
                            <td><input type="text" name="dk_ten" required /></td>
                        </tr>
                        <tr>
                            <td>Địa chỉ:</td>
                            <td><input type="text" name="dk_diachi" /></td>
                        </tr>
                        <tr>
                            <td>Địa chỉ nhận:</td>
                            <td><input type="text" name="dk_diachinhanhang" /></td>
                        </tr>
                        <tr>
                            <td>Điện thoại:</td>
                            <td><input type="text" name="dk_dienthoai" /></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type="submit" name="btn_dangky" value="Đăng ký tài khoản" />
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </td>
    </tr>
</table>
