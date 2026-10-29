<?php
echo "Day la trang chu";
echo '<a href="gioithieu.php">Trang gioi thieu</a>';
if(isset($_GET['id'])) {
    echo "id la ";
    echo $_GET['id'];
}
echo '<a href="index.php?id=23">Get id</a>';
?>