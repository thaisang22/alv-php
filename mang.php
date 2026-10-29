<?php

include __DIR__ . '/clsmang.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xử lý mảng</title>
    <style>
        .box {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .box-item {
            border: 3px solid darkred;
            padding: 15px 20px;
            min-width: 30px;
            text-align: center;
        }
    </style>
</head>

<body>

    <form action="" method="post">

        <p>nhap so phan tu n</p>

        <input type="number" name="txtn">

        <button type="submit" name="xuatmang" value="xuatmang">
            Xuất mảng
        </button>

    </form>

    <?php

    if (isset($_POST['xuatmang']) == 'xuatmang') {

        $n = (int) $_POST['txtn'];

        if ($n > 0) {

            $mang = [];
            $mang = XulyMang::nhapMang($mang, $n);
            XulyMang::xuatMang($mang, $n);

        } else {

            echo '<p>n phải lớn hơn 0</p>';

        }
        $timmax = xulymang::timMax($mang, $n);
        $timmin = xulymang::timMin($mang, $n);
        $demle = xulymang::demLe($mang, $n);
        echo 'max:' . $timmax;
        echo '<br>';
        echo 'min:' . $timmin;
           echo '<br>';
        echo 'dem so le:' . $demle;

    }

    ?>

</body>

</html>