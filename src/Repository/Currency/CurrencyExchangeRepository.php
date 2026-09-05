<?php

declare(strict_types=1);

namespace App\Repository\Currency;

use App\Entity\Currency\CurrencyExchangeEntity;
use App\RepositoryInterface\Currency\CurrencyExchangeRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<CurrencyExchangeEntity> */
final class CurrencyExchangeRepository extends ServiceEntityRepository implements CurrencyExchangeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CurrencyExchangeEntity::class);
    }

    public function save(CurrencyExchangeEntity $exchange): void
    {
        $this->getEntityManager()->persist($exchange);
        $this->getEntityManager()->flush();
    }

    public function findLatestRate(string $baseCurrencyCode, string $targetCurrencyCode): ?CurrencyExchangeEntity
    {
        return $this->createQueryBuilder('exchangeRate')
            ->andWhere('exchangeRate.baseCurrencyCode = :baseCurrencyCode')
            ->andWhere('exchangeRate.targetCurrencyCode = :targetCurrencyCode')
            ->setParameter('baseCurrencyCode', strtoupper($baseCurrencyCode))
            ->setParameter('targetCurrencyCode', strtoupper($targetCurrencyCode))
            ->orderBy('exchangeRate.effectiveAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
