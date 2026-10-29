<?php
session_start();
include("classtmdt/clskhachhang.php");

$p = new khachhang();

// KIEM TRA PHAN QUYEN ADMIN
if (!isset($_SESSION["khachhang"])) {
    header("Location: dangnhap.php?yeucau=admin");
    exit();
}

$is_admin = $p->isAdmin();

$thongbao = "";

// Neu la Admin moi xu ly cac thao tac Quan tri
if ($is_admin) {
    // Bien luu thong tin form khi sua
    $idsp_edit  = "";
    $tensp_edit = "";
    $gia_edit   = "";
    $mota_edit  = "";
    $idcty_edit = "";
    $hinh_edit  = "";

    // Xu ly XOA san pham
    if (isset($_GET["action"]) && $_GET["action"] == "delete" && isset($_GET["idsp"])) {
        $id_del = intval($_GET["idsp"]);
        if ($p->xoasanpham($id_del)) {
            $thongbao = "Xóa sản phẩm thành công!";
        } else {
            $thongbao = "Lỗi khi xóa sản phẩm!";
        }
    }

    // Xu ly load du lieu len form khi bam SUA
    if (isset($_GET["action"]) && $_GET["action"] == "edit" && isset($_GET["idsp"])) {
        $sp_edit = $p->chitietsanpham(intval($_GET["idsp"]));
        if ($sp_edit) {
            $idsp_edit  = $sp_edit["idsp"];
            $tensp_edit = $sp_edit["tensp"];
            $gia_edit   = $sp_edit["gia"];
            $mota_edit  = $sp_edit["mota"];
            $idcty_edit = $sp_edit["idcty"];
            $hinh_edit  = $sp_edit["hinh"];
        }
    }

    // Xu ly SUBMIT: THEM SAN PHAM
    if (isset($_POST["btn_them"])) {
        $tensp = trim($_POST["tensp"]);
        $gia   = floatval($_POST["gia"]);
        $mota  = trim($_POST["mota"]);
        $idcty = intval($_POST["idcty"]);

        if ($tensp != "" && $gia > 0 && $idcty > 0) {
            $hinh = "";
            if (isset($_FILES["hinh"]) && $_FILES["hinh"]["name"] != "") {
                $hinh = $p->uploadfile($_FILES["hinh"], "images/");
            }

            if ($p->themsanpham($tensp, $gia, $mota, $hinh, $idcty)) {
                $thongbao = "Thêm sản phẩm mới thành công!";
            } else {
                $thongbao = "Lỗi khi thêm sản phẩm vào CSDL!";
            }
        } else {
            $thongbao = "Vui lòng nhập đầy đủ: Tên SP, Giá và Chọn công ty!";
        }
    }

    // Xu ly SUBMIT: SUA SAN PHAM
    if (isset($_POST["btn_sua"])) {
        $idsp  = intval($_POST["idsp"]);
        $tensp = trim($_POST["tensp"]);
        $gia   = floatval($_POST["gia"]);
        $mota  = trim($_POST["mota"]);
        $idcty = intval($_POST["idcty"]);

        if ($idsp > 0 && $tensp != "" && $gia > 0) {
            $hinh = "";
            if (isset($_FILES["hinh"]) && $_FILES["hinh"]["name"] != "") {
                $hinh = $p->uploadfile($_FILES["hinh"], "images/");
            }

            if ($p->suasanpham($idsp, $tensp, $gia, $mota, $hinh, $idcty)) {
                $thongbao = "Cập nhật sản phẩm thành công!";
                $idsp_edit = $tensp_edit = $gia_edit = $mota_edit = $idcty_edit = $hinh_edit = "";
            } else {
                $thongbao = "Lỗi khi cập nhật sản phẩm!";
            }
        } else {
            $thongbao = "Vui lòng chọn sản phẩm cần sửa và điền đầy đủ thông tin!";
        }
    }

    // Lay danh sach san pham
    $danhsach_sp = $p->XemDSSanPham();
}

$congty = $p->XemDSCongTy();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý sản phẩm - Quản trị</title>
    <link rel="stylesheet" href="style/styletmdt.css">
    <style>
        .form-quantri {
            width: 580px;
            margin: 0 auto 20px auto;
            border: 1px solid #999;
            padding: 15px;
            background: #fafafa;
        }
        .form-quantri h3 {
            text-align: center;
            margin-bottom: 15px;
            font-size: 16px;
            color: #333;
            text-transform: uppercase;
        }
        .table-form {
            width: 100%;
            border-collapse: collapse;
        }
        .table-form td {
            padding: 6px;
            font-size: 13px;
        }
        .table-form td:first-child {
            width: 140px;
            font-weight: bold;
        }
        .table-form input[type="text"],
        .table-form textarea {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            font-size: 13px;
        }
        .table-dssp {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .table-dssp th, .table-dssp td {
            border: 1px solid #999;
            padding: 8px;
            font-size: 13px;
            text-align: center;
        }
        .table-dssp th {
            background-color: #336699;
            color: #fff;
        }
        .table-dssp tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .table-dssp td.col-mota {
            text-align: left;
            max-width: 250px;
            font-size: 12px;
        }
        .alert-box {
            padding: 8px 12px;
            margin-bottom: 12px;
            font-size: 13px;
            font-weight: bold;
            text-align: center;
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .badge-admin {
            background: #c0392b;
            color: #fff;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
<div class="wrapper">

    <div class="banner">BANNER WEBSITE - TRANG QUẢN TRỊ</div>

    <div class="main-container">

        <!-- Menu trai -->
        <div class="menu">
            <h3>MENU</h3>
            <ul>
                <li><a href="index.php">Trang chủ</a></li>
                <li><a href="quantri.php" style="font-weight:bold; color:#c00;">Quản lý sản phẩm</a></li>
                <li><a href="thongke.php" style="font-weight:bold; color:#0055aa;">Biểu đồ thống kê</a></li>
                <li><a href="giohang.php">Giỏ hàng (<?php echo isset($_SESSION["giohang"]) ? count($_SESSION["giohang"]) : 0; ?>)</a></li>
            </ul>
            <br>
            <div style="border-top:1px solid #ccc; padding-top:10px;">
                <?php if (isset($_SESSION["khachhang"])) { ?>
                    <p style="font-size:12px; margin-bottom:5px;">
                        Chào, <strong><?php echo $_SESSION["khachhang"]["ten"]; ?></strong>
                        <?php if ($is_admin) { ?>
                            <span class="badge-admin">Admin</span>
                        <?php } ?>
                    </p>
                    <a href="dangxuat.php" class="btn btn-red" style="font-size:11px; padding:3px 6px;">Đăng xuất</a>
                <?php } ?>
            </div>
        </div>

        <!-- Vung noi dung quan tri -->
        <div class="content">

            <?php if (!$is_admin) { ?>
                <!-- THONG BAO KHONG CO QUYEN ADMIN -->
                <div style="padding: 25px; background: #fff3cd; border: 1px solid #ffeeba; color: #856404; text-align: center; margin: 30px auto; max-width: 600px;">
                    <h3 style="color: #c0392b; margin-bottom: 10px;">TRUY CẬP BỊ TỪ CHỐI (ACCESS DENIED)</h3>
                    <p style="font-size: 14px; margin-bottom: 15px;">
                        Tài khoản hiện tại của bạn không có quyền <strong>Quản trị viên (Admin)</strong> để vào trang này!
                    </p>
                    <a href="dangnhap.php?yeucau=admin" class="btn">Đăng nhập tài khoản Admin</a>
                    &nbsp;&nbsp;
                    <a href="index.php" class="btn btn-green">Về trang chủ</a>
                </div>

            <?php } else { ?>

                <?php if ($thongbao != "") { ?>
                    <div class="alert-box"><?php echo $thongbao; ?></div>
                <?php } ?>

                <!-- FORM QUAN LY SAN PHAM (Hinh 7.9) -->
                <div class="form-quantri">
                    <h3>QUẢN LÝ SẢN PHẨM</h3>
                    <form method="post" action="quantri.php" enctype="multipart/form-data">
                        <input type="hidden" name="idsp" value="<?php echo $idsp_edit; ?>">

                        <table class="table-form">
                            <tr>
                                <td>Công ty cung cấp</td>
                                <td>
                                    <?php $p->xuatComboboxCongTy($idcty_edit); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Nhập tên sản phẩm</td>
                                <td>
                                    <input type="text" name="tensp" value="<?php echo htmlspecialchars($tensp_edit); ?>" required>
                                </td>
                            </tr>
                            <tr>
                                <td>Nhập giá (USD)</td>
                                <td>
                                    <input type="text" name="gia" value="<?php echo $gia_edit; ?>" required>
                                </td>
                            </tr>
                            <tr>
                                <td>Mô tả</td>
                                <td>
                                    <textarea name="mota" rows="4"><?php echo htmlspecialchars($mota_edit); ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>Hình đại diện</td>
                                <td>
                                    <input type="file" name="hinh">
                                    <?php if ($hinh_edit != "") { ?>
                                        <br><small>Ảnh hiện tại: <?php echo $hinh_edit; ?></small>
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align:center; padding-top:12px;">
                                    <input type="submit" name="btn_them" value="Thêm sản phẩm" class="btn btn-green">
                                    &nbsp;&nbsp;
                                    <input type="submit" name="btn_sua" value="Sửa sản phẩm" class="btn" style="background:#f39c12;">
                                    &nbsp;&nbsp;
                                    <?php if ($idsp_edit != "") { ?>
                                        <a href="quantri.php" class="btn" style="background:#7f8c8d;">Hủy sửa</a>
                                    <?php } ?>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>

                <!-- BANG DANH SACH SAN PHAM (Hinh 7.9) -->
                <table class="table-dssp">
                    <thead>
                        <tr>
                            <th style="width:40px;">STT</th>
                            <th style="width:140px;">Tên SP</th>
                            <th style="width:70px;">Giá</th>
                            <th>Mô tả</th>
                            <th style="width:100px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        for ($i = 0; $i < count($danhsach_sp); $i++) { 
                            $sp = $danhsach_sp[$i];
                        ?>
                        <tr>
                            <td><?php echo ($i + 1); ?></td>
                            <td><strong><?php echo $sp["tensp"]; ?></strong></td>
                            <td><?php echo number_format($sp["gia"], 0, ".", ","); ?></td>
                            <td class="col-mota"><?php echo $sp["mota"]; ?></td>
                            <td>
                                <a href="quantri.php?action=edit&idsp=<?php echo $sp["idsp"]; ?>" style="color:#0055aa; font-weight:bold; text-decoration:none;">Sửa</a>
                                &nbsp;|&nbsp;
                                <a href="quantri.php?action=delete&idsp=<?php echo $sp["idsp"]; ?>" 
                                   onclick="return confirm('Bạn có chắc muốn xóa sản phẩm: <?php echo $sp["tensp"]; ?>?');"
                                   style="color:#c00; font-weight:bold; text-decoration:none;">Xóa</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>

            <?php } ?>

        </div>

    </div>

    <div class="footer">Footer website</div>
</div>
</body>
</html>
