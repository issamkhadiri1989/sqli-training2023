<?php

declare(strict_types=1);

namespace App\Service\Mailer;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class MessageSender
{
    public function __construct(private readonly MailerInterface $mailer)
    {
    }

    /**
     * Simple method to send a email.7
     *
     * @param string $from
     * @param string $to
     * @param string $subject
     * @param string $mailTemplate
     * @param array $context
     *
     * @return bool
     *
     * @throws TransportExceptionInterface
     */
    public function sendMail(string $from, string $to, string $subject, string $mailTemplate, array $context = []): bool
    {
        $mailSentSuccessfully = false;
        $message = new TemplatedEmail();
        $message->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate($mailTemplate)
            ->context($context);
        try {
            // @TODO : throw a new TransportException() to simulate an error while sending the email.
            $this->mailer->send($message);

            $mailSentSuccessfully = true;
        } catch (TransportException) {
            // Do something when an error occured
        } finally {
            return $mailSentSuccessfully;
        }
    }
}