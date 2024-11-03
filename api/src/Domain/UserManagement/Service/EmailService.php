<?php

namespace App\Domain\UserManagement\Service;

use App\Infrastructure\Mailer\MailerService;

/**
 * Service for handling email-related operations within the application.
 * 
 * This service is responsible for generating and sending various types of emails,
 * including welcome emails, password reset emails, and more.
 */
class EmailService
{
    /**
     * @var MailerService The mailer service used to send emails.
     */
    private MailerService $mailerService;

    /**
     * Constructs the EmailService with the specified MailerService.
     * 
     * @param MailerService $mailerService The mailer service.
     */
    public function __construct(MailerService $mailerService)
    {
        $this->mailerService = $mailerService;
    }

    /**
     * Sends a welcome email to a newly registered user.
     * 
     * @param string $email Email of the user to send the welcome email to.
     * @param string $username Username of the user to send the welcome email to.
     * @return void
     */
    public function sendWelcomeEmail(string $email, string $username ): void
    {
        $this->mailerService->sendEmail(
            'no-reply@example.com',
            $email,
            'Welcome to Our Service',
            sprintf("Hello %s,\n\nThank you for registering with us!", $username())
        );
    }

    /**
     * Sends a password reset email to a user.
     * 
     * @param string $email Email of the user requesting a password reset.
     * @param string $resetToken The reset token for the user.
     * @return void
     */
    public function sendPasswordResetEmail(string $email, string $username, string $resetToken): void
    {
        $this->mailerService->sendEmail(
            'no-reply@example.com',
            $email,
            'Password Reset Request',
            sprintf("Hello %s,\n\nClick the link to reset your password: https://example.com/reset/%s", $username, $resetToken)
        );
    }
}
