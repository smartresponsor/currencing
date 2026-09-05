<?php

declare(strict_types=1);

namespace App\Repository\Currency;

use App\Entity\Currency\CurrencyEntity;
use App\RepositoryInterface\Currency\CurrencyRepositoryInterface;
use App\ValueObject\Currency\CurrencyCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CurrencyEntity>
 */
final class CurrencyRepository extends ServiceEntityRepository implements CurrencyRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CurrencyEntity::class);
    }

    public function findOneByCode(string|CurrencyCode $code): ?CurrencyEntity
    {
        return $this->findOneBy(['code' => $this->normalizeCode($code)]);
    }

    public function findOneActiveByCode(string|CurrencyCode $code): ?CurrencyEntity
    {
        return $this->findOneBy(['code' => $this->normalizeCode($code), 'active' => true]);
    }

    public function hasActiveCode(string|CurrencyCode $code): bool
    {
        return $this->createQueryBuilder('currency')
            ->select('COUNT(currency.id)')
            ->andWhere('currency.code = :code')
            ->andWhere('currency.active = true')
            ->setParameter('code', $this->normalizeCode($code))
            ->getQuery()
            ->getSingleScalarResult() > 0;
    }

    /**
     * @return list<CurrencyEntity>
     */
    public function findActiveOrderedByCode(): array
    {
        return $this->createQueryBuilder('currency')
            ->andWhere('currency.active = true')
            ->orderBy('currency.code', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return list<string>
     */
    public function findActiveCodesOrdered(): array
    {
        $rows = $this->createQueryBuilder('currency')
            ->select('currency.code')
            ->andWhere('currency.active = true')
            ->orderBy('currency.code', 'ASC')
            ->getQuery()
            ->getScalarResult();

        return array_values(array_map(static fn (array $row): string => (string) $row['code'], $rows));
    }

    private function normalizeCode(string|CurrencyCode $code): string
    {
        return $code instanceof CurrencyCode ? $code->value() : CurrencyCode::fromString($code)->value();
    }
}
