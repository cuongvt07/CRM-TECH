<?php

namespace App\Helpers;

class Helper
{
    /**
     * Định dạng số sạch theo chuẩn Việt Nam
     * - Dấu '.' cho hàng nghìn
     * - Dấu ',' cho thập phân
     * - Bỏ .00 nếu là số nguyên
     */
    public static function nfmt($val)
    {
        if ($val === null || $val === '') return '0';
        
        $floatVal = (float)$val;
        
        // Kiểm tra xem có phần thập phân không
        $decimals = (floor($floatVal) == $floatVal) ? 0 : 2;
        
        return number_format($floatVal, $decimals, ',', '.');
    }
}
