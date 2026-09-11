<?php

declare(strict_types=1);

namespace App\Currencing\Policy;

/**
 * Lifecycle guard for currency.
 *
 * The policy is intentionally framework-free: entities/services can call it
 * without introducing cross-component Doctrine dependencies.
 */
final class CurrencyLifecyclePolicy
{
    /** @var array<string, list<string>> */
    private const TRANSITIONS = [
        'draft' => ['active', 'deprecated'],
        'active' => ['restricted', 'deprecated', 'archived'],
        'restricted' => ['active', 'deprecated', 'archived'],
        'deprecated' => ['archived'],
        'archived' => [],
    ];

    public function canTransition(string $from, string $to): bool
    {
        $from = self::normalize($from);
        $to = self::normalize($to);

        if ($from === $to) {
            return true;
        }

        return \in_array($to, self::TRANSITIONS[$from] ?? [], true);
    }

    public function assertCanTransition(string $from, string $to): void
    {
        if (!$this->canTransition($from, $to)) {
            throw new \DomainException(sprintf('Invalid currency lifecycle transition from "%s" to "%s".', $from, $to));
        }
    }

    /** @return list<string> */
    public function allowedNextStatuses(string $from): array
    {
        return self::TRANSITIONS[self::normalize($from)] ?? [];
    }

    private static function normalize(string $status): string
    {
        return strtolower(trim($status));
    }
}
