<?php
session_start();
include("classtmdt/clskhachhang.php");

$p = new khachhang();

$idsp = isset($_REQUEST["idsp"]) ? $_REQUEST["idsp"] : "";
$sp   = null;
if ($idsp != "") {
    $sp = $p->chitietsanpham($idsp);
}

$congty = $p->XemDSCongTy();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết sản phẩm</title>
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

        <!-- Vung chi tiet san pham -->
        <div class="content">
            <div class="chitietsanpham">
                <h2>CHI TIẾT SẢN PHẨM</h2>
                <?php if ($sp) { ?>
                <img src="images/<?php echo $sp["hinh"]; ?>" onerror="this.src='https://via.placeholder.com/200'" />
                <div class="info">
                    <p style="font-size:16px; color:#0055aa;"><strong><?php echo $sp["tensp"]; ?></strong></p>
                    <p>Mô tả: <?php echo $sp["mota"]; ?></p>
                    <p class="gia">Giá: <?php echo number_format($sp["gia"], 0, ".", ","); ?> USD</p>

                    <!-- Form dat hang them vao gio -->
                    <form method="post" action="giohang.php">
                        <input type="hidden" name="idsp" value="<?php echo $sp["idsp"]; ?>">
                        <div class="form-group">
                            <label>Số lượng:</label>
                            <input type="number" name="soluong" value="1" min="1" style="width:60px;">
                        </div>
                        <input type="submit" name="them" value="Thêm vào giỏ hàng" class="btn">
                    </form>
                    <br>
                    <a href="index.php">« Quay lại danh sách sản phẩm</a>
                </div>
                <?php } else { ?>
                <p>Không tìm thấy sản phẩm này.</p>
                <a href="index.php">« Quay lại danh sách</a>
                <?php } ?>
                <div class="clearfix"></div>
            </div>
        </div>

    </div>

    <div class="footer">Footer website</div>
</div>
</body>
</html>
