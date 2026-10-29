<?php
$idcty = isset($_REQUEST["idcty"]) ? $_REQUEST["idcty"] : "";
$sanpham = $p->XemDSSanPham($idcty);
?>

<h3>
    DANH SÁCH SẢN PHẨM
    <?php
    if ($idcty != "") {
        for ($k = 0; $k < count($congty); $k++) {
            if ($congty[$k]["idcty"] == $idcty) {
                echo " - " . $congty[$k]["tencty"];
                break;
            }
        }
    }
    ?>
</h3>

<?php if (count($sanpham) == 0) { ?>
    <p>Không có sản phẩm nào thuộc công ty này.</p>
<?php } else { ?>
    <?php for ($i = 0; $i < count($sanpham); $i++) { ?>
        <div class="sp-item">
            <div class="tensp">
                <a href="index.php?page=chitietsanpham&idsp=<?php echo $sanpham[$i]["idsp"]; ?>">
                    <?php echo $sanpham[$i]["tensp"]; ?>
                </a>
            </div>
            <a href="index.php?page=chitietsanpham&idsp=<?php echo $sanpham[$i]["idsp"]; ?>">
                <img src="images/<?php echo $sanpham[$i]["hinh"]; ?>" onerror="this.src='images/a15.jpg'" alt="<?php echo $sanpham[$i]["tensp"]; ?>" />
            </a>
            <div class="gia">Giá: <?php echo number_format($sanpham[$i]["gia"], 0, ".", ","); ?> USD</div>
            <div>
                <a href="index.php?page=chitietsanpham&idsp=<?php echo $sanpham[$i]["idsp"]; ?>">[Chi tiết]</a>
                &nbsp;|&nbsp;
                <a href="index.php?page=giohang&them=1&idsp=<?php echo $sanpham[$i]["idsp"]; ?>">[Đặt hàng]</a>
            </div>
        </div>
    <?php } ?>
<?php } ?>

<div class="clear"></div>
