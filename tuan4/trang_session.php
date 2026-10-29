<?php
session_start();
error_reporting();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    if (isset($_POST["sbgan"])) {
        $_SESSION["Thongtin"] = $_POST['txtthongtin'];
    }
    ?>
    <form action="" method="post">
        <table>
            <tr>
                <td>
                    gán giá trị cho biến thông tin:
                </td>
                <td>
                    <input type="text" name="txtthongtin">
                </td>
                <td>
                    <button type="submit" name="sbgan">Gán bién</button>
                </td>
            </tr>
        </table>
    </form>
    <?php
    $sessionThongTin = $_SESSION["Thongtin"] ?? null;
    if ($sessionThongTin) {
        echo "Gía trị biến session là: " . $_SESSION["Thongtin"] . " . <a href='logout.php'>Dang xuat </a>";
    } else {
        echo "Gía trị của biến session chưa được gán";
    }
    ?>

</body>

</html>