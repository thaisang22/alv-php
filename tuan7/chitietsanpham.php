<?php
if (!isset($p)) {
    include("classtmdt/clskhachhang.php");
    $p = new khachhang();
}

$idsp = isset($_REQUEST["idsp"]) ? intval($_REQUEST["idsp"]) : 0;
$sp = null;
if ($idsp > 0) {
    $sp = $p->chitietsanpham($idsp);
}
?>

<h3>CHI TIẾT SẢN PHẨM</h3>

<?php if ($sp) { ?>
    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <tr valign="top">
            <td width="250" align="center">
                <img src="images/<?php echo $sp["hinh"]; ?>" onerror="this.src='images/a15.jpg'" width="220" height="220" alt="<?php echo $sp["tensp"]; ?>" />
            </td>
            <td>
                <h2><?php echo $sp["tensp"]; ?></h2>
                <p><strong>Công ty:</strong> <?php echo isset($sp["tencty"]) && !empty($sp["tencty"]) ? $sp["tencty"] : "Chưa cập nhật"; ?></p>
                <p><strong>Mô tả:</strong> <?php echo $sp["mota"]; ?></p>
                <p><strong>Đơn giá:</strong> <?php echo number_format($sp["gia"], 0, ".", ","); ?> USD</p>

                <form method="post" action="index.php?page=giohang">
                    <input type="hidden" name="idsp" value="<?php echo $sp["idsp"]; ?>" />
                    <p>
                        <strong>Số lượng mua:</strong>
                        <input type="number" name="soluong" value="1" min="1" />
                    </p>
                    <p>
                        <input type="submit" name="them" value="Thêm vào giỏ hàng" />
                    </p>
                </form>
                <br />
                <a href="index.php">&laquo; Quay lại danh sách sản phẩm</a>
            </td>
        </tr>
    </table>
<?php } else { ?>
    <p>Không tìm thấy sản phẩm yêu cầu!</p>
    <a href="index.php">&laquo; Quay lại danh sách</a>
<?php } ?>
