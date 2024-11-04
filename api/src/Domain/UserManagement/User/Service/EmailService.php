<?php

namespace App\Domain\UserManagement\User\Service;

use App\Api\DTO\UserRegisteredDTO;
use App\Infrastructure\Mailer\MailerService;

/**
 * EmailService
 *
 * Service for handling user-related email operations within the application.
 *
 * This service is responsible for generating and sending various types of emails,
 * such as welcome emails for new users and password reset emails, ensuring that
 * user notifications are handled consistently.
 *
 * **Responsibilities**
 * - Send welcome emails to new users.
 * - Send password reset emails to users requesting a password reset.
 *
 * **Dependencies**
 * This service depends on the `MailerService` infrastructure component, which
 * encapsulates the actual email sending logic and integrates with external email providers.
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
     * @param MailerService $mailerService The mailer service responsible for sending emails.
     */
    public function __construct(MailerService $mailerService)
    {
        $this->mailerService = $mailerService;
    }

    /**
     * Sends a welcome email to a newly registered user.
     *
     * The email includes a personalized greeting for the new user, welcoming them to the service.
     * 
     * @param string $email The email address of the user to send the welcome email to.
     * @param string $username The username of the new user to include in the welcome message.
     * @return void
     */
    public function sendWelcomeEmail(UserRegisteredDTO $dto): void
    {
        $this->mailerService->sendEmail(
            'no-reply@example.com',
            $dto->getEmail(),
            'Welcome to Our Service',
            sprintf("Hello %s,\n\nThank you for registering with us!", $dto->getUsername())
        );
    }
}
