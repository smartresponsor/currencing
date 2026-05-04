<?php

declare(strict_types=1);

namespace App\Enum\Currency;

/**
 * Business contexts that may require different monetary rounding policies.
 *
 * The enum is intentionally business-facing and does not model exchange-rate or
 * payment-provider behavior. FX conversion remains outside Currencing.
 */
enum MoneyRoundingContext: string
{
    case Canonical = 'canonical';
    case Ordering = 'ordering';
    case Paying = 'paying';
    case Taxating = 'taxating';
    case Discounting = 'discounting';
    case Subscription = 'subscription';
    case Formatting = 'formatting';
    case Cash = 'cash';
}
