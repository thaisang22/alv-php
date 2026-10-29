<?php
session_start();
include("classtmdt/clskhachhang.php");

$p = new khachhang();

// KIEM TRA PHAN QUYEN ADMIN
if (!isset($_SESSION["khachhang"])) {
    header("Location: dangnhap.php?yeucau=admin");
    exit();
}

$is_admin = $p->isAdmin();

$label = "";
$data  = "";
$thongkemuahang = array();

if ($is_admin) {
    // Lay so lieu thong ke mua hang (Hinh 7.16)
    $thongkemuahang = $p->thongkemuahang();

    for ($i = 0; $i < count($thongkemuahang); $i++) {
        $label .= "'" . $thongkemuahang[$i]["ngaydat"] . "',";
        $data  .= $thongkemuahang[$i]["tongsoluong"] . ",";
    }

    $label = rtrim($label, ",");
    $data  = rtrim($data, ",");
}

$congty = $p->XemDSCongTy();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thống kê mua hàng - Quản trị</title>
    <link rel="stylesheet" href="style/styletmdt.css">
    <script src="js/chart.min.js"></script>
    <style>
        .chart-box {
            background: #fff;
            border: 1px solid #ccc;
            padding: 20px;
            margin: 10px auto;
            max-width: 720px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .chart-box h2 {
            text-align: center;
            color: #336699;
            margin-bottom: 15px;
            text-transform: uppercase;
            font-size: 18px;
        }
        .chart-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }
        .chart-table th, .chart-table td {
            border: 1px solid #ccc;
            padding: 8px 12px;
            text-align: center;
            font-size: 13px;
        }
        .chart-table th {
            background: #336699;
            color: #fff;
        }
        .chart-table tr:nth-child(even) {
            background: #f9f9f9;
        }
        .badge-admin {
            background: #c0392b;
            color: #fff;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
<div class="wrapper">

    <div class="banner">BANNER WEBSITE - BÁO CÁO THỐNG KÊ</div>

    <div class="main-container">

        <!-- Menu trai -->
        <div class="menu">
            <h3>MENU</h3>
            <ul>
                <li><a href="index.php">Trang chủ</a></li>
                <li><a href="quantri.php" style="font-weight:bold; color:#c00;">Quản lý sản phẩm</a></li>
                <li><a href="thongke.php" style="font-weight:bold; color:#0055aa;">Biểu đồ thống kê</a></li>
                <li><a href="giohang.php">Giỏ hàng (<?php echo isset($_SESSION["giohang"]) ? count($_SESSION["giohang"]) : 0; ?>)</a></li>
            </ul>
            <br>
            <div style="border-top:1px solid #ccc; padding-top:10px;">
                <?php if (isset($_SESSION["khachhang"])) { ?>
                    <p style="font-size:12px; margin-bottom:5px;">
                        Chào, <strong><?php echo $_SESSION["khachhang"]["ten"]; ?></strong>
                        <?php if ($is_admin) { ?>
                            <span class="badge-admin">Admin</span>
                        <?php } ?>
                    </p>
                    <a href="dangxuat.php" class="btn btn-red" style="font-size:11px; padding:3px 6px;">Đăng xuất</a>
                <?php } ?>
            </div>
        </div>

        <!-- Vung ve bieu do (Hinh 7.17) -->
        <div class="content">

            <?php if (!$is_admin) { ?>
                <!-- THONG BAO KHONG CO QUYEN ADMIN -->
                <div style="padding: 25px; background: #fff3cd; border: 1px solid #ffeeba; color: #856404; text-align: center; margin: 30px auto; max-width: 600px;">
                    <h3 style="color: #c0392b; margin-bottom: 10px;">TRUY CẬP BỊ TỪ CHỐI (ACCESS DENIED)</h3>
                    <p style="font-size: 14px; margin-bottom: 15px;">
                        Tài khoản hiện tại của bạn không có quyền <strong>Quản trị viên (Admin)</strong> để xem báo cáo thống kê!
                    </p>
                    <a href="dangnhap.php?yeucau=admin" class="btn">Đăng nhập tài khoản Admin</a>
                    &nbsp;&nbsp;
                    <a href="index.php" class="btn btn-green">Về trang chủ</a>
                </div>

            <?php } else { ?>

                <div class="chart-box">
                    <h2>THỐNG KÊ MUA HÀNG THEO NGÀY</h2>

                    <!-- The canvas ve bieu do theo sach -->
                    <div style="position: relative; height: 350px; width: 100%;">
                        <canvas id="canvas"></canvas>
                    </div>

                    <!-- Bang so lieu tong hop chi tiet -->
                    <h3 style="margin-top:20px; font-size:14px; color:#333;">Chi tiết số lượng bán theo ngày:</h3>
                    <table class="chart-table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Ngày đặt hàng</th>
                                <th>Tổng số lượng sản phẩm bán ra</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $tong_toanbo = 0;
                            for ($i = 0; $i < count($thongkemuahang); $i++) { 
                                $tong_toanbo += $thongkemuahang[$i]["tongsoluong"];
                            ?>
                            <tr>
                                <td><?php echo ($i + 1); ?></td>
                                <td><strong><?php echo $thongkemuahang[$i]["ngaydat"]; ?></strong></td>
                                <td style="color:#d9534f; font-weight:bold;"><?php echo $thongkemuahang[$i]["tongsoluong"]; ?></td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <td colspan="2" style="text-align:right; font-weight:bold;">TỔNG CỘNG:</td>
                                <td style="font-weight:bold; color:#0055aa; font-size:14px;"><?php echo $tong_toanbo; ?> sản phẩm</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Script ve bieu do Chart.js (Hinh 7.17) -->
                <script>
                var ctx = document.getElementById("canvas").getContext("2d");

                var lineChartData = {
                    labels: [<?php echo $label; ?>],
                    datasets: [{
                        label: "Tổng số lượng mua hàng",
                        data: [<?php echo $data; ?>],
                        backgroundColor: "rgba(151, 249, 190, 0.4)",
                        borderColor: "rgba(46, 139, 87, 1)",
                        borderWidth: 2,
                        pointBackgroundColor: "rgba(220, 20, 60, 1)",
                        pointBorderColor: "#fff",
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: "rgba(220, 20, 60, 1)",
                        pointRadius: 6,
                        fill: true,
                        tension: 0.3
                    }]
                };

                new Chart(ctx, {
                    type: "line",
                    data: lineChartData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    font: { size: 12 }
                                },
                                title: {
                                    display: true,
                                    text: "Số lượng (sản phẩm)"
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: "Ngày đặt hàng"
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: "top"
                            }
                        }
                    }
                });
                </script>

            <?php } ?>

        </div>

    </div>

    <div class="footer">Footer website</div>
</div>
</body>
</html>
