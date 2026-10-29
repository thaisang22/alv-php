<?php
session_start();
include("classtmdt/clskhachhang.php");

$p = new khachhang();
$thongbao = "";

// Xu ly them san pham vao gio (tu trang chu hoac chi tiet)
if (isset($_REQUEST["them"]) && isset($_REQUEST["idsp"])) {
    $idsp    = $_REQUEST["idsp"];
    $soluong = isset($_REQUEST["soluong"]) ? intval($_REQUEST["soluong"]) : 1;
    if ($soluong < 1) $soluong = 1;
    $p->ThemVaoGio($idsp, $soluong);
}

// Xu ly cap nhat so luong
if (isset($_POST["capnhat"])) {
    if (isset($_POST["soluong"]) && is_array($_POST["soluong"])) {
        foreach ($_POST["soluong"] as $idsp => $sl) {
            $p->CapNhatGio($idsp, intval($sl));
        }
    }
}

// Xu ly xoa san pham khoi gio
if (isset($_GET["xoa"]) && $_GET["xoa"] != "") {
    $p->XoaKhoiGio($_GET["xoa"]);
}

// Xu ly xoa toan bo gio
if (isset($_GET["xoatoanbo"])) {
    $_SESSION["giohang"] = array();
}

// Xu ly DAT HANG (Yeu cau phai dang nhap moi duoc dat hang)
if (isset($_POST["btn_dathang"])) {
    if (!isset($_SESSION["khachhang"])) {
        header("Location: dangnhap.php?yeucau=dathang");
        exit();
    } else {
        $idkh = $_SESSION["khachhang"]["idkh"];
        $iddh = $p->xulydathang($idkh);
        if ($iddh) {
            $thongbao = "Đặt hàng thành công! Mã đơn hàng của bạn là: <strong>#$iddh</strong>.";
        } else {
            $thongbao = "Có lỗi xảy ra khi tạo đơn hàng. Giỏ hàng có thể đang trống!";
        }
    }
}

$giohang = $p->XemGioHang();
$tongtien = $p->TongTien();
$congty = $p->XemDSCongTy();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng</title>
    <link rel="stylesheet" href="style/styletmdt.css">
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
                <li><a href="quantri.php" style="color:#c00; font-weight:bold;">Quản lý sản phẩm</a></li>
                <li><a href="thongke.php" style="color:#0055aa; font-weight:bold;">Biểu đồ thống kê</a></li>
                <li><a href="#">Công ty</a></li>
                <?php for ($i = 0; $i < count($congty); $i++) { ?>
                <li><a href="index.php?idcty=<?php echo $congty[$i]["idcty"]; ?>"><?php echo $congty[$i]["tencty"]; ?></a></li>
                <?php } ?>
            </ul>
            <br>
            <div style="border-top:1px solid #ccc; padding-top:10px;">
                <?php if (isset($_SESSION["khachhang"])) { ?>
                    <p style="font-size:12px; margin-bottom:5px;">
                        Chào, <strong><?php echo $_SESSION["khachhang"]["ten"]; ?></strong>
                        <?php if ($p->isAdmin()) { ?>
                            <span style="background:#c0392b; color:#fff; padding:1px 5px; font-size:10px; border-radius:3px;">Admin</span>
                        <?php } ?>
                    </p>
                    <a href="dangxuat.php" class="btn btn-red" style="font-size:11px; padding:3px 6px;">Đăng xuất</a>
                <?php } else { ?>
                    <a href="dangnhap.php" class="btn" style="font-size:11px; padding:4px 8px;">Đăng nhập / Đăng ký</a>
                <?php } ?>
            </div>
            <br>
            <a href="giohang.php" class="btn">Giỏ hàng (<?php echo count($giohang); ?>)</a>
        </div>

        <!-- Vung gio hang -->
        <div class="content">
            <h2 style="margin-bottom:10px;">QUẢN LÝ GIỎ HÀNG</h2>

            <?php if ($thongbao != "") { ?>
                <div style="background:#d4edda; color:#155724; padding:12px; border:1px solid #c3e6cb; margin-bottom:15px; font-size:14px;">
                    <?php echo $thongbao; ?>
                    <br><br>
                    <a href="index.php" class="btn">Tiếp tục mua hàng</a>
                </div>
            <?php } ?>

            <?php if (count($giohang) == 0 && $thongbao == "") { ?>
            <p>Giỏ hàng đang trống. <a href="index.php">Tiếp tục mua sắm</a></p>

            <?php } else if (count($giohang) > 0) { ?>
            <form method="post" action="giohang.php">
                <table class="giohang-table">
                    <tr>
                        <th>Hình</th>
                        <th>Tên sản phẩm</th>
                        <th>Đơn giá (USD)</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th>Thao tác</th>
                    </tr>
                    <?php foreach ($giohang as $idsp => $item) { ?>
                    <tr>
                        <td><img src="images/<?php echo $item["hinh"]; ?>" onerror="this.src='https://via.placeholder.com/60'" /></td>
                        <td><?php echo $item["tensp"]; ?></td>
                        <td><?php echo number_format($item["gia"], 0, ".", ","); ?></td>
                        <td>
                            <input type="number" name="soluong[<?php echo $idsp; ?>]"
                                   value="<?php echo $item["soluong"]; ?>" min="1" />
                        </td>
                        <td><?php echo number_format($item["gia"] * $item["soluong"], 0, ".", ","); ?></td>
                        <td>
                            <a href="giohang.php?xoa=<?php echo $idsp; ?>" class="btn btn-red"
                               onclick="return confirm('Xóa sản phẩm này?')">Xóa</a>
                        </td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="4" style="text-align:right;font-weight:bold;">Tổng tiền:</td>
                        <td colspan="2" style="font-weight:bold;color:#c00;">
                            <?php echo number_format($tongtien, 0, ".", ","); ?> USD
                        </td>
                    </tr>
                </table>

                <div style="margin-top:15px;">
                    <input type="submit" name="capnhat" value="Cập nhật số lượng" class="btn btn-green">
                    &nbsp;&nbsp;
                    <a href="giohang.php?xoatoanbo=1" class="btn btn-red"
                       onclick="return confirm('Xóa toàn bộ giỏ hàng?')">Xóa toàn bộ giỏ</a>
                    &nbsp;&nbsp;
                    <a href="index.php" class="btn">Tiếp tục mua sắm</a>
                </div>

                <div style="margin-top:20px; padding:15px; background:#f8f9fa; border:1px solid #ddd;">
                    <h3 style="margin-bottom:8px; font-size:15px; color:#333;">XÁC NHẬN ĐẶT HÀNG</h3>
                    <?php if (isset($_SESSION["khachhang"])) { ?>
                        <p style="font-size:13px; margin-bottom:8px;">
                            Khách hàng: <strong><?php echo $_SESSION["khachhang"]["hodem"] . " " . $_SESSION["khachhang"]["ten"]; ?></strong> (<?php echo $_SESSION["khachhang"]["email"]; ?>)
                        </p>
                        <p style="font-size:13px; margin-bottom:12px;">
                            Địa chỉ nhận hàng: <strong><?php echo $_SESSION["khachhang"]["diachinhanhang"] ? $_SESSION["khachhang"]["diachinhanhang"] : $_SESSION["khachhang"]["diachi"]; ?></strong>
                        </p>
                        <input type="submit" name="btn_dathang" value="XÁC NHẬN ĐẶT HÀNG NGAY" class="btn" style="padding:8px 16px; font-size:14px; background:#e67e22;">
                    <?php } else { ?>
                        <p style="color:#d9534f; font-size:13px; margin-bottom:10px;">
                            (*) Bạn chưa đăng nhập. Bạn phải đăng nhập tài khoản trước khi đặt hàng!
                        </p>
                        <input type="submit" name="btn_dathang" value="Đăng nhập để đặt hàng" class="btn" style="padding:8px 16px; font-size:14px; background:#336699;">
                    <?php } ?>
                </div>
            </form>
            <?php } ?>
        </div>

    </div>

    <div class="footer">Footer website</div>
</div>
</body>
</html>
