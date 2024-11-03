<?php

namespace App\Infrastructure\Mailer;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;

/**
 * Service responsible for sending emails.
 * Provides an abstraction over Symfony Mailer to simplify email handling.
 */
class MailerService
{
    /**
     * @var MailerInterface
     */
    private MailerInterface $mailer;

    /**
     * Constructor for MailerService.
     * 
     * @param MailerInterface $mailer Symfony's mailer interface.
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Sends an email.
     * 
     * @param string $to Recipient's email address.
     * @param string $subject Subject of the email.
     * @param string $body Body content of the email.
     * @param string|null $from Optional sender's email address.
     * 
     * @return void
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface If the email cannot be sent.
     */
    public function sendEmail(
        string $to, 
        string $subject, 
        string $body, 
        ?string $from = null
    ): void {
        $email = (new Email())
            ->from($from ?: new Address('no-reply@example.com', 'System'))
            ->to($to)
            ->subject($subject)
            ->text($body)
            ->html($body);

        $this->mailer->send($email);
    }
}
