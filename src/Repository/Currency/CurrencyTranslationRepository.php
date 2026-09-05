<?php

declare(strict_types=1);

namespace App\Repository\Currency;

use App\Entity\Currency\CurrencyTranslationEntity;
use App\RepositoryInterface\Currency\CurrencyTranslationRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<CurrencyTranslationEntity> */
final class CurrencyTranslationRepository extends ServiceEntityRepository implements CurrencyTranslationRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CurrencyTranslationEntity::class);
    }

    public function save(CurrencyTranslationEntity $translation): void
    {
        $this->getEntityManager()->persist($translation);
        $this->getEntityManager()->flush();
    }

    public function findOneByCurrencyIdAndLocale(int $currencyId, string $locale): ?CurrencyTranslationEntity
    {
        return $this->createQueryBuilder('translation')
            ->andWhere('IDENTITY(translation.currency) = :currencyId')
            ->andWhere('translation.objectLocale.objectLocale = :locale')
            ->setParameter('currencyId', $currencyId)
            ->setParameter('locale', $locale)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /** @return list<CurrencyTranslationEntity> */
    public function findByCurrencyId(int $currencyId): array
    {
        return $this->createQueryBuilder('translation')
            ->andWhere('IDENTITY(translation.currency) = :currencyId')
            ->setParameter('currencyId', $currencyId)
            ->orderBy('translation.objectLocale.objectLocale', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
