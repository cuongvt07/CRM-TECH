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
        
        // Định dạng với tối đa 2 chữ số thập phân
        $formatted = number_format($floatVal, 2, ',', '.');
        
        // Nếu có dấu phẩy (thập phân), thực hiện rtrim số 0 và dấu phẩy thừa
        if (strpos($formatted, ',') !== false) {
            $formatted = rtrim(rtrim($formatted, '0'), ',');
        }
        
        return $formatted;
    }
}
