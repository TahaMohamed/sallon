<?php

namespace App\Enums;

enum WeekDays: string
{
    case SAT = 'sat';
    case SUN = 'sun';
    case MON = 'mon';
    case TUE = 'tue';
    case WED = 'wed';
    case THU = 'thu';
    case FRI = 'fri';

    public static function casesValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function saturday(): string
    {
        return self::SAT->value;
    }

    public static function sunday(): string
    {
        return self::SUN->value;
    }

    public static function monday(): string
    {
        return self::MON->value;
    }

    public static function tuesday(): string
    {
        return self::TUE->value;
    }

    public static function wednesday(): string
    {
        return self::WED->value;
    }

    public static function thursday(): string
    {
        return self::THU->value;
    }

    public static function friday(): string
    {
        return self::FRI->value;
    }
}

