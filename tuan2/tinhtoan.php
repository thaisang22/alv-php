<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phép tính</title>
</head>

<body>

<?php

include_once "clspheptinh.php";

$ketqua = "";

if (isset($_POST['pheptinh'])) {

    $a = $_POST['ValA'] ?? "";
    $b = $_POST['ValB'] ?? "";
    $pheptinh = $_POST['pheptinh'];
    if (!is_numeric($a) || !is_numeric($b)) {

        $ketqua = "A và B phải là số";

    } else {
        $a = (float)$a;
        $b = (float)$b;
        $pt = new clsPhepTinh();
        $ketqua = $pt->tinh($a, $b, $pheptinh);
    }
}

?>

<form name="pheptinh" action="" method="post">

    <p>A = </p>
    <input name="ValA" type="text">

    <p>B = </p>
    <input name="ValB" type="text">

    <br><br>

    <button name="pheptinh" value="+">+</button>

    <button name="pheptinh" value="-">-</button>

    <button name="pheptinh" value="*">*</button>

    <button name="pheptinh" value="/">/</button>

</form>

<p>
    Ket Qua =
    <?php echo $ketqua; ?>
</p>

</body>

</html>