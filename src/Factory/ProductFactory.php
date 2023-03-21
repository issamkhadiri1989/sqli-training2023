<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Product>
 *
 * @method        Product|Proxy create(array|callable $attributes = [])
 * @method static Product|Proxy createOne(array $attributes = [])
 * @method static Product|Proxy find(object|array|mixed $criteria)
 * @method static Product|Proxy findOrCreate(array $attributes)
 * @method static Product|Proxy first(string $sortedField = 'id')
 * @method static Product|Proxy last(string $sortedField = 'id')
 * @method static Product|Proxy random(array $attributes = [])
 * @method static Product|Proxy randomOrCreate(array $attributes = [])
 * @method static ProductRepository|RepositoryProxy repository()
 * @method static Product[]|Proxy[] all()
 * @method static Product[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Product[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Product[]|Proxy[] findBy(array $attributes)
 * @method static Product[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Product[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class ProductFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    protected function getDefaults(): array
    {
        return [
            'fullDescription' => self::faker()->text(),
            'name' => self::faker()->text(20),
            'quantity' => \rand(0, 100),
            'shortDescription' => self::faker()->text(60),
            'unitPrice' => self::faker()->randomFloat(min: 10, max: 999, nbMaxDecimals: 2),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Product $product): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Product::class;
    }
}
