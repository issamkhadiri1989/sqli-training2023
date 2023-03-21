<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Factory\CategoryFactory;
use App\Factory\ProductFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private const COUNT_CATEGORIES = 7;
    private const COUNT_PRODUCTS = 50;

    public function load(ObjectManager $manager): void
    {
        CategoryFactory::createMany(self::COUNT_CATEGORIES);

        ProductFactory::createMany(
            self::COUNT_PRODUCTS,
            function () {
                return ['category' => CategoryFactory::random()];
            });

        $manager->flush();
    }
}
