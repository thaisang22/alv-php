<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        .body{
            margin: auto;
            padding-left: 100px;
            padding-top: 20px;
        }
        .a_0{
            font-weight:bold;           
        }
        .a_1{
            font-weight: normal;
            font-style: italic;
        } 
    </style>
</head>
<body>

    <p>bài 1.1</p>
    <h1 style="color: black;">
    <?php echo 'hello php' ?> 
    </h1>   


    <p>bai 1.2</p>
    <?php 
    $hoten='Nguyen van A ';
    $ngaysinh= '01/01/2001';
    $lop='DHHTTT6A';
    echo ' Họ và Tên : '. $hoten;
    echo '<br> Ngày sinh: '. $ngaysinh;
    echo '<br> Lớp: '. $lop;
    ?>

    <p>bài 1.3</p>
    <?php 
    $x = rand(0,100); 
    $y = rand(0,100);
    echo 'x='.$x;
    echo '<br>y='.$y;
    echo '<br> x + y='.$x+$y;
    echo '<br> x - y='.$x-$y;
    echo '<br> x * y='.$x*$y;
    echo '<br> x / y='.$x/$y;
    ?>


    <p>bài 1.4</p>
    <?php 
        for($i=0;$i<30;$i++)
            {

            echo '<span class="a_'.($i%2). '">'.$i.'</span>' ;
        }
    ?>


    <?php 
        echo '';

    ?>
    <p>bai 1.5</p>
    <?php 
    $binhphuong = 1;
    for($i = 0 ; $i <= 10; $i++) {
        echo $i. ':'.pow($i,$binhphuong++) . '<br>';
    }
    ?>
    
 
</body>
</html>