<?php
if (!isset($p)) {
    include("classtmdt/clskhachhang.php");
    $p = new khachhang();
}

$thongbao = "";

if (isset($_REQUEST["them"]) && isset($_REQUEST["idsp"])) {
    $idsp = intval($_REQUEST["idsp"]);
    $soluong = isset($_REQUEST["soluong"]) ? intval($_REQUEST["soluong"]) : 1;
    if ($soluong < 1)
        $soluong = 1;
    $p->ThemVaoGio($idsp, $soluong);
}

if (isset($_POST["capnhat"])) {
    if (isset($_POST["soluong"]) && is_array($_POST["soluong"])) {
        foreach ($_POST["soluong"] as $idsp => $sl) {
            $p->CapNhatGio(intval($idsp), intval($sl));
        }
    }
}

if (isset($_GET["xoa"]) && $_GET["xoa"] != "") {
    $p->XoaKhoiGio(intval($_GET["xoa"]));
}

if (isset($_GET["xoatoanbo"])) {
    $_SESSION["giohang"] = array();
}

if (isset($_POST["btn_dathang"])) {
    if (!isset($_SESSION["khachhang"])) {
        header("Location: index.php?page=dangnhap&yeucau=dathang");
        exit();
    } else {
        $idkh = $_SESSION["khachhang"]["idkh"];
        $iddh = $p->xulydathang($idkh);
        if ($iddh) {
            $thongbao = "Đặt hàng thành công! Mã đơn hàng của bạn là: #" . $iddh;
        } else {
            $thongbao = "Không thể tạo đơn hàng hoặc giỏ hàng trống!";
        }
    }
}

$giohang = $p->XemGioHang();
$tongtien = $p->TongTien();
?>

<h3>QUẢN LÝ GIỎ HÀNG</h3>

<?php if ($thongbao != "") { ?>
    <div>
        <p><strong><?php echo $thongbao; ?></strong></p>
        <p><a href="index.php">&laquo; Tiếp tục mua hàng</a></p>
    </div>
<?php } ?>

<?php if (count($giohang) == 0 && $thongbao == "") { ?>
    <p>Giỏ hàng của bạn đang trống.</p>
    <a href="index.php">&laquo; Quay lại chọn sản phẩm</a>
<?php } else if (count($giohang) > 0) { ?>
        <form method="post" action="index.php?page=giohang">
            <table border="1" cellpadding="6" cellspacing="0" width="100%">
                <tr>
                    <th>Hình</th>
                    <th>Tên sản phẩm</th>
                    <th>Đơn giá (USD)</th>
                    <th>Số lượng</th>
                    <th>Thành tiền (USD)</th>
                    <th>Thao tác</th>
                </tr>
            <?php foreach ($giohang as $idsp => $item) { ?>
                    <tr align="center">
                        <td><img src="images/<?php echo $item["hinh"]; ?>" onerror="this.src='images/a15.jpg'" width="50"
                                height="50" /></td>
                        <td align="left"><?php echo $item["tensp"]; ?></td>
                        <td><?php echo number_format($item["gia"], 0, ".", ","); ?></td>
                        <td>
                            <input type="number" name="soluong[<?php echo $idsp; ?>]" value="<?php echo $item["soluong"]; ?>"
                                min="1" />
                        </td>
                        <td><?php echo number_format($item["gia"] * $item["soluong"], 0, ".", ","); ?></td>
                        <td>
                            <a href="index.php?page=giohang&xoa=<?php echo $idsp; ?>"
                                onclick="return confirm('Bạn muốn xóa sản phẩm này?');">Xóa</a>
                        </td>
                    </tr>
            <?php } ?>
                <tr>
                    <td colspan="4" align="right"><strong>Tổng tiền:</strong></td>
                    <td colspan="2" align="center">
                        <strong><?php echo number_format($tongtien, 0, ".", ","); ?> USD</strong>
                    </td>
                </tr>
            </table>

            <div>
                <br />
                <input type="submit" name="capnhat" value="Cập nhật số lượng" />
                &nbsp;
                <a href="index.php?page=giohang&xoatoanbo=1" onclick="return confirm('Xóa toàn bộ giỏ hàng?');">[Xóa toàn bộ
                    giỏ]</a>
                &nbsp;|&nbsp;
                <a href="index.php">[Tiếp tục mua hàng]</a>
            </div>

            <div>
                <h4>THÔNG TIN ĐẶT HÀNG</h4>
            <?php if (isset($_SESSION["khachhang"])) { ?>
                    <p>Người đặt:
                        <strong><?php echo $_SESSION["khachhang"]["hodem"] . " " . $_SESSION["khachhang"]["ten"]; ?></strong></p>
                    <p>Email: <strong><?php echo $_SESSION["khachhang"]["email"]; ?></strong></p>
                    <p>Điện thoại: <strong><?php echo $_SESSION["khachhang"]["dienthoai"]; ?></strong></p>
                    <p>Địa chỉ giao:
                        <strong><?php echo $_SESSION["khachhang"]["diachinhanhang"] ? $_SESSION["khachhang"]["diachinhanhang"] : $_SESSION["khachhang"]["diachi"]; ?></strong>
                    </p>
                    <input type="submit" name="btn_dathang" value="XÁC NHẬN ĐẶT HÀNG" />
            <?php } else { ?>
                    <p>(*) Bạn cần đăng nhập tài khoản trước khi thực hiện đặt hàng.</p>
                    <input type="submit" name="btn_dathang" value="Đăng nhập để đặt hàng" />
            <?php } ?>
            </div>
        </form>
<?php } ?>