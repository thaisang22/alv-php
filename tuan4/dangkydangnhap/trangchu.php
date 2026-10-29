<?php
if (isset($_COOKIE['status']) && $_COOKIE['status'] === 'false') {
    header("Location: dangky.php");
    exit();
}

$message = "";
if (isset($_POST['upload']) && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $fileName = $file['name'];
    $fileTmp = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];

    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowed = array('jpg', 'jpeg', 'png', 'pdf', 'docx');

    if (in_array($fileExt, $allowed)) {
        if ($fileError === 0) {
            if ($fileSize < 5000000) { 
                $fileNameNew = uniqid('', true) . "." . $fileExt;
                $fileDestination = 'uploads/' . $fileNameNew;
                
                if (!is_dir('uploads')) {
                    mkdir('uploads', 0777, true);
                }
                
                move_uploaded_file($fileTmp, $fileDestination);
                $message = "Upload thành công: " . $fileNameNew;
            } else {
                $message = "File quá lớn (tối đa 5MB)!";
            }
        } else {
            $message = "Có lỗi xảy ra khi upload file!";
        }
    } else {
        $message = "Định dạng file không được hỗ trợ!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang chủ & Upload File</title>
</head>
<body>
    <p>Upload file</p>
    <?php if (!empty($message)) echo "<p><strong>$message</strong></p>"; ?>
    <form action="" enctype="multipart/form-data" method="post">
        <input type="file" name="file">
        <button type="submit" name="upload">Upload</button>
    </form>
</body>
</html>