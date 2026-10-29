<?php
session_start();
if (!$_SESSION['user']) {
    if (isset($_COOKIE['remember'])) {
        $_SESSION['user'] = $_COOKIE['remember'];
    } else {
        header('Location: index.php');
        exit;
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>trang chủ</h1>
    <form action="uploadfile.php" enctype="multipart/form-data" method="post">
        <label for="">Chọn file:</label>
        <input type="file" name="fileUpLoad">
        <button type="submit" name="btnUpFile">Tải lên</button>
    </form>
    <form action="reset.php" method="post">
        <button type="submit">RESET SESSION + COOKIE</button>
    </form>
    <? 
    ?>

</body>

</html>