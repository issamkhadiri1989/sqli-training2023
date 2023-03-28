<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Factory\CategoryFactory;
use App\Factory\ProductFactory;
use App\Factory\StoreFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private const COUNT_CATEGORIES = 7;
    private const COUNT_PRODUCTS = 50;
    private const COUNT_STORES = 18;
    private const COUNT_USERS = 10;

    public function load(ObjectManager $manager): void
    {
        UserFactory::createMany(self::COUNT_USERS);
        CategoryFactory::createMany(self::COUNT_CATEGORIES);
        StoreFactory::createMany(self::COUNT_STORES, function () {
            return ['owner' => UserFactory::random()];
        });

        ProductFactory::createMany(
            self::COUNT_PRODUCTS,
            function () {
                return ['category' => CategoryFactory::random(), 'store' => StoreFactory::random()];
            });

        $manager->flush();
    }
}
