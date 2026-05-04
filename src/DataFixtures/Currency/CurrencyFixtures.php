<?php

declare(strict_types=1);

namespace App\DataFixtures\Currency;

use App\Entity\Currency\Currency;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class CurrencyFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $repository = $manager->getRepository(Currency::class);

        foreach ($this->currencies() as $row) {
            $currency = $repository->findOneBy(['code' => $row['code']]);

            if (!$currency instanceof Currency) {
                $currency = new Currency($row['code']);
                $manager->persist($currency);
            }

            $currency
                ->setNumericCode($row['numericCode'])
                ->setMinorUnit($row['minorUnit'])
                ->setSymbol($row['symbol'])
                ->setDisplayName($row['displayName'])
                ->setActive(true);

            $this->addReference('currency_' . $row['code'], $currency);
        }

        $manager->flush();
    }

    /**
     * @return list<array{code:string,numericCode:string,minorUnit:int,symbol:string,displayName:string}>
     */
    private function currencies(): array
    {
        return [
            ['code' => 'USD', 'numericCode' => '840', 'minorUnit' => 2, 'symbol' => '$', 'displayName' => 'US Dollar'],
            ['code' => 'EUR', 'numericCode' => '978', 'minorUnit' => 2, 'symbol' => '€', 'displayName' => 'Euro'],
            ['code' => 'UAH', 'numericCode' => '980', 'minorUnit' => 2, 'symbol' => '₴', 'displayName' => 'Ukrainian Hryvnia'],
            ['code' => 'GBP', 'numericCode' => '826', 'minorUnit' => 2, 'symbol' => '£', 'displayName' => 'Pound Sterling'],
            ['code' => 'JPY', 'numericCode' => '392', 'minorUnit' => 0, 'symbol' => '¥', 'displayName' => 'Japanese Yen'],
            ['code' => 'CAD', 'numericCode' => '124', 'minorUnit' => 2, 'symbol' => '$', 'displayName' => 'Canadian Dollar'],
            ['code' => 'AUD', 'numericCode' => '036', 'minorUnit' => 2, 'symbol' => '$', 'displayName' => 'Australian Dollar'],
            ['code' => 'CHF', 'numericCode' => '756', 'minorUnit' => 2, 'symbol' => 'CHF', 'displayName' => 'Swiss Franc'],
        ];
    }
}
