<?php

require_once "database.php";

$crud = new database();


if (isset($_GET['xoa'])) {

    $ma_sv = $_GET['xoa'];

    $crud->deleteSinhVien($ma_sv);

    header("Location: crudsinhvien.php");
    exit();
}


if (isset($_POST['them'])) {

    $ma_sv = $_POST['ma_sv'];
    $ho_dem = $_POST['ho_dem'];
    $ten = $_POST['ten'];
    $lop = $_POST['lop'];

    $crud->addSinhVien(
        $ma_sv,
        $ho_dem,
        $ten,
        $lop
    );

    header("Location: crudsinhvien.php");
    exit();
}


if (isset($_POST['sua'])) {

    $ma_sv = $_POST['ma_sv'];
    $ho_dem = $_POST['ho_dem'];
    $ten = $_POST['ten'];
    $lop = $_POST['lop'];

    $crud->updateSinhVien(
        $ma_sv,
        $ho_dem,
        $ten,
        $lop
    );

    header("Location: crudsinhvien.php");
    exit();
}

$sinhVienSua = null;

if (isset($_GET['sua'])) {

    $ma_sv = $_GET['sua'];

    $sinhVienSua = $crud->getSinhVien($ma_sv);
}

?>

<!DOCTYPE html>

<html lang="vi">

<head>

    <meta charset="UTF-8">

    <title>Quản lý sinh viên</title>

</head>

<body>


<h1>Quản lý sinh viên</h1>



<form method="POST" action="">

    <table>

        <tr>

            <td>Mã sinh viên:</td>

            <td>

                <input
                    type="text"
                    name="ma_sv"
                    value="<?= $sinhVienSua['ma_sv'] ?? '' ?>"
                    <?= $sinhVienSua ? 'readonly' : '' ?>
                    required
                >

            </td>

        </tr>


        <tr>

            <td>Họ đệm:</td>

            <td>

                <input
                    type="text"
                    name="ho_dem"
                    value="<?= $sinhVienSua['ho_dem'] ?? '' ?>"
                    required
                >

            </td>

        </tr>


        <tr>

            <td>Tên:</td>

            <td>

                <input
                    type="text"
                    name="ten"
                    value="<?= $sinhVienSua['ten'] ?? '' ?>"
                    required
                >

            </td>

        </tr>


        <tr>

            <td>Lớp:</td>

            <td>

                <input
                    type="text"
                    name="lop"
                    value="<?= $sinhVienSua['lop'] ?? '' ?>"
                    required
                >

            </td>

        </tr>


        <tr>

            <td></td>

            <td>

                <?php if ($sinhVienSua): ?>

                    <button type="submit" name="sua">
                        Cập nhật
                    </button>

                    <a href="index.php">
                        Hủy
                    </a>

                <?php else: ?>

                    <button type="submit" name="them">
                        Thêm sinh viên
                    </button>

                <?php endif; ?>

            </td>

        </tr>

    </table>

</form>


<br>


<?php
$crud->showSinhVien();

?>


</body>

</html>
