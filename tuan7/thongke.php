<?php
include_once("classtmdt/clskhachhang.php");
$p = new khachhang();

if (!$p->isAdmin()) {
    header("Location: index.php");
    echo "<script>window.location.href='index.php';</script>";
    exit();
}

// 1. Du lieu bieu do mua hang
$thongkemuahang = $p->thongkemuahang();
$label = '';
$data = '';
for ($i = 0; $i < count($thongkemuahang); $i++) {
    $ngay = date("j-n-Y", strtotime($thongkemuahang[$i]['ngaydat']));
    $label .= "'Ngày: " . $ngay . "',";
    $data .= $thongkemuahang[$i]['tongsoluong'] . ",";
}

// 2. Thong ke doanh thu theo Ngay / Thang / Nam
$loai_tk = isset($_GET["loai"]) ? $_GET["loai"] : "thang";

if ($loai_tk == "ngay") {
    $ds_doanhthu = $p->ThongKeDoanhThuTheoNgay();
    $tieude_cot = "Ngày";
} else if ($loai_tk == "nam") {
    $ds_doanhthu = $p->ThongKeDoanhThuTheoNam();
    $tieude_cot = "Năm";
} else {
    $loai_tk = "thang";
    $ds_doanhthu = $p->ThongKeDoanhThuTheoThang();
    $tieude_cot = "Tháng (Năm-Tháng)";
}
?>

<!-- Vung bieu do canvas lich su mua hang (Hinh 7.1) -->
<div class="container" style="width: 610px; margin: 15px auto; text-align: center;">
    <canvas id="canvas" height="450" width="610" style="border: 1px solid #666;"></canvas>
</div>

<!-- Bang thong ke doanh thu don gian theo Ngay / Thang / Nam -->
<div style="width: 610px; margin: 25px auto 30px auto; font-family: Arial, sans-serif;">
    <h3 style="text-align: center; margin-bottom: 10px; text-transform: uppercase;">Thống kê doanh thu bán hàng</h3>
    
    <div style="text-align: center; margin-bottom: 15px;">
        <strong>Xem theo: </strong>
        <a href="index.php?page=thongke&loai=ngay" style="<?php echo ($loai_tk == 'ngay') ? 'font-weight:bold; color:red;' : 'color:#036;'; ?>">[ Theo Ngày ]</a> &nbsp;|&nbsp;
        <a href="index.php?page=thongke&loai=thang" style="<?php echo ($loai_tk == 'thang') ? 'font-weight:bold; color:red;' : 'color:#036;'; ?>">[ Theo Tháng ]</a> &nbsp;|&nbsp;
        <a href="index.php?page=thongke&loai=nam" style="<?php echo ($loai_tk == 'nam') ? 'font-weight:bold; color:red;' : 'color:#036;'; ?>">[ Theo Năm ]</a>
    </div>

    <table border="1" cellpadding="6" cellspacing="0" width="100%" style="border-collapse: collapse; text-align: center; font-size: 13px;">
        <tr style="background: #e9e9e9; font-weight: bold;">
            <th width="40">STT</th>
            <th><?php echo $tieude_cot; ?></th>
            <th>Số đơn hàng</th>
            <th>Số lượng SP bán ra</th>
            <th>Doanh thu (USD)</th>
        </tr>
        <?php
        $tong_don = 0;
        $tong_sp  = 0;
        $tong_tien = 0;

        if (count($ds_doanhthu) > 0) {
            for ($i = 0; $i < count($ds_doanhthu); $i++) {
                $row = $ds_doanhthu[$i];
                $tong_don += $row["sodonhang"];
                $tong_sp  += $row["tongsoluong"];
                $tong_tien += $row["doanhthu"];
        ?>
            <tr>
                <td><?php echo ($i + 1); ?></td>
                <td><strong><?php echo $row["thoigian"]; ?></strong></td>
                <td><?php echo $row["sodonhang"]; ?></td>
                <td><?php echo $row["tongsoluong"]; ?></td>
                <td style="text-align: right; font-weight: bold; color: #c00;">
                    <?php echo number_format($row["doanhthu"], 0, ".", ","); ?> USD
                </td>
            </tr>
        <?php
            }
        ?>
            <tr style="background: #f5f5f5; font-weight: bold;">
                <td colspan="2" style="text-align: right;">TỔNG CỘNG:</td>
                <td><?php echo $tong_don; ?> đơn</td>
                <td><?php echo $tong_sp; ?> SP</td>
                <td style="text-align: right; color: #006600; font-size: 14px;">
                    <?php echo number_format($tong_tien, 0, ".", ","); ?> USD
                </td>
            </tr>
        <?php } else { ?>
            <tr>
                <td colspan="5">Không có dữ liệu thống kê.</td>
            </tr>
        <?php } ?>
    </table>
</div>

<script>
// Ve truc tiep tren Canvas HTML5 theo Hinh 7.1
function Chart(ctx) {
    this.Line = function(chartData, options) {
        var cv = ctx.canvas;
        var w = cv.width, h = cv.height;
        var pL = 50, pR = 25, pT = 55, pB = 45;
        var pw = w - pL - pR, ph = h - pT - pB;
        var labels = chartData.labels || [];
        var dataset = (chartData.datasets && chartData.datasets[0]) ? chartData.datasets[0] : { data: [] };
        var pts = dataset.data || [];

        // Nen xam
        ctx.fillStyle = "#8e9a9f";
        ctx.fillRect(0, 0, w, h);

        // Tieu de bieu do
        ctx.fillStyle = "#111";
        ctx.font = "bold 20px Arial, sans-serif";
        ctx.textAlign = "center";
        ctx.textBaseline = "middle";
        ctx.fillText("THỐNG KÊ MUA HÀNG", w / 2, 28);

        if (!pts.length) {
            ctx.fillStyle = "#222";
            ctx.font = "14px Arial, sans-serif";
            ctx.textAlign = "center";
            ctx.fillText("Chưa có dữ liệu đặt hàng", w / 2, h / 2);
            return;
        }

        // Tinh truc Y voi 2 so thap phan
        var max = Math.max.apply(null, pts);
        var min = Math.min.apply(null, pts);
        var step = 0.25;
        if (max - min > 5) step = 0.5;
        if (max - min > 10) step = 1.0;

        var startY = Math.max(0, Math.floor((min - step) / step) * step);
        var endY = Math.ceil((max + step) / step) * step;
        var steps = Math.round((endY - startY) / step);

        ctx.font = "11px Arial, sans-serif";
        ctx.fillStyle = "#111";
        ctx.textAlign = "right";
        ctx.textBaseline = "middle";

        for (var i = 0; i <= steps; i++) {
            var val = startY + i * step;
            var y = h - pB - (i / steps) * ph;
            ctx.fillText(val.toFixed(2), pL - 8, y);

            ctx.beginPath();
            ctx.moveTo(pL, y);
            ctx.lineTo(w - pR, y);
            ctx.strokeStyle = "rgba(0, 0, 0, 0.08)";
            ctx.lineWidth = 1;
            ctx.stroke();
        }

        // Toa do cac diem
        var n = pts.length;
        var coords = [];
        for (var i = 0; i < n; i++) {
            var x = pL + (n > 1 ? (i / (n - 1)) * pw : pw / 2);
            var y = h - pB - ((pts[i] - startY) / (endY - startY)) * ph;
            coords.push({ x: x, y: y });

            ctx.save();
            ctx.translate(x, h - pB + 14);
            if (n > 6) {
                ctx.rotate(-Math.PI / 10);
                ctx.textAlign = "right";
            } else {
                ctx.textAlign = "center";
            }
            ctx.fillStyle = "#111";
            ctx.font = "9px Arial, sans-serif";
            ctx.fillText(labels[i], 0, 0);
            ctx.restore();
        }

        // Ve duong cong Bezier & do bong
        ctx.beginPath();
        ctx.moveTo(coords[0].x, h - pB);
        ctx.lineTo(coords[0].x, coords[0].y);
        for (var i = 0; i < coords.length - 1; i++) {
            var cpX = (coords[i].x + coords[i + 1].x) / 2;
            ctx.bezierCurveTo(cpX, coords[i].y, cpX, coords[i + 1].y, coords[i + 1].x, coords[i + 1].y);
        }
        ctx.lineTo(coords[coords.length - 1].x, h - pB);
        ctx.closePath();
        ctx.fillStyle = dataset.fillColor || "rgba(75, 115, 115, 0.5)";
        ctx.fill();

        // Duong vien trang
        ctx.beginPath();
        ctx.moveTo(coords[0].x, coords[0].y);
        for (var i = 0; i < coords.length - 1; i++) {
            var cpX = (coords[i].x + coords[i + 1].x) / 2;
            ctx.bezierCurveTo(cpX, coords[i].y, cpX, coords[i + 1].y, coords[i + 1].x, coords[i + 1].y);
        }
        ctx.strokeStyle = dataset.strokeColor || "rgba(255,255,255,0.8)";
        ctx.lineWidth = 2;
        ctx.stroke();

        // Cham tron
        for (var i = 0; i < coords.length; i++) {
            ctx.beginPath();
            ctx.arc(coords[i].x, coords[i].y, 2.5, 0, Math.PI * 2);
            ctx.fillStyle = dataset.pointColor || "#333";
            ctx.fill();
        }
    };
    return this;
}

// Goi ve theo Hinh 7.17
var LineChart = {
    labels : [<?php echo $label; ?>],
    datasets : 
    [
        {
            fillColor : "rgba(151,249,190,0.5)",
            strokeColor : "rgba(255,255,255,1)",
            pointColor : "rgba(220,220,220,1)",
            pointStrokeColor : "#fff",
            data : [<?php echo $data; ?>]
        }
    ]
};

var myLineChart = new Chart(document.getElementById("canvas").getContext("2d")).Line(LineChart, {
    scaleFontSize : 13, 
    scaleFontColor : "#ffa45e"
});
</script>