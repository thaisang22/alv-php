<?php
session_start();
unset($_SESSION["khachhang"]);
header("Location: index.php");
exit();
?>
