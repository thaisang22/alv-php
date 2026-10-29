<?php
if (isset($_POST['btnUpFile']) && $_FILES['fileUpLoad']['error'] === UPLOAD_ERR_OK) {
    $file_size = $_FILES['fileUpLoad']['size'];
    $file_tmp = $_FILES['fileUpLoad']['tmp_name'];
    $file_name = $_FILES['fileUpLoad']['name'];

    $max_size = 5 * 1024 * 1024;
    if ($file_size > $max_size) {
        die("Lỗi kích thước lớn hơn 5MB");
    }

    $allowed_ext = ['jpg', 'png', 'pdf'];
    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed_ext)) {
        die("Lổi: định dạng file không hợp lệ");
    }
    $new_name = date('Ymd_His') . '_' . uniqid() . '.' . $ext;
    $target_path = 'upload/' . $new_name;

    if(move_uploaded_file($file_tmp,$target_path)) {
        echo "upload thanh cong file luu tai:" . $new_name;
    }
}

?>