<?php

class database
{
    // Kết nối database
    private function connect()
    {
        $host = "localhost";
        $username = "root";
        $password = "";
        $database = "sinhvien";

        $con = new mysqli($host, $username, $password, $database);

        if ($con->connect_error) {
            die("Kết nối thất bại: " . $con->connect_error);
        }

        // Sử dụng tiếng Việt
        $con->set_charset("utf8mb4");

        return $con;
    }


    public function showSinhVien()
    {
        $con = $this->connect();

        $sql = "SELECT * FROM sinhvien";

        $result = $con->query($sql);

        echo "<h1>Danh sách sinh viên</h1>";

        echo "<table border='1' cellpadding='10' cellspacing='0'>";

        echo "<tr>";
        echo "<th>Mã sinh viên</th>";
        echo "<th>Họ đệm</th>";
        echo "<th>Tên</th>";
        echo "<th>Lớp</th>";
        echo "<th>Thao tác</th>";
        echo "</tr>";

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {

                echo "<tr>";

                echo "<td>" . $row['ma_sv'] . "</td>";
                echo "<td>" . $row['ho_dem'] . "</td>";
                echo "<td>" . $row['ten'] . "</td>";
                echo "<td>" . $row['lop'] . "</td>";

                echo "<td>";
                
                echo "<a href='?sua=" . $row['ma_sv'] . "'>Sửa</a>";
                echo " | ";
                echo "<a href='?xoa=" . $row['ma_sv'] . "' 
                        onclick=\"return confirm('Bạn có chắc muốn xóa sinh viên này không?')\">
                        Xóa
                      </a>";

                echo "</td>";

                echo "</tr>";
            }

        } else {

            echo "<tr>";
            echo "<td colspan='5'>Chưa có sinh viên</td>";
            echo "</tr>";
        }

        echo "</table>";

        $con->close();
    }


    public function addSinhVien($ma_sv, $ho_dem, $ten, $lop)
    {
        $con = $this->connect();

        $sql = "INSERT INTO sinhvien(ma_sv, ho_dem, ten, lop)
                VALUES ('$ma_sv', '$ho_dem', '$ten', '$lop')";

        if ($con->query($sql)) {
            echo "Thêm sinh viên thành công!";
        } else {
            echo "Lỗi: " . $con->error;
        }

        $con->close();
    }
    public function deleteSinhVien($ma_sv)
    {
        $con = $this->connect();

        $sql = "DELETE FROM sinhvien
                WHERE ma_sv = '$ma_sv'";

        if ($con->query($sql)) {
            echo "Xóa sinh viên thành công!";
        } else {
            echo "Lỗi: " . $con->error;
        }

        $con->close();
    }


    public function getSinhVien($ma_sv)
    {
        $con = $this->connect();

        $sql = "SELECT * FROM sinhvien
                WHERE ma_sv = '$ma_sv'";

        $result = $con->query($sql);

        $row = $result->fetch_assoc();

        $con->close();

        return $row;
    }

    public function updateSinhVien($ma_sv, $ho_dem, $ten, $lop)
    {
        $con = $this->connect();

        $sql = "UPDATE sinhvien
                SET ho_dem = '$ho_dem',
                    ten = '$ten',
                    lop = '$lop'
                WHERE ma_sv = '$ma_sv'";

        if ($con->query($sql)) {
            echo "Cập nhật sinh viên thành công!";
        } else {
            echo "Lỗi: " . $con->error;
        }

        $con->close();
    }
}

?>
```
