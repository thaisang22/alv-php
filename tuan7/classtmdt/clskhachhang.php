<?php
if (!class_exists('csdltmdt')) {
class csdltmdt
{
    // Ket noi CSDL
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

    // Xuat du lieu truy van
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

    // Thuc thi cau lenh insert, update, delete
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
}

if (!class_exists('khachhang')) {
class khachhang extends csdltmdt
{
    // Lay danh sach san pham (kem ten cong ty)
    public function XemDSSanPham($id_congty = "")
    {
        if ($id_congty != "") {
            $idcty_clean = intval($id_congty);
            return $this->xuatdulieu("select s.*, c.tencty from sanpham s left join congty c on s.idcty = c.idcty where s.idcty='$idcty_clean' order by s.idsp desc");
        } else {
            return $this->xuatdulieu("select s.*, c.tencty from sanpham s left join congty c on s.idcty = c.idcty order by s.idsp desc");
        }
    }

    // Lay danh sach cong ty
    public function XemDSCongTy()
    {
        return $this->xuatdulieu("select * from congty order by idcty asc");
    }

    // Xem chi tiet san pham
    public function chitietsanpham($idsp)
    {
        $idsp_clean = intval($idsp);
        $result = $this->xuatdulieu("select s.*, c.tencty from sanpham s left join congty c on s.idcty = c.idcty where s.idsp='$idsp_clean' limit 1");
        if (count($result) > 0) return $result[0];
        return null;
    }

    // Them san pham vao gio hang
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

    // Cap nhat so luong trong gio
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

    // Xoa san pham khoi gio
    public function XoaKhoiGio($idsp)
    {
        if (isset($_SESSION["giohang"][$idsp])) {
            unset($_SESSION["giohang"][$idsp]);
        }
    }

    // Lay toan bo gio hang
    public function XemGioHang()
    {
        if (!isset($_SESSION["giohang"])) return array();
        return $_SESSION["giohang"];
    }

    // Tinh tong tien
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

    // Xu ly dat hang
    public function xulydathang($idkh)
    {
        if (!isset($_SESSION["giohang"]) || count($_SESSION["giohang"]) == 0) {
            return false;
        }

        $idkh_clean = intval($idkh);
        $sql_dh = "insert into dathang(idkh, ngaydathang, trangthai) values ('$idkh_clean', NOW(), 0)";
        $iddh = $this->thucthisql($sql_dh);

        if ($iddh) {
            foreach ($_SESSION["giohang"] as $sp) {
                $idsp = intval($sp["idsp"]);
                $sl   = intval($sp["soluong"]);
                $gia  = floatval($sp["gia"]);
                $sql_ct = "insert into dathang_chitiet(iddh, idsp, soluong, gia) values ('$iddh', '$idsp', '$sl', '$gia')";
                $this->thucthisql($sql_ct);
            }
            unset($_SESSION["giohang"]);
            return $iddh;
        }
        return false;
    }

    // Dang ky tai khoan
    public function dangky($email, $password, $hodem, $ten, $diachi, $diachinhanhang, $dienthoai)
    {
        $email_clean = addslashes(trim($email));
        $check = $this->xuatdulieu("select idkh from khachhang where email='$email_clean'");
        if (count($check) > 0) {
            return "Email này đã được sử dụng!";
        }

        $pass_md5 = md5($password);
        $hodem_c = addslashes(trim($hodem));
        $ten_c = addslashes(trim($ten));
        $diachi_c = addslashes(trim($diachi));
        $diachinhan_c = addslashes(trim($diachinhanhang));
        $dienthoai_c = addslashes(trim($dienthoai));

        $sql = "insert into khachhang(email, password, hodem, ten, diachi, diachinhanhang, dienthoai, phanquyen) 
                values ('$email_clean', '$pass_md5', '$hodem_c', '$ten_c', '$diachi_c', '$diachinhan_c', '$dienthoai_c', 'user')";
        $res = $this->thucthisql($sql);
        if ($res) {
            return true;
        }
        return "Đăng ký không thành công. Vui lòng thử lại!";
    }

    // Dang nhap tai khoan
    public function dangnhap($email, $password)
    {
        $email_clean = addslashes(trim($email));
        $pass_md5 = md5($password);
        $result = $this->xuatdulieu("select * from khachhang where email='$email_clean' and password='$pass_md5' limit 1");
        if (count($result) > 0) {
            $_SESSION["khachhang"] = $result[0];
            return true;
        }
        return false;
    }

    // Kiem tra quyen admin
    public function isAdmin()
    {
        if (isset($_SESSION["khachhang"]) && isset($_SESSION["khachhang"]["phanquyen"]) && $_SESSION["khachhang"]["phanquyen"] == "admin") {
            return true;
        }
        return false;
    }

    // Xuat combobox cong ty
    public function xuatComboboxCongTy($selected_id = 0)
    {
        $ds = $this->XemDSCongTy();
        echo '<select name="idcty" id="idcty" style="width:100%; padding:4px;">';
        echo '<option value="">-- Chọn công ty --</option>';
        for ($i = 0; $i < count($ds); $i++) {
            $sel = ($ds[$i]["idcty"] == $selected_id) ? 'selected="selected"' : '';
            echo '<option value="' . $ds[$i]["idcty"] . '" ' . $sel . '>' . $ds[$i]["tencty"] . '</option>';
        }
        echo '</select>';
    }

    // Them san pham
    public function themsanpham($tensp, $gia, $mota, $hinh, $idcty)
    {
        $tensp_c = addslashes(trim($tensp));
        $gia_c   = floatval($gia);
        $mota_c  = addslashes(trim($mota));
        $hinh_c  = addslashes(trim($hinh));
        $idcty_c = intval($idcty);

        $sql = "insert into sanpham(tensp, gia, mota, hinh, idcty) values ('$tensp_c', '$gia_c', '$mota_c', '$hinh_c', '$idcty_c')";
        return $this->thucthisql($sql);
    }

    // Sua san pham
    public function suasanpham($idsp, $tensp, $gia, $mota, $hinh, $idcty)
    {
        $idsp_c  = intval($idsp);
        $tensp_c = addslashes(trim($tensp));
        $gia_c   = floatval($gia);
        $mota_c  = addslashes(trim($mota));
        $idcty_c = intval($idcty);

        if ($hinh != "") {
            $hinh_c = addslashes(trim($hinh));
            $sql = "update sanpham set tensp='$tensp_c', gia='$gia_c', mota='$mota_c', hinh='$hinh_c', idcty='$idcty_c' where idsp='$idsp_c'";
        } else {
            $sql = "update sanpham set tensp='$tensp_c', gia='$gia_c', mota='$mota_c', idcty='$idcty_c' where idsp='$idsp_c'";
        }
        return $this->thucthisql($sql);
    }

    // Xoa san pham
    public function xoasanpham($idsp)
    {
        $idsp_c = intval($idsp);
        $sql = "delete from sanpham where idsp='$idsp_c'";
        return $this->thucthisql($sql);
    }

    // Upload hinh anh
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

    // Lay danh sach don hang
    public function XemDSDatHang($trangthai = "")
    {
        $sql = "SELECT dh.iddh, dh.idkh, dh.ngaydathang, dh.trangthai, 
                       kh.hodem, kh.ten, kh.email, kh.dienthoai, kh.diachinhanhang, kh.diachi
                FROM dathang dh 
                LEFT JOIN khachhang kh ON dh.idkh = kh.idkh ";
        if ($trangthai !== "") {
            $tt_clean = intval($trangthai);
            $sql .= " WHERE dh.trangthai = '$tt_clean' ";
        }
        $sql .= " ORDER BY dh.iddh DESC";
        return $this->xuatdulieu($sql);
    }

    // Lay chi tiet don hang
    public function XemChiTietDonHang($iddh)
    {
        $iddh_c = intval($iddh);
        $sql = "SELECT ct.iddh, ct.idsp, ct.soluong, ct.gia, sp.tensp, sp.hinh, (ct.soluong * ct.gia) as thanhtien
                FROM dathang_chitiet ct
                LEFT JOIN sanpham sp ON ct.idsp = sp.idsp
                WHERE ct.iddh = '$iddh_c'";
        return $this->xuatdulieu($sql);
    }

    // Cap nhat trang thai don hang
    public function DoiTrangThaiDonHang($iddh, $trangthai_moi)
    {
        $iddh_c = intval($iddh);
        $tt_c   = intval($trangthai_moi);
        $sql = "UPDATE dathang SET trangthai = '$tt_c' WHERE iddh = '$iddh_c'";
        return $this->thucthisql($sql);
    }

    // Thong ke doanh thu theo ngay
    public function ThongKeDoanhThuTheoNgay()
    {
        $sql = "SELECT DATE(dh.ngaydathang) as thoigian, 
                       SUM(ct.soluong * ct.gia) as doanhthu, 
                       SUM(ct.soluong) as tongsoluong,
                       COUNT(DISTINCT dh.iddh) as sodonhang
                FROM dathang dh
                JOIN dathang_chitiet ct ON dh.iddh = ct.iddh
                GROUP BY DATE(dh.ngaydathang)
                ORDER BY thoigian DESC";
        return $this->xuatdulieu($sql);
    }

    // Thong ke doanh thu theo thang
    public function ThongKeDoanhThuTheoThang()
    {
        $sql = "SELECT DATE_FORMAT(dh.ngaydathang, '%Y-%m') as thoigian, 
                       SUM(ct.soluong * ct.gia) as doanhthu, 
                       SUM(ct.soluong) as tongsoluong,
                       COUNT(DISTINCT dh.iddh) as sodonhang
                FROM dathang dh
                JOIN dathang_chitiet ct ON dh.iddh = ct.iddh
                GROUP BY DATE_FORMAT(dh.ngaydathang, '%Y-%m')
                ORDER BY thoigian DESC";
        return $this->xuatdulieu($sql);
    }

    // Thong ke doanh thu theo nam
    public function ThongKeDoanhThuTheoNam()
    {
        $sql = "SELECT YEAR(dh.ngaydathang) as thoigian, 
                       SUM(ct.soluong * ct.gia) as doanhthu, 
                       SUM(ct.soluong) as tongsoluong,
                       COUNT(DISTINCT dh.iddh) as sodonhang
                FROM dathang dh
                JOIN dathang_chitiet ct ON dh.iddh = ct.iddh
                GROUP BY YEAR(dh.ngaydathang)
                ORDER BY thoigian DESC";
        return $this->xuatdulieu($sql);
    }

    // Du lieu bieu do theo thang
    public function BieuDoSanPhamTheoThang()
    {
        $sql = "SELECT DATE_FORMAT(dh.ngaydathang, '%Y-%m') as thang, 
                       SUM(ct.soluong) as tongsoluong
                FROM dathang dh
                JOIN dathang_chitiet ct ON dh.iddh = ct.iddh
                GROUP BY DATE_FORMAT(dh.ngaydathang, '%Y-%m')
                ORDER BY thang ASC";
        return $this->xuatdulieu($sql);
    }

    // Du lieu bieu do theo nam
    public function BieuDoSanPhamTheoNam()
    {
        $sql = "SELECT YEAR(dh.ngaydathang) as nam, 
                       SUM(ct.soluong) as tongsoluong
                FROM dathang dh
                JOIN dathang_chitiet ct ON dh.iddh = ct.iddh
                GROUP BY YEAR(dh.ngaydathang)
                ORDER BY nam ASC";
        return $this->xuatdulieu($sql);
    }

    // Thong ke mua hang ve bieu do (Hinh 7.16)
    public function thongkemuahang()
    {
        return $this->xuatdulieu("SELECT dh.ngaydathang as ngaydat, sum(ct.soluong) as tongsoluong
FROM dathang_chitiet ct, dathang dh
where ct.iddh=dh.iddh
group by dh.ngaydathang ");
    }
}
}
