<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 
    $txtten =  $_POST["txtten"] ?? null;
    ?>
    <h1>ho va ten:
    <?php 
        if($txtten != null) {
            echo $txtten;
        } else {
            echo 'chua nhap ten';
        }
    ?>
    </h1> 
</body>
</html>