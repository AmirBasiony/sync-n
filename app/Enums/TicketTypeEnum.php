<?php

namespace App\Enums;

enum TicketTypeEnum: int
{
    case INSTALLATION = 1;
    case SOFTWARE     = 2;
    case CONNECTION   = 3;
    case DEVICE       = 4;
    case BILLING      = 5;
    case PAYMENT      = 6;
    case OTHER        = 7;

    public function label(): string
    {
        return match ($this) {
            self::INSTALLATION => 'Installation',
            self::SOFTWARE     => 'Software',
            self::CONNECTION   => 'Connection',
            self::DEVICE       => 'Device',
            self::BILLING      => 'Billing',
            self::PAYMENT      => 'Payment',
            self::OTHER        => 'Other',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::INSTALLATION => 'primary',
            self::SOFTWARE     => 'success',
            self::CONNECTION   => 'info',
            self::DEVICE       => 'warning',
            self::BILLING      => 'danger',
            self::PAYMENT      => 'danger',
            self::OTHER        => 'secondary',
        };
    }

    public static function array(): array
    {
        return [
            self::INSTALLATION->value => self::INSTALLATION->label(),
            self::SOFTWARE->value     => self::SOFTWARE->label(),
            self::CONNECTION->value   => self::CONNECTION->label(),
            self::DEVICE->value       => self::DEVICE->label(),
            self::BILLING->value      => self::BILLING->label(),
            self::PAYMENT->value      => self::PAYMENT->label(),
            self::OTHER->value        => self::OTHER->label(),
        ];
    }
}
