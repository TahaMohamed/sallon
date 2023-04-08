<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

if (!function_exists('convert_arabic_number')) {
    function convert_arabic_number($number)
    {
        $arabic_array = ['۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9', '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9'];
        return strtr($number, $arabic_array);
    }
}

if (!function_exists('random_code')) {
    function random_code($length = 4, $letters = true, $numbers = true, $symbols = true)
    {
        return (new Collection)
            ->when($letters, fn($c) => $c->merge([
                'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k',
                'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v',
                'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G',
                'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R',
                'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
            ]))
            ->when($numbers, fn($c) => $c->merge([
                '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
            ]))
            ->when($symbols, fn($c) => $c->merge([
                '~', '!', '#', '$', '%', '^', '&', '*', '(', ')', '-',
                '_', '.', ',', '<', '>', '?', '/', '\\', '{', '}', '[',
                ']', '|', ':', ';',
            ]))
            ->pipe(fn($c) => Collection::times($length, fn() => $c[random_int(0, $c->count() - 1)]))
            ->implode('');
    }
}

if (!function_exists('generate_unique_code')) {
    function generate_unique_code($model, $col = 'code', $length = 4, $letters = true, $numbers = true, $symbols = true)
    {
        $random_code = random_code($length, $letters, $numbers, $symbols);

        if ($model::where($col, $random_code)->exists()) {
            generate_unique_code($model, $col, $length, $letters, $numbers, $symbols);
        }
        return $random_code;
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
