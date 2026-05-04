<?php

declare(strict_types=1);

namespace App\Enum\Currency;

/**
 * Business-level rounding modes used when decimal input has more fractional
 * digits than the target currency supports.
 */
enum MoneyRoundingMode: string
{
    case Reject = 'reject';
    case HalfUp = 'half_up';
    case Down = 'down';
    case Up = 'up';
}
