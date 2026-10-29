<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    $sessionThongTin = $_SESSION["Thongtin"] ?? null;
    if ($sessionThongTin) {
        echo "Gía trị biến session là: " . $_SESSION["Thongtin"] . " . <a href='logout.php'>Dang xuat </a>";
    } else {
        session_start();
        $_SESSION = [];
        session_destroy();
        header("Location: trangchu.php");
        exit;

    }
    ?>
</body>

</html>