-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 29, 2026 at 12:19 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tmdt_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `congty`
--

CREATE TABLE `congty` (
  `idcty` int(11) NOT NULL,
  `tencty` varchar(50) NOT NULL,
  `diachi` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dienthoai` int(11) NOT NULL,
  `fax` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `congty`
--

INSERT INTO `congty` (`idcty`, `tencty`, `diachi`, `dienthoai`, `fax`) VALUES
(1, ' Samsung ', 'Thủ Đức, TP. Hồ Chí Minh', 2147483647, '02887654322'),
(2, 'Xiaomi', 'Quận 3, TP. Hồ Chí Minh', 2147483647, '02899998889'),
(3, 'Apple', 'Quận 1, TP. Hồ Chí Minh', 2147483647, '02812345679');

-- --------------------------------------------------------

--
-- Table structure for table `dathang`
--

CREATE TABLE `dathang` (
  `iddh` int(11) NOT NULL,
  `idkh` int(11) NOT NULL,
  `ngaydathang` timestamp NOT NULL DEFAULT current_timestamp(),
  `trangthai` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dathang`
--

INSERT INTO `dathang` (`iddh`, `idkh`, `ngaydathang`, `trangthai`) VALUES
(1, 3, '2026-10-29 11:11:38', 0),
(2, 3, '2026-10-29 11:11:49', 0),
(3, 3, '2025-10-29 11:12:01', 0),
(4, 3, '2026-10-29 11:12:07', 0);

-- --------------------------------------------------------

--
-- Table structure for table `dathang_chitiet`
--

CREATE TABLE `dathang_chitiet` (
  `iddh` int(11) NOT NULL,
  `idsp` int(11) NOT NULL,
  `soluong` int(11) NOT NULL,
  `gia` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dathang_chitiet`
--

INSERT INTO `dathang_chitiet` (`iddh`, `idsp`, `soluong`, `gia`) VALUES
(1, 8, 3, 1100),
(2, 6, 1, 800),
(3, 3, 3, 300),
(4, 10, 1, 5000);

-- --------------------------------------------------------

--
-- Table structure for table `khachhang`
--

CREATE TABLE `khachhang` (
  `idkh` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `hodem` varchar(50) DEFAULT NULL,
  `ten` varchar(50) DEFAULT NULL,
  `diachi` varchar(255) DEFAULT NULL,
  `diachinhanhang` varchar(255) DEFAULT NULL,
  `dienthoai` varchar(20) DEFAULT NULL,
  `phanquyen` varchar(30) DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `khachhang`
--

INSERT INTO `khachhang` (`idkh`, `email`, `password`, `hodem`, `ten`, `diachi`, `diachinhanhang`, `dienthoai`, `phanquyen`) VALUES
(1, 'test@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'Nguyen Van', 'An', 'TP HCM', 'TP HCM', '0901234567', 'admin'),
(2, 'admin@gmail.com', '0192023a7bbd73250516f069df18b500', 'Quản Trị', 'Viên', 'TP HCM', 'TP HCM', '0988888888', 'admin'),
(3, 'test12@gmail.com', '4297f44b13955235245b2497399d7a93', 'sa', 'sa', 'sa', 'sa', '0559657785', 'admin'),
(4, 'test2@gmail.com', '4297f44b13955235245b2497399d7a93', 'as', 'sa', 'sa', 'sa', '0559657785', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `sanpham`
--

CREATE TABLE `sanpham` (
  `idsp` int(11) NOT NULL,
  `tensp` varchar(150) NOT NULL,
  `gia` decimal(15,2) NOT NULL,
  `mota` text DEFAULT NULL,
  `hinh` varchar(255) DEFAULT NULL,
  `giamgia` decimal(5,2) DEFAULT 0.00,
  `idcty` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sanpham`
--

INSERT INTO `sanpham` (`idsp`, `tensp`, `gia`, `mota`, `hinh`, `giamgia`, `idcty`) VALUES
(1, 'Sam sung J7 pro', 100.00, 'Điện thoại Samsung J7 pro chính hãng', 'j7pro.jpg', 0.00, 1),
(2, 'Samsung Galaxy S7', 150.00, 'Điện thoại Samsung Galaxy S7 cấu hình tốt', 's7.jpg', 5.00, 1),
(3, 'Xiaomi 125', 300.00, 'Điện thoại Xiaomi 125 giá rẻ', 'xiaomi125.jpg', 0.00, 2),
(4, 'Iphone 12 (98%)', 500.00, 'Điện thoại Iphone 12 cũ 98%', 'ip12.jpg', 10.00, 3),
(5, 'Iphone 14 pro', 800.00, 'Điện thoại Iphone 14 pro cao cấp', 'ip14pro.jpg', 0.00, 3),
(6, 'Xiaomi Redmi 10', 800.00, 'Điện thoại Xiaomi Redmi 10 pin trâu', 'redmi10.jpg', 0.00, 2),
(7, 'Iphone 15 pro', 1000.00, 'Điện thoại Iphone 15 pro mới nhất', 'ip15pro.jpg', 0.00, 3),
(8, 'S5 Galaxy A15', 1100.00, 'Điện thoại Samsung Galaxy A15', 'a15.jpg', 0.00, 1),
(9, '16prm11', 50003.00, 'acsakk', '1793265161_ip12.jpg', 0.00, 2),
(10, '16prm', 5000.00, 'acsa', '', 0.00, 3),
(12, 'samsung1', 100.00, 'asad', '1793267964_redmi10.jpg', 0.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `taikhoan`
--

CREATE TABLE `taikhoan` (
  `iduser` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `hodem` varchar(50) DEFAULT NULL,
  `ten` varchar(50) DEFAULT NULL,
  `phanquyen` varchar(30) DEFAULT NULL,
  `landangnhapcuoi` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `congty`
--
ALTER TABLE `congty`
  ADD PRIMARY KEY (`idcty`);

--
-- Indexes for table `dathang`
--
ALTER TABLE `dathang`
  ADD PRIMARY KEY (`iddh`);

--
-- Indexes for table `dathang_chitiet`
--
ALTER TABLE `dathang_chitiet`
  ADD PRIMARY KEY (`iddh`,`idsp`);

--
-- Indexes for table `khachhang`
--
ALTER TABLE `khachhang`
  ADD PRIMARY KEY (`idkh`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`idsp`);

--
-- Indexes for table `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD PRIMARY KEY (`iduser`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `congty`
--
ALTER TABLE `congty`
  MODIFY `idcty` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dathang`
--
ALTER TABLE `dathang`
  MODIFY `iddh` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `khachhang`
--
ALTER TABLE `khachhang`
  MODIFY `idkh` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sanpham`
--
ALTER TABLE `sanpham`
  MODIFY `idsp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `taikhoan`
--
ALTER TABLE `taikhoan`
  MODIFY `iduser` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
