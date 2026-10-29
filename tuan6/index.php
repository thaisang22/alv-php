<?php
session_start();
include("classtmdt/clskhachhang.php");

$p = new khachhang();

$idcty = isset($_REQUEST["idcty"]) ? $_REQUEST["idcty"] : "";

$congty  = $p->XemDSCongTy();
$sanpham = $p->XemDSSanPham($idcty);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang chủ - Danh sách sản phẩm</title>
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
                <?php if ($p->isAdmin()) { ?>
                <li><a href="quantri.php" style="color:#c00; font-weight:bold;">Quản lý sản phẩm</a></li>
                <li><a href="thongke.php" style="color:#0055aa; font-weight:bold;">Biểu đồ thống kê</a></li>
                <?php } ?>
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
            <a href="giohang.php" class="btn">Giỏ hàng (<?php echo isset($_SESSION["giohang"]) ? count($_SESSION["giohang"]) : 0; ?>)</a>
        </div>
        <div class="content">
            <?php for ($i = 0; $i < count($sanpham); $i++) { ?>
            <div class="sanpham">
                <div class="tensp"><a href="chitietsanpham.php?idsp=<?php echo $sanpham[$i]["idsp"]; ?>"><?php echo $sanpham[$i]["tensp"]; ?></a></div>
                <div class="hinh"><img src="images/<?php echo $sanpham[$i]["hinh"]; ?>" onerror="this.src='https://via.placeholder.com/150'" width="150" height="150" /></div>
                <div class="gia">Giá: <?php echo number_format($sanpham[$i]["gia"], 0, ".", ","); ?> USD</div>
                <div class="btn-dathang">
                    <a href="giohang.php?them=1&idsp=<?php echo $sanpham[$i]["idsp"]; ?>" class="btn">Đặt hàng</a>
                </div>
            </div>
            <?php } ?>
            <div class="clearfix"></div>
        </div>

    </div>

    <div class="footer">Footer website</div>
</div>
</body>
</html>
