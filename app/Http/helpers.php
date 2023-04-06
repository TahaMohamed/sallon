<?php

use Illuminate\Support\Facades\File;

if (!function_exists('convert_arabic_number')) {
    function convert_arabic_number($number)
    {
        $arabic_array = ['۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9', '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9'];
        return strtr($number, $arabic_array);
    }
}

if (!function_exists('upload_image')) {
    function upload_image($image, $directory = null, $width = null, $height = null)
    {
        $fullDirectory = storage_path('app/public/' . $directory);
        if (!File::isDirectory($fullDirectory)){
            File::makeDirectory($fullDirectory,recursive: true);
        }
        $name = $image->hashName;
        $image->move($fullDirectory, $name);
        return $directory . DIRECTORY_SEPARATOR . $name;
    }
}
