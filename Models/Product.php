<?php

class Product
{
    private static $dph = 0.19;
    public static function countDph($value) : int
    {
         return round($value + ($value * Product::$dph));        
    }
    public static function getDph() : float
    {
        return Product::$dph + 1;
    }
}