<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    $ketqua = "";
    if (isset($_POST['luachon'])) {
        $luachon = $_POST['luachon'];
        if ($luachon == 'a') 
        {
            $ketqua = 'Đã lựa chọn a';
        }elseif ($luachon == 'b') {
            $ketqua = 'Đã lựa chọn b';
        }
    }
    ?>
    <form method="post">
        <input name="luachon" type="submit" value="a">
        <input name="luachon" type="submit" value="b">
    </form>

    <?php 
        echo $ketqua;
    ?>

    
    
</body>

</html>