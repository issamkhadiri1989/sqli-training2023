<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ErrorListener
{
    public function __construct(private readonly UrlGeneratorInterface $generator, private readonly Security $security)
    {
    }

    public function handleAccessDenied(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if ($exception instanceof AccessDeniedHttpException || $exception instanceof AccessDeniedException) {
            $user = $this->security->getUser();
            if (null !== $user) {
                $event->setResponse(new RedirectResponse($this->generator->generate('app_index')));
            }
        }
    }

    /*public function onKernelException(ExceptionEvent $event): void
    {

    }*/

    /*public function __invoke(ExceptionEvent $event): void
    {

    }*/
}
