<?php
// Class cha ket noi CSDL - dung mysqli, tuong thich PHP 5.3+
class csdltmdt
{
    protected function connect()
    {
        $host     = "localhost";
        $username = "root";
        $password = "";
        $database = "tmdt_db";

        $con = mysqli_connect($host, $username, $password, $database);
        if (!$con) return null;
        mysqli_set_charset($con, "utf8");
        return $con;
    }

    public function xuatdulieu($sql)
    {
        $con = $this->connect();
        if (!$con) return array();
        $result = mysqli_query($con, $sql);
        $data   = array();
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }
        mysqli_close($con);
        return $data;
    }

    public function thucthisql($sql)
    {
        $con = $this->connect();
        if (!$con) return false;
        $ok = mysqli_query($con, $sql);
        $insert_id = mysqli_insert_id($con);
        mysqli_close($con);
        if ($insert_id > 0) {
            return $insert_id;
        }
        return $ok;
    }
}

// Class khach hang ke thua csdltmdt
class khachhang extends csdltmdt
{
    // Bai 6.1a: Xuat DS san pham, co loc theo cong ty
    public function XemDSSanPham($id_congty = "")
    {
        if ($id_congty != "") {
            return $this->xuatdulieu("select * from sanpham where idcty='$id_congty' order by gia asc");
        } else {
            return $this->xuatdulieu("select * from sanpham order by gia asc");
        }
    }

    // Bai 6.1b: Xuat DS cong ty cho menu
    public function XemDSCongTy()
    {
        return $this->xuatdulieu("select * from congty order by tencty asc");
    }

    // Bai 6.2: Chi tiet san pham
    public function chitietsanpham($idsp)
    {
        $result = $this->xuatdulieu("select * from sanpham where idsp='$idsp' limit 1");
        if (count($result) > 0) return $result[0];
        return null;
    }

    // Bai 6.4: Them san pham vao gio hang (session)
    public function ThemVaoGio($idsp, $soluong = 1)
    {
        if (!isset($_SESSION["giohang"])) {
            $_SESSION["giohang"] = array();
        }
        $sp = $this->chitietsanpham($idsp);
        if (!$sp) return;

        if (isset($_SESSION["giohang"][$idsp])) {
            $_SESSION["giohang"][$idsp]["soluong"] += $soluong;
        } else {
            $_SESSION["giohang"][$idsp] = array(
                "idsp"    => $sp["idsp"],
                "tensp"   => $sp["tensp"],
                "gia"     => $sp["gia"],
                "hinh"    => $sp["hinh"],
                "soluong" => $soluong
            );
        }
    }

    // Bai 6.4: Cap nhat so luong trong gio
    public function CapNhatGio($idsp, $soluong)
    {
        if (!isset($_SESSION["giohang"])) return;
        if ($soluong <= 0) {
            unset($_SESSION["giohang"][$idsp]);
        } else {
            if (isset($_SESSION["giohang"][$idsp])) {
                $_SESSION["giohang"][$idsp]["soluong"] = $soluong;
            }
        }
    }

    // Bai 6.4: Xoa san pham khoi gio
    public function XoaKhoiGio($idsp)
    {
        if (isset($_SESSION["giohang"][$idsp])) {
            unset($_SESSION["giohang"][$idsp]);
        }
    }

    // Bai 6.4: Lay toan bo gio hang
    public function XemGioHang()
    {
        if (!isset($_SESSION["giohang"])) return array();
        return $_SESSION["giohang"];
    }

    // Bai 6.4: Tinh tong tien
    public function TongTien()
    {
        $tong = 0;
        if (isset($_SESSION["giohang"])) {
            foreach ($_SESSION["giohang"] as $item) {
                $tong += $item["gia"] * $item["soluong"];
            }
        }
        return $tong;
    }

    // Bai 6.3: Dang ky tai khoan khach hang (mat khau ma hoa MD5)
    public function dangky($email, $password, $hodem, $ten, $diachi, $diachinhanhang, $dienthoai)
    {
        $check = $this->xuatdulieu("select idkh from khachhang where email='$email'");
        if (count($check) > 0) {
            return "Email này đã được sử dụng!";
        }

        $pass_md5 = md5($password);
        $sql = "insert into khachhang(email, password, hodem, ten, diachi, diachinhanhang, dienthoai, phanquyen) 
                values ('$email', '$pass_md5', '$hodem', '$ten', '$diachi', '$diachinhanhang', '$dienthoai', 'user')";
        $res = $this->thucthisql($sql);
        if ($res) {
            return true;
        }
        return "Đăng ký không thành công. Vui lòng thử lại!";
    }

    // Bai 6.3: Dang nhap (mat khau ma hoa MD5)
    public function dangnhap($email, $password)
    {
        $pass_md5 = md5($password);
        $result = $this->xuatdulieu("select * from khachhang where email='$email' and password='$pass_md5' limit 1");
        if (count($result) > 0) {
            $_SESSION["khachhang"] = $result[0];
            return true;
        }
        return false;
    }

    public function isAdmin()
    {
        if (isset($_SESSION["khachhang"]) && isset($_SESSION["khachhang"]["phanquyen"]) && $_SESSION["khachhang"]["phanquyen"] == "admin") {
            return true;
        }
        return false;
    }

    public function xulydathang($idkh)
    {
        if (!isset($_SESSION["giohang"]) || count($_SESSION["giohang"]) == 0) {
            return false;
        }

        $sql_dh = "insert into dathang(idkh, ngaydathang, trangthai) values ('$idkh', NOW(), 0)";
        $iddh = $this->thucthisql($sql_dh);

        if ($iddh) {
            foreach ($_SESSION["giohang"] as $sp) {
                $idsp = $sp["idsp"];
                $sl   = $sp["soluong"];
                $gia  = $sp["gia"];
                $sql_ct = "insert into dathang_chitiet(iddh, idsp, soluong, gia) values ('$iddh', '$idsp', '$sl', '$gia')";
                $this->thucthisql($sql_ct);
            }   
            unset($_SESSION["giohang"]);
            return $iddh;
        }
        return false;
    }

    // ==========================================
    // CHUONG 7: QUAN LY SAN PHAM (quantri.php)
    // ==========================================

    // Xuat combobox cong ty (Hinh 7.9)
    public function xuatComboboxCongTy($selected_id = 0)
    {
        $ds = $this->XemDSCongTy();
        echo '<select name="idcty" id="idcty" style="width:100%; padding:6px; box-sizing:border-box;">';
        echo '<option value="">-Chọn công ty-</option>';
        for ($i = 0; $i < count($ds); $i++) {
            $sel = ($ds[$i]["idcty"] == $selected_id) ? 'selected="selected"' : '';
            echo '<option value="' . $ds[$i]["idcty"] . '" ' . $sel . '>' . $ds[$i]["tencty"] . '</option>';
        }
        echo '</select>';
    }

    // Them san pham
    public function themsanpham($tensp, $gia, $mota, $hinh, $idcty)
    {
        $sql = "insert into sanpham(tensp, gia, mota, hinh, idcty) values ('$tensp', '$gia', '$mota', '$hinh', '$idcty')";
        return $this->thucthisql($sql);
    }

    // Sua san pham
    public function suasanpham($idsp, $tensp, $gia, $mota, $hinh, $idcty)
    {
        if ($hinh != "") {
            $sql = "update sanpham set tensp='$tensp', gia='$gia', mota='$mota', hinh='$hinh', idcty='$idcty' where idsp='$idsp'";
        } else {
            $sql = "update sanpham set tensp='$tensp', gia='$gia', mota='$mota', idcty='$idcty' where idsp='$idsp'";
        }
        return $this->thucthisql($sql);
    }

    // Xoa san pham
    public function xoasanpham($idsp)
    {
        $sql = "delete from sanpham where idsp='$idsp'";
        return $this->thucthisql($sql);
    }

    // Upload hinh anh len server
    public function uploadfile($file, $folder = "images/")
    {
        if (isset($file["name"]) && $file["name"] != "") {
            $filename = time() . "_" . basename($file["name"]);
            $dest = rtrim($folder, "/") . "/" . $filename;
            if (move_uploaded_file($file["tmp_name"], $dest)) {
                return $filename;
            }
        }
        return "";
    }

    // ==========================================
    // CHUONG 7: THONG KE MUA HANG (Hinh 7.16)
    // ==========================================
    public function thongkemuahang()
    {
        $sql = "SELECT DATE(dh.ngaydathang) as ngaydat, sum(ct.soluong) as tongsoluong 
                FROM dathang_chitiet ct, dathang dh 
                WHERE ct.iddh = dh.iddh 
                GROUP BY DATE(dh.ngaydathang) 
                ORDER BY ngaydat ASC";
        return $this->xuatdulieu($sql);
    }
}
?>
