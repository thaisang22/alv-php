<?php
class csdltmdt
{
    // private function connect()
    // {
    //     $host = "localhost";
    //     $username = "root";
    //     $password = "";
    //     $database = "tmdt_db";
    //     try {
    //         $con = new mysqli($host, $username, $password, $database);
    //     } catch (mysqli_sql_exception $e) {
    //         return null;
    //     }

    //     $con->set_charset("utf8mb4");
    //     return $con;
    // }   

    private function connect()
    {
        $host = "localhost";
        $username = " rooot";
        $password = "";
        $database = "tmdt_db";
        try {
            $con = new mysqli($host, $username, $password, $database);
        } catch (mysqli_sql_exception $e) {
            return null;
        }
        $con->set_charset("utf8mb4");
        return $con;
    }



    public function xuatDuLieu()
    {
        $con = $this->connect();
        if ($con === null) {
            echo '<p>Không thể kết nối đến cơ sở dữ liệu.</p>';
            return;
        }

        $sql = "SELECT idcty, tencty, diachi, dienthoai, fax FROM congty";
        $result = $con->query($sql);
        if (!$result) {
            echo '<p>Không thể lấy dữ liệu công ty.</p>';
            $con->close();
            return;
        }



        echo '<table border="1" cellpadding="6" cellspacing="0">';
        echo '
        <thead>
        <tr><th>idcty</th>
        <th>tencty</th>
        <th>diachi</th>
        <th>dienthoai</th>
        <th>fax</th></tr>
        </thead>';
        echo '<tbody>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            foreach (["idcty", "tencty", "diachi", "dienthoai", "fax"] as $column) {
                echo '<td>' . $row[$column] . '</td>';
            }
            echo '</tr>';
        }

        if ($result->num_rows === 0) {
            echo '<tr><td colspan="5">Chưa có dữ liệu công ty.</td></tr>';
        }

        echo '</tbody></table>';
        $result->free();
        $con->close();
    }

}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Danh sách công ty</title>
<style type="text/css">
#apDiv1 {
	position: absolute;
	left: 2px;
	top: 12px;
	width: 262px;
	height: 489px;
	z-index: 1;
	 border: 1px solid #ff0000;
}
#apDiv2 {
	position: absolute;
	left: 266px;
	top: 13px;
	width: 1130px;
	height: 489px;
	z-index: 2;
	color: #03C;
	border: 1px solid #ff0000;
}
</style>
</head>

<body>
    <div id="apDiv1"></div>
    <div id="apDiv2"></div>
    <h1>Danh sách công ty</h1>
    <?php (new csdltmdt())->xuatDuLieu(); ?>
    <h1></h1>

</body>

</html>