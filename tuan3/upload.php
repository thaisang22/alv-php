<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <form action="" enctype="multipart/form-data" method="post"
        style="background-color: aquamarine; padding: 10px; width: 50%;">
        <input type="file" name="file">
        <button type="submit" name="submit-file"> upload file</button>
    </form>
    <h2>Kết quả sau khi upload file</h2>
    <?php
    if (isset($_POST['submit-file']))
        echo '<pre>';
    var_dump($_FILES["file"]);
    echo '</pre>';
    ?>
</body>

</html>