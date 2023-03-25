<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Store;
use App\Repository\StoreRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Store>
 *
 * @method        Store|Proxy create(array|callable $attributes = [])
 * @method static Store|Proxy createOne(array $attributes = [])
 * @method static Store|Proxy find(object|array|mixed $criteria)
 * @method static Store|Proxy findOrCreate(array $attributes)
 * @method static Store|Proxy first(string $sortedField = 'id')
 * @method static Store|Proxy last(string $sortedField = 'id')
 * @method static Store|Proxy random(array $attributes = [])
 * @method static Store|Proxy randomOrCreate(array $attributes = [])
 * @method static StoreRepository|RepositoryProxy repository()
 * @method static Store[]|Proxy[] all()
 * @method static Store[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Store[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Store[]|Proxy[] findBy(array $attributes)
 * @method static Store[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Store[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class StoreFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'address' => self::faker()->text(),
            'name' => self::faker()->text(\rand(6, 10)),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Store $store): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Store::class;
    }
}
