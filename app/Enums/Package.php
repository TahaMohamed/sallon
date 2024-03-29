<?php

namespace App\Enums;

enum Package: string
{
    case YEARLY = 'yearly';
    case MONTHLY = 'monthly';
    case FOREVER = 'forever';

    public static function casesValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function yearly(): string
    {
        return self::YEARLY->value;
    }

    public static function monthly(): string
    {
        return self::MONTHLY->value;
    }

    public static function forever(): string
    {
        return self::FOREVER->value;
    }
}

