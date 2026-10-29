<?php

class clsPhepTinh
{
    public function cong(float $a, float $b): float
    {
        return $a + $b;
    }

    public function tru(float $a, float $b): float
    {
        return $a - $b;
    }

    public function nhan(float $a, float $b): float
    {
        return $a * $b;
    }

    public function chia(float $a, float $b)
    {
        if ($b == 0) {
            return "Khong the chia cho 0";
        }

        return $a / $b;
    }

    public function tinh(float $a, float $b, string $pheptinh)
    {
        switch ($pheptinh) {
            case '+':
                return $this->cong($a, $b);

            case '-':
                return $this->tru($a, $b);

            case '*':
                return $this->nhan($a, $b);

            case '/':
                return $this->chia($a, $b);

            default:
                return "Phep tinh khong hop le";
        }
    }
}