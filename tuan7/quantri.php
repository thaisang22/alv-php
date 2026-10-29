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
$idsp_edit  = "";
$tensp_edit = "";
$gia_edit   = "";
$mota_edit  = "";
$idcty_edit = "";
$hinh_edit  = "";

if (isset($_GET["action"]) && $_GET["action"] == "delete" && isset($_GET["idsp"])) {
    $id_del = intval($_GET["idsp"]);
    if ($p->xoasanpham($id_del)) {
        $thongbao = "Xóa sản phẩm thành công!";
    } else {
        $thongbao = "Lỗi khi xóa sản phẩm!";
    }
}

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
            $thongbao = "Lỗi khi thêm sản phẩm!";
        }
    } else {
        $thongbao = "Vui lòng nhập đủ: Tên SP, Giá và Chọn công ty!";
    }
}

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
        $thongbao = "Vui lòng chọn sản phẩm cần sửa và điền đầy đủ!";
    }
}

$danhsach_sp = $p->XemDSSanPham();
?>

<h3>QUẢN LÝ SẢN PHẨM</h3>

<?php if ($thongbao != "") { ?>
    <p><strong><?php echo $thongbao; ?></strong></p>
<?php } ?>

<div>
    <h4><?php echo ($idsp_edit != "") ? "CẬP NHẬT SẢN PHẨM" : "THÊM SẢN PHẨM MỚI"; ?></h4>
    <form method="post" action="index.php?page=quantri" enctype="multipart/form-data">
        <input type="hidden" name="idsp" value="<?php echo $idsp_edit; ?>" />
        <table width="100%" border="0" cellpadding="5">
            <tr>
                <td width="160"><strong>Công ty cung cấp:</strong></td>
                <td>
                    <?php $p->xuatComboboxCongTy($idcty_edit); ?>
                </td>
            </tr>
            <tr>
                <td><strong>Tên sản phẩm:</strong></td>
                <td>
                    <input type="text" name="tensp" value="<?php echo $tensp_edit; ?>" required size="50" />
                </td>
            </tr>
            <tr>
                <td><strong>Đơn giá (USD):</strong></td>
                <td>
                    <input type="text" name="gia" value="<?php echo $gia_edit; ?>" required size="20" />
                </td>
            </tr>
            <tr>
                <td><strong>Mô tả:</strong></td>
                <td>
                    <textarea name="mota" rows="3" cols="50"><?php echo $mota_edit; ?></textarea>
                </td>
            </tr>
            <tr>
                <td><strong>Hình đại diện:</strong></td>
                <td>
                    <input type="file" name="hinh" />
                    <?php if ($hinh_edit != "") { ?>
                        <br /><small>Ảnh hiện tại: <?php echo $hinh_edit; ?></small>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="submit" name="btn_them" value="Thêm sản phẩm" />
                    &nbsp;
                    <input type="submit" name="btn_sua" value="Sửa sản phẩm" />
                    &nbsp;
                    <?php if ($idsp_edit != "") { ?>
                        <a href="index.php?page=quantri">[Hủy sửa]</a>
                    <?php } ?>
                </td>
            </tr>
        </table>
    </form>
</div>

<h4>DANH SÁCH SẢN PHẨM HIỆN CÓ</h4>
<table border="1" cellpadding="6" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th width="40">STT</th>
            <th width="70">Hình</th>
            <th width="150">Tên SP</th>
            <th width="80">Giá (USD)</th>
            <th>Mô tả</th>
            <th width="100">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <?php for ($i = 0; $i < count($danhsach_sp); $i++) { 
            $sp = $danhsach_sp[$i];
        ?>
        <tr align="center">
            <td><?php echo ($i + 1); ?></td>
            <td>
                <img src="images/<?php echo $sp["hinh"]; ?>" onerror="this.src='images/a15.jpg'" width="50" height="50" />
            </td>
            <td align="left"><strong><?php echo $sp["tensp"]; ?></strong></td>
            <td><?php echo number_format($sp["gia"], 0, ".", ","); ?></td>
            <td align="left"><?php echo $sp["mota"]; ?></td>
            <td>
                <a href="index.php?page=quantri&action=edit&idsp=<?php echo $sp["idsp"]; ?>">Sửa</a>
                &nbsp;|&nbsp;
                <a href="index.php?page=quantri&action=delete&idsp=<?php echo $sp["idsp"]; ?>" onclick="return confirm('Bạn có chắc muốn xóa: <?php echo $sp["tensp"]; ?>?');">Xóa</a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>
