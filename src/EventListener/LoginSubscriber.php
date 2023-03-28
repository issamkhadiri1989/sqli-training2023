<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\User;
use App\Service\Account;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly Account $manager)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => [
                ['updateLastConnectionTime', 1],
                ['anotherFunction', 2]
            ],
            LoginFailureEvent::class => 'doSomething',
            ExceptionEvent::class => ['handleException', 9],
        ];
    }

    public function anotherFunction(LoginSuccessEvent $event): void
    {
        // @TODO do some logic...
    }
    public function updateLastConnectionTime(LoginSuccessEvent $event): void
    {
        /** @var User $user */
        $user = $event->getUser();
        $this->manager->updateLastConnectionTime($user);
    }

    public function doSomething(LoginFailureEvent $event)
    {
        // @TODO do something like cleaning
    }

    public function handleException(ExceptionEvent $event)
    {
        // @TODO do some logic...
    }
}
