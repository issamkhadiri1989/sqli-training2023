<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<User>
 *
 * @method        User|Proxy create(array|callable $attributes = [])
 * @method static User|Proxy createOne(array $attributes = [])
 * @method static User|Proxy find(object|array|mixed $criteria)
 * @method static User|Proxy findOrCreate(array $attributes)
 * @method static User|Proxy first(string $sortedField = 'id')
 * @method static User|Proxy last(string $sortedField = 'id')
 * @method static User|Proxy random(array $attributes = [])
 * @method static User|Proxy randomOrCreate(array $attributes = [])
 * @method static UserRepository|RepositoryProxy repository()
 * @method static User[]|Proxy[] all()
 * @method static User[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static User[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static User[]|Proxy[] findBy(array $attributes)
 * @method static User[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static User[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class UserFactory extends ModelFactory
{
    private const ROLES = ['ROLE_STORE_KEEPER', 'ROLE_SUPERVISOR', 'ROLE_ADMIN'];
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     */
    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    protected function getDefaults(): array
    {
        $rolesSet = $this->randomlyCreateRolesSet();

        return [
            'roles' => $rolesSet,
            'username' => self::faker()->userName(),
            'enabled' => self::faker()->boolean(),
            'lastConnectionTime' => self::faker()->dateTimeBetween('-20 days', '-5 days')
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this->afterInstantiate(function (User $user): void {
            // suppose that all users hase the same `123456` password
            $plainText = '123456';
            // hash the plain password
            $hashedPassword = $this->hasher->hashPassword($user, $plainText);
            // set the hashed password
            $user->setPassword($hashedPassword);
        });
    }

    protected static function getClass(): string
    {
        return User::class;
    }

    private function randomlyCreateRolesSet(): array
    {
        $rolesCount = \rand(0, \count(self::ROLES));
        $rolesSet = [];
        for ($i = 0; $i < $rolesCount; $i++) {
            $randomRole = self::ROLES[\array_rand(self::ROLES)];
            if (!\in_array($randomRole, $rolesSet)) {
                $rolesSet[] = $randomRole;
            }
        }

        return $rolesSet;
    }
}
