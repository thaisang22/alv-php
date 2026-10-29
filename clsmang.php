<?php
class xulymang
{
    public static function nhapMang($mang, $n)
    {
        for ($i = 0; $i < $n; $i++) {
            $mang[$i] = rand(1, 100);
        }
        return $mang;
    }

    public static function xuatMang($mang, $n)
    {
        echo '<div class="box">';

        for ($i = 0; $i < $n; $i++) {
            echo '<div class="box-item">';
            echo $mang[$i];
            echo '</div>';
        }
        echo '</div>';
    }
    public static function timMax($mang, $n)
    {
        $max = $mang[0];

        for ($i = 1; $i < $n; $i++) {
            if ($mang[$i] > $max) {
                $max = $mang[$i];
            }
        }

        return $max;
    }

    public static function timMin($mang, $n)
    {
        $min = $mang[0];

        for ($i = 1; $i < $n; $i++) {
            if ($mang[$i] < $min) {
                $min = $mang[$i];
            }
        }

        return $min;
    }

    public static function demLe($mang, $n)
    {
        $dem = 0;

        for ($i = 0; $i < $n; $i++) {
            if ($mang[$i] % 2 != 0) {
                $dem++;
            }
        }

        return $dem;
    }

}



?>