<?php

namespace App\Enums;

enum TicketStatusEnum: int
{
    case NEW      = 1;
    case OPEN     = 2;
    case CLOSED   = 3;
    case REOPENED = 4;


    public function label(): string
    {
        return match ($this) {
            self::NEW      => 'New',
            self::OPEN     => 'Open',
            self::CLOSED   => 'Closed',
            self::REOPENED => 'Reopened',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::NEW      => 'text-blue-500',
            self::OPEN     => 'text-yellow-500',
            self::CLOSED   => 'text-green-600',
            self::REOPENED => 'text-violet-500',
        };
    }

    public function badge(): string
    {
        return match ($this) {
            self::NEW      => 'bg-blue-500 text-blue-50',
            self::OPEN     => 'bg-yellow-500 text-yellow-50',
            self::CLOSED   => 'bg-green-600 text-green-50',
            self::REOPENED => 'bg-violet-500 text-violet-50',
        };
    }

    public static function array(): array
    {
        return [
            self::NEW->value      => self::NEW->label(),
            self::OPEN->value     => self::OPEN->label(),
            self::CLOSED->value   => self::CLOSED->label(),
            self::REOPENED->value => self::REOPENED->label(),
        ];
    }
}
