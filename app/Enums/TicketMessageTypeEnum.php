<?php

namespace App\Enums;

enum TicketMessageTypeEnum: int
{
    case USER  = 1;
    case AGENT = 2;

    public static function toArray(): array
    {
        return [
            self::USER->value  => 'User',
            self::AGENT->value => 'Agent',
        ];
    }

    public function label(): string
    {
        return match ($this) {
            self::USER  => 'user',
            self::AGENT => 'agent',
        };
    }
}
