<?php
if (!isset($p)) {
    include("classtmdt/clskhachhang.php");
    $p = new khachhang();
}

if (!$p->isAdmin()) {
    header("Location: index.php");
    echo "<script>window.location.href='index.php';</script>";
    exit();
}

$thongbao = "";

if (isset($_GET["action"]) && $_GET["action"] == "doitrangthai" && isset($_GET["iddh"]) && isset($_GET["tt"])) {
    $iddh_doi = intval($_GET["iddh"]);
    $tt_moi   = intval($_GET["tt"]);
    if ($p->DoiTrangThaiDonHang($iddh_doi, $tt_moi)) {
        $thongbao = "Cập nhật trạng thái đơn hàng #$iddh_doi thành công!";
    } else {
        $thongbao = "Lỗi khi cập nhật trạng thái đơn hàng!";
    }
}

$loc_trangthai = isset($_GET["trangthai"]) ? $_GET["trangthai"] : "";
$ds_donhang = $p->XemDSDatHang($loc_trangthai);
?>

<h3>QUẢN LÝ BÁN HÀNG - DANH SÁCH ĐƠN ĐẶT HÀNG</h3>

<?php if ($thongbao != "") { ?>
    <p><strong><?php echo $thongbao; ?></strong></p>
<?php } ?>

<div>
    <form method="get" action="index.php">
        <input type="hidden" name="page" value="quanlybanhang" />
        <strong>Lọc theo trạng thái đơn hàng:</strong>
        <select name="trangthai" onchange="this.form.submit()">
            <option value="" <?php if ($loc_trangthai === "") echo 'selected="selected"'; ?>>-- Tất cả đơn hàng --</option>
            <option value="0" <?php if ($loc_trangthai === "0") echo 'selected="selected"'; ?>>Chờ xử lý / Chờ giao</option>
            <option value="1" <?php if ($loc_trangthai === "1") echo 'selected="selected"'; ?>>Đã giao hàng</option>
        </select>
        <input type="submit" value="Lọc" />
        &nbsp;&nbsp;
        <a href="index.php?page=quanlybanhang">[Xem tất cả]</a>
    </form>
</div>
<br />

<?php if (count($ds_donhang) == 0) { ?>
    <p>Không có đơn hàng nào phù hợp với điều kiện lọc.</p>
<?php } else { ?>
    <table border="1" cellpadding="6" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th width="40">Mã ĐH</th>
                <th width="150">Người đặt</th>
                <th width="120">Ngày đặt hàng</th>
                <th>Chi tiết sản phẩm đặt</th>
                <th width="100">Trạng thái</th>
                <th width="120">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            for ($i = 0; $i < count($ds_donhang); $i++) {
                $dh = $ds_donhang[$i];
                $ct = $p->XemChiTietDonHang($dh["iddh"]);
                $tongtiendh = 0;
            ?>
            <tr valign="top">
                <td align="center"><strong>#<?php echo $dh["iddh"]; ?></strong></td>
                <td>
                    <strong><?php echo $dh["hodem"] . " " . $dh["ten"]; ?></strong><br />
                    Email: <?php echo $dh["email"]; ?><br />
                    ĐT: <?php echo $dh["dienthoai"]; ?><br />
                    Đ/c: <?php echo $dh["diachinhanhang"] ? $dh["diachinhanhang"] : $dh["diachi"]; ?>
                </td>
                <td align="center"><?php echo $dh["ngaydathang"]; ?></td>
                <td>
                    <table border="1" cellpadding="4" cellspacing="0" width="100%">
                        <tr>
                            <th>Tên sản phẩm</th>
                            <th width="60">Đơn giá</th>
                            <th width="35">SL</th>
                            <th width="70">Thành tiền</th>
                        </tr>
                        <?php 
                        for ($j = 0; $j < count($ct); $j++) { 
                            $tongtiendh += $ct[$j]["thanhtien"];
                        ?>
                        <tr>
                            <td><?php echo $ct[$j]["tensp"]; ?></td>
                            <td align="center"><?php echo number_format($ct[$j]["gia"], 0, ".", ","); ?></td>
                            <td align="center"><?php echo $ct[$j]["soluong"]; ?></td>
                            <td align="right"><?php echo number_format($ct[$j]["thanhtien"], 0, ".", ","); ?></td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="3" align="right"><strong>Tổng cộng:</strong></td>
                            <td align="right"><strong><?php echo number_format($tongtiendh, 0, ".", ","); ?> USD</strong></td>
                        </tr>
                    </table>
                </td>
                <td align="center">
                    <?php if ($dh["trangthai"] == 1) { ?>
                        <span>Đã giao</span>
                    <?php } else { ?>
                        <span>Chờ giao</span>
                    <?php } ?>
                </td>
                <td align="center">
                    <?php if ($dh["trangthai"] == 1) { ?>
                        <a href="index.php?page=quanlybanhang&action=doitrangthai&iddh=<?php echo $dh["iddh"]; ?>&tt=0<?php echo ($loc_trangthai !== '') ? '&trangthai='.$loc_trangthai : ''; ?>" 
                           onclick="return confirm('Chuyển trạng thái đơn #<?php echo $dh["iddh"]; ?> sang CHỜ GIAO?');">
                            [Chuyển Chờ giao]
                        </a>
                    <?php } else { ?>
                        <a href="index.php?page=quanlybanhang&action=doitrangthai&iddh=<?php echo $dh["iddh"]; ?>&tt=1<?php echo ($loc_trangthai !== '') ? '&trangthai='.$loc_trangthai : ''; ?>" 
                           onclick="return confirm('Chuyển trạng thái đơn #<?php echo $dh["iddh"]; ?> sang ĐÃ GIAO?');">
                            [Chuyển Đã giao]
                        </a>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } ?>
