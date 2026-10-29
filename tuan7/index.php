<?php
session_start();
include("classtmdt/clskhachhang.php");

$p = new khachhang();
$congty = $p->XemDSCongTy();
$page = isset($_REQUEST["page"]) ? $_REQUEST["page"] : "";

// Khong co quyen admin thi khong duoc truy cap, da ve lai index.php
$admin_pages = array("quantri", "quanlybanhang", "thongke");
if (in_array($page, $admin_pages) && !$p->isAdmin()) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Trang chủ - Bán hàng</title>
  <style type="text/css">
    body {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 13px;
      margin: 0;
      padding: 0;
    }

    #Menutrai {
      border: 1px solid #111111ff;
      position: absolute;
      left: 181px;
      top: 55px;
      width: 164px;
      height: 642px;
      z-index: 1;
      overflow-y: auto;
    }

    #product {
      border: 1px solid #111111ff;
      position: absolute;
      left: 345px;
      top: 51px;
      width: 856px;
      height: 639px;
      z-index: 2;
      overflow-y: auto;
      padding: 12px;
      box-sizing: border-box;
    }

    .sp-item {
      float: left;
      width: 180px;
      border: 1px solid #ddd;
      margin: 8px;
      padding: 10px;
      text-align: center;
      background: #fff;
    }

    .sp-item img {
      width: 130px;
      height: 130px;
      object-fit: cover;
      display: block;
      margin: 5px auto;
    }

    .sp-item .tensp {
      font-weight: bold;
      margin: 5px 0;
      height: 36px;
      overflow: hidden;
    }

    .sp-item .tensp a {
      color: #036;
      text-decoration: none;
    }

    .sp-item .gia {
      color: #c00;
      font-weight: bold;
      margin-bottom: 8px;
    }

    .clear {
      clear: both;
    }

    .menu-box {
      border-bottom: 1px solid #ddd;
      padding: 8px;
      margin-bottom: 8px;
    }

    .menu-box h4 {
      margin: 4px 0 8px 0;
      color: #036;
      border-bottom: 1px solid #eee;
      padding-bottom: 4px;
      font-size: 13px;
    }

    .menu-box ul {
      list-style-type: none;
      padding-left: 0;
      margin: 0;
    }

    .menu-box ul li {
      padding: 4px 0;
      border-bottom: 1px dotted #eee;
    }

    .menu-box ul li a {
      text-decoration: none;
      color: #333;
      font-size: 13px;
    }

  </style>
</head>

<body>
  <div id="Menutrai">
    <div class="menu-box">
      <h4>TÀI KHOẢN</h4>
      <?php if (isset($_SESSION["khachhang"])) { ?>
        <p style="margin:4px 0;">Chào, <strong><?php echo $_SESSION["khachhang"]["ten"]; ?></strong></p>
        <?php if ($p->isAdmin()) { ?>
          <p style="color:red; font-weight:bold; margin:2px 0;">(Admin)</p>
        <?php } ?>
        <p style="margin:4px 0;"><a href="dangxuat.php" style="color:red;">[Đăng xuất]</a></p>
      <?php } else { ?>
        <a href="index.php?page=dangnhap">Đăng nhập / Đăng ký</a>
      <?php } ?>
    </div>

    <div class="menu-box">
      <ul>
        <li><a href="index.php"><strong>Trang chủ</strong></a></li>
        <li><a href="index.php">-- Tất cả công ty --</a></li>
        <?php for ($i = 0; $i < count($congty); $i++) { ?>
          <li>
            <a href="index.php?idcty=<?php echo $congty[$i]["idcty"]; ?>">
              <?php echo $congty[$i]["tencty"]; ?>
            </a>
          </li>
        <?php } ?>
        <li><a href="index.php?page=giohang"><strong>Giỏ hàng</strong>
            (<?php echo isset($_SESSION["giohang"]) ? count($_SESSION["giohang"]) : 0; ?>)</a></li>
        <?php if ($p->isAdmin()) { ?>
          <li><a href="index.php?page=quantri" style="color:#c00;">Quản lý sản phẩm</a></li>
          <li><a href="index.php?page=quanlybanhang" style="color:#006600;">Quản lý bán hàng</a></li>
          <li><a href="index.php?page=thongke" style="color:#0033cc; font-weight:bold;">Thống kê & Biểu đồ</a></li>
        <?php } ?>
      </ul>
    </div>


  </div>

  <div id="product">
    <?php
    switch ($page) {
      case "chitietsanpham":
        include("chitietsanpham.php");
        break;
      case "giohang":
        include("giohang.php");
        break;
      case "dangnhap":
        include("dangnhap.php");
        break;
      case "quantri":
        include("quantri.php");
        break;
      case "quanlybanhang":
        include("quanlybanhang.php");
        break;
      case "thongke":
        include("thongke.php");
        break;
      default:
        include("sanpham.php");
        break;
    }
    ?>
  </div>
</body>

</html>